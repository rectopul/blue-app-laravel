<?php

namespace App\Services;

class FraudMetricsService
{
    public function getDailyMetrics($date = null)
    {
        $date = $date ?? today();

        return [
            'new_alerts' => FraudAlert::whereDate('created_at', $date)->count(),
            'high_risk_alerts' => FraudAlert::whereDate('created_at', $date)
                ->where('risk_score', '>=', 80)->count(),
            'blocked_withdrawals' => \App\Models\Withdrawal::whereDate('created_at', $date)
                ->where('status', 'blocked')->count(),
            'false_positives' => FraudAlert::whereDate('resolved_at', $date)
                ->where('status', 'false_positive')->count(),
            'prevention_rate' => $this->calculatePreventionRate($date),
        ];
    }

    private function calculatePreventionRate($date): float
    {
        $totalSuspiciousActivity = FraudAlert::whereDate('created_at', $date)->count();
        $preventedFraud = FraudAlert::whereDate('created_at', $date)
            ->whereIn('status', ['resolved', 'investigating'])
            ->where('risk_score', '>=', 60)->count();

        return $totalSuspiciousActivity > 0 ?
            round(($preventedFraud / $totalSuspiciousActivity) * 100, 2) : 0;
    }
}
