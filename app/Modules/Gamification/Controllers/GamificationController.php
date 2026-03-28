<?php

namespace App\Modules\Gamification\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Purchase;
use App\Models\UserLedger;
use App\Modules\Gamification\Models\GamificationSetting;
use App\Modules\Gamification\Models\UserGamificationEgg;
use App\Modules\Gamification\Services\GamificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GamificationController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    /**
     * Collect an egg and award bonus.
     *
     * @param Request $request
     * @param int $settingId
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectEgg(Request $request, $settingId)
    {
        $user = auth()->user();
        $setting = GamificationSetting::find($settingId);

        if (!$setting || !$setting->is_active) {
            return response()->json(['message' => 'Ovo inválido.'], 404);
        }

        // 1. Check if user already collected this egg
        $alreadyCollected = UserGamificationEgg::where('user_id', $user->id)
            ->where('gamification_setting_id', $settingId)
            ->exists();

        if ($alreadyCollected) {
            return response()->json(['message' => 'Ovo já coletado.'], 400);
        }

        // 2. Security validation: Check active purchase
        $hasActivePurchase = Purchase::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();

        if (!$hasActivePurchase) {
            return response()->json(['message' => 'É necessário uma compra ativa para coletar ovos.'], 403);
        }

        // 3. Security validation: Check referral count
        $referralCount = $user->levelOneReferrals()->count();
        if ($referralCount < $setting->required_referrals) {
            return response()->json(['message' => 'Requisitos de indicação não atendidos.'], 403);
        }

        // 4. Update user balance and log it
        try {
            DB::beginTransaction();

            // Mark as collected
            UserGamificationEgg::create([
                'user_id' => $user->id,
                'gamification_setting_id' => $setting->id,
                'collected_at' => now(),
            ]);

            // Add balance
            $user->addBalance($setting->bonus_reward);

            // Create ledger entry
            $user->ledgers()->create([
                'reason' => 'gamification_bonus',
                'perticulation' => "Bônus por ovo escondido ({$setting->page_name})",
                'amount' => $setting->bonus_reward,
                'credit' => $setting->bonus_reward,
                'debit' => 0,
                'status' => 'approved',
                'date' => now()->format('d-m-Y H:i'),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Parabéns! Você coletou um bônus de R$ ' . number_format($setting->bonus_reward, 2, ',', '.'),
                'bonus' => $setting->bonus_reward,
                'new_balance' => $user->balance,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao coletar ovo gamification: ' . $e->getMessage());
            return response()->json(['message' => 'Ocorreu um erro ao coletar o bônus.'], 500);
        }
    }
}
