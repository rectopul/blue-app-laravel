<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FraudAlert;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\FraudDetectionService;

class FraudController extends Controller
{
    public function __construct(private FraudDetectionService $fraudDetectionService) {}

    /**
     * Verificar risco de um usuário
     */
    public function checkUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $analysis = $this->fraudDetectionService->analyzeUser($user);

        return response()->json([
            'user_id' => $userId,
            'risk_level' => $analysis['risk_level'],
            'risk_score' => $analysis['total_risk_score'],
            'can_withdraw' => $analysis['total_risk_score'] < 80,
            'alerts_count' => count($analysis['alerts']),
            'recommendations' => $analysis['recommendations']
        ]);
    }

    /**
     * Analisar saque específico
     */
    public function checkWithdrawal(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0'
        ]);

        $user = User::findOrFail($request->user_id);

        // Simular saque para análise
        $mockWithdrawal = new \App\Models\Withdrawal([
            'user_id' => $user->id,
            'amount' => $request->amount
        ]);
        $mockWithdrawal->user = $user;

        $analysis = $this->fraudDetectionService->analyzeWithdrawal($mockWithdrawal);

        return response()->json([
            'should_approve' => $analysis['should_approve'],
            'risk_score' => $analysis['total_risk_score'],
            'withdrawal_risk' => $analysis['withdrawal_risk'],
            'alerts' => $analysis['alerts'],
            'recommendations' => $analysis['recommendations']
        ]);
    }

    /**
     * Relatório de estatísticas
     */
    public function stats(Request $request)
    {
        $days = $request->get('days', 30);
        $report = $this->fraudDetectionService->getFraudReport($days);

        return response()->json($report);
    }

    /**
     * Marcar alerta como falso positivo
     */
    public function markFalsePositive(Request $request, $alertId)
    {
        $alert = FraudAlert::findOrFail($alertId);

        $alert->update([
            'status' => 'false_positive',
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'notes' => $request->get('notes', 'Marcado como falso positivo via API')
        ]);

        return response()->json(['message' => 'Alerta marcado como falso positivo']);
    }
}
