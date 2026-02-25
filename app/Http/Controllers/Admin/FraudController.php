<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FraudAlert;
use App\Models\User;
use App\Services\FraudDetectionService;
use Illuminate\Http\Request;

class FraudController extends Controller
{
    public function __construct(private FraudDetectionService $fraudDetectionService) {}

    public function index(Request $request)
    {
        $alerts = FraudAlert::with('user')
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->alert_type, function ($query, $type) {
                $query->where('alert_type', $type);
            })
            ->when($request->risk_level, function ($query, $level) {
                $thresholds = ['low' => 30, 'medium' => 60, 'high' => 80];
                if ($level === 'high') {
                    $query->where('risk_score', '>=', $thresholds['high']);
                } elseif ($level === 'medium') {
                    $query->whereBetween('risk_score', [$thresholds['medium'], $thresholds['high'] - 1]);
                } elseif ($level === 'low') {
                    $query->whereBetween('risk_score', [$thresholds['low'], $thresholds['medium'] - 1]);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => FraudAlert::count(),
            'pending' => FraudAlert::where('status', 'pending')->count(),
            'high_risk' => FraudAlert::where('risk_score', '>=', 80)->count(),
            'today' => FraudAlert::whereDate('created_at', today())->count()
        ];

        return view('admin.fraud.index', compact('alerts', 'stats'));
    }

    public function show(FraudAlert $alert)
    {
        $alert->load('user', 'resolver');
        $userAnalysis = $this->fraudDetectionService->analyzeUser($alert->user);

        return view('admin.fraud.show', compact('alert', 'userAnalysis'));
    }

    public function update(Request $request, FraudAlert $alert)
    {
        $request->validate([
            'status' => 'required|in:pending,investigating,resolved,false_positive',
            'notes' => 'nullable|string|max:1000'
        ]);

        $alert->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'resolved_at' => in_array($request->status, ['resolved', 'false_positive']) ? now() : null,
            'resolved_by' => in_array($request->status, ['resolved', 'false_positive']) ? auth()->id() : null
        ]);

        return redirect()->back()->with('success', 'Status do alerta atualizado com sucesso!');
    }

    public function analyzeUser(Request $request, User $user)
    {
        $analysis = $this->fraudDetectionService->analyzeUser($user);

        if ($request->expectsJson()) {
            return response()->json($analysis);
        }

        return view('admin.fraud.user-analysis', compact('user', 'analysis'));
    }

    public function report(Request $request)
    {
        $days = $request->get('days', 30);
        $report = $this->fraudDetectionService->getFraudReport($days);

        if ($request->expectsJson()) {
            return response()->json($report);
        }

        return view('admin.fraud.report', compact('report'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'alert_ids' => 'required|array',
            'alert_ids.*' => 'exists:fraud_alerts,id',
            'action' => 'required|in:resolve,investigate,false_positive'
        ]);

        $status = [
            'resolve' => 'resolved',
            'investigate' => 'investigating',
            'false_positive' => 'false_positive'
        ][$request->action];

        FraudAlert::whereIn('id', $request->alert_ids)->update([
            'status' => $status,
            'resolved_at' => in_array($status, ['resolved', 'false_positive']) ? now() : null,
            'resolved_by' => in_array($status, ['resolved', 'false_positive']) ? auth()->id() : null
        ]);

        return redirect()->back()->with('success', 'Ação aplicada aos alertas selecionados!');
    }

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
        $alert = \App\Models\FraudAlert::findOrFail($alertId);

        $alert->update([
            'status' => 'false_positive',
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'notes' => $request->get('notes', 'Marcado como falso positivo via API')
        ]);

        return response()->json(['message' => 'Alerta marcado como falso positivo']);
    }
}
