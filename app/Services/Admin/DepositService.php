<?php

namespace App\Services\Admin;

use App\Models\User;
use App\Exceptions\SecuritySuspensionException;
use App\Http\Controllers\user\UserController;
use App\Models\Deposit;
use App\Models\UserLedger;
use App\Services\FraudDetectionService;
use App\Services\VizionPay\VizionPayService;
use Illuminate\Support\Facades\{Cache, Log, DB};

class DepositService
{
    public function __construct(private VizionPayService $vizionPayService, private UserController $userController, private FraudDetectionService $fraudDetectionService) {}

    public function generateDeposit(User $user, float $amount)
    {

        $transactionId = Deposit::generateTransactionId();
        $deposit = Deposit::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'security_hash' => $this->vizionPayService->generateSignature($transactionId),
            'status' => 'approved',
            'transaction_id' => $transactionId,
            'order_id' => $transactionId,
            'date' => now(),
        ]);

        if ($deposit) {
            Log::debug('Depósito encontrado', ['deposit_id' => $deposit->id]);

            DB::beginTransaction();

            try {
                $deposit->status = 'approved';
                $deposit->save();
                Log::debug('Depósito atualizado para aprovado', ['deposit_id' => $deposit->id]);

                $user = $deposit->user;
                $oldBalance = $user->balance;

                $this->fraudDetectionService->analyzeUser($user);

                $user->increment('balance', $deposit->amount);

                Log::info('Depósito aprovado - Saldo atualizado.', [
                    'deposit_id' => $deposit->id,
                    'user_id' => $user->id,
                    'old_balance' => $oldBalance,
                    'new_balance' => $user->fresh()->balance,
                    'amount_added' => $deposit->amount,
                ]);

                // Ledger
                UserLedger::create([
                    'user_id'       => $user->id,
                    'reason'        => 'deposit',
                    'perticulation' => UserLedger::generatePerticulation('deposit', $deposit->amount),
                    'amount'        => $deposit->amount,
                    'credit'        => $deposit->amount,
                    'status'        => 'approved'
                ]);

                // Ao processar um depósito
                $referrer = $user->referrer; // Nível 1 (quem indicou o usuário atual)
                $level = 1;

                Log::info('Iniciando processo de indicação.');

                while ($referrer && $level <= 3) {
                    Log::info('Lista de usuário para comissão de indicação', [
                        'level' => $level,
                        'referrer_id' => $referrer->id,
                        'referrer_name' => $referrer->name,
                    ]);

                    $this->userController->payUserReferral($referrer, $deposit->amount, $level, $user->id);

                    // sobe para o próximo nível
                    $referrer = $referrer->referrer;
                    $level++;
                }

                DB::commit();

                return response()->json(['message' => 'Deposit approved'], 200);
            } catch (\Exception $e) {
                DB::rollBack();

                Log::error('Erro ao processar webhook de depósito.', [
                    'deposit_id' => $deposit->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return ['error' => 'Processing failed', 'details' => $e->getMessage()];
            }
        } else {
            Log::error('Depósito não encontrado', ['transaction_id' => $verify['transaction_id']]);

            return [
                'status' => 'error',
                'error' => 'Deposit not found.'
            ];
        }
    }
}
