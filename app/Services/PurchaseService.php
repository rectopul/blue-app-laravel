<?php

namespace App\Services;

use App\Models\Package;
use App\Models\Purchase;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Rebate;
use App\Exceptions\{
    InsufficientBalanceException,
    PackageNotAvailableException,
    DuplicatePurchaseException,
    SecuritySuspensionException,
    RateLimitExceededException
};
use Illuminate\Support\Facades\{DB, Cache, Log};
use Carbon\Carbon;

class PurchaseService
{
    protected AntifraudService $antifraudService;

    public function __construct(AntifraudService $antifraudService)
    {
        $this->antifraudService = $antifraudService;
    }

    /**
     * Processar compra de pacote
     */
    public function processPurchase(User $user, int $packageId, string $ip): array
    {
        // Verificação de rate limiting
        $this->checkRateLimit($user->id, $ip);

        // Verificação antifraude
        $this->antifraudService->checkUser($user, $ip);

        return DB::transaction(function () use ($user, $packageId) {
            // Lock do usuário para evitar race conditions
            $userLocked = User::where('id', $user->id)
                ->lockForUpdate()
                ->first();

            if (!$userLocked) {
                throw new \Exception('Usuário não encontrado');
            }

            // Verificar se o pacote existe e está ativo
            $package = Package::where('id', $packageId)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if (!$package) {
                throw new PackageNotAvailableException();
            }

            // Verificar se já possui compra ativa do mesmo pacote
            $existingPurchase = Purchase::where('user_id', $userLocked->id)
                ->where('package_id', $packageId)
                ->where('status', 'active')
                ->where('expires_at', '>', now())
                ->exists();

            if ($existingPurchase) {
                throw new DuplicatePurchaseException();
            }

            // Verificar saldo suficiente
            if ($userLocked->balance < $package->price) {
                throw new InsufficientBalanceException();
            }

            // Débito do saldo do usuário
            $userLocked->balance -= $package->price;
            $userLocked->save();

            // Criar registro da compra
            $purchase = Purchase::create([
                'user_id' => $userLocked->id,
                'package_id' => $package->id,
                'amount' => $package->price,
                'status' => 'active',
                'purchased_at' => now(),
                'expires_at' => now()->addDays((int)$package->validity),
                'validity' => now()->addDays((int)$package->validity),
                'transaction_hash' => $this->generateTransactionHash($userLocked->id, $packageId)
            ]);

            // Registrar débito no ledger
            UserLedger::create([
                'user_id' => $userLocked->id,
                'amount' => -$package->price,
                'reason' => 'package_purchase',
                'reference_id' => $purchase->id,
                'reference_type' => Purchase::class,
                'status' => 'completed',
                'created_at' => now()
            ]);

            // Processar comissões de indicação
            $user->processComissionReferral($package->price, 'Comissão de indicação pela compra do pacote ' . $package->name);

            // Limpar cache relacionado ao usuário
            Cache::forget("user_balance_{$userLocked->id}");
            Cache::forget("user_purchases_{$userLocked->id}");

            return [
                'purchase_id' => $purchase->id,
                'package_name' => $package->name,
                'amount' => $package->price,
                'new_balance' => $userLocked->fresh()->balance
            ];
        });
    }

    /**
     * Verificar rate limiting
     */
    protected function checkRateLimit(int $userId, string $ip): void
    {
        $userKey = "purchase_attempts_user_{$userId}";
        $ipKey = "purchase_attempts_ip_" . str_replace('.', '_', $ip);

        $userAttempts = Cache::get($userKey, 0);
        $ipAttempts = Cache::get($ipKey, 0);

        if ($userAttempts >= 20 || $ipAttempts >= 30) {
            Log::warning('Rate limit exceeded', [
                'user_id' => $userId,
                'ip' => $ip,
                'user_attempts' => $userAttempts,
                'ip_attempts' => $ipAttempts
            ]);
            throw new RateLimitExceededException();
        }

        // Incrementar contadores
        Cache::put($userKey, $userAttempts + 1, now()->addMinutes(15));
        Cache::put($ipKey, $ipAttempts + 1, now()->addMinutes(15));
    }

    /**
     * Processar comissões de indicação (até 3 níveis)
     */
    protected function processReferralCommissions(User $buyer, float $amount, int $purchaseId): void
    {
        $rebate = Rebate::where('status', 'active')->first();
        if (!$rebate) return;

        $currentUser = $buyer;
        $levels = [
            1 => $rebate->first_level_percentage,
            2 => $rebate->second_level_percentage,
            3 => $rebate->third_level_percentage
        ];

        foreach ($levels as $level => $percentage) {
            if ($percentage <= 0) continue;

            $referrer = $currentUser->referrer;
            if (!$referrer) break;

            $commissionAmount = ($amount * $percentage) / 100;

            // Atualizar saldo do referrer
            $referrer->balance += $commissionAmount;
            $referrer->save();

            // Registrar no ledger
            UserLedger::create([
                'user_id' => $referrer->id,
                'amount' => $commissionAmount,
                'reason' => 'referral_commission',
                'reference_id' => $purchaseId,
                'reference_type' => Purchase::class,
                'step' => $level,
                'status' => 'completed',
                'metadata' => json_encode([
                    'buyer_id' => $buyer->id,
                    'level' => $level,
                    'percentage' => $percentage,
                    'original_amount' => $amount
                ])
            ]);

            $currentUser = $referrer;
        }
    }

    /**
     * Gerar hash único da transação
     */
    protected function generateTransactionHash(int $userId, int $packageId): string
    {
        return hash('sha256', $userId . $packageId . now()->timestamp . uniqid());
    }
}
