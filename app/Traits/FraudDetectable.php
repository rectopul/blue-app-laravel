<?php

namespace App\Traits;

use App\Models\FraudAlert;
use App\Services\FraudDetectionService;

trait FraudDetectable
{
    public function fraudAlerts()
    {
        return $this->hasMany(FraudAlert::class);
    }

    public function getLatestFraudAnalysis()
    {
        $service = app(FraudDetectionService::class);
        return $service->analyzeUser($this);
    }

    public function isHighRisk(): bool
    {
        return $this->fraudAlerts()
            ->where('status', 'pending')
            ->where('risk_score', '>=', 80)
            ->exists();
    }

    public function getRiskLevel(): string
    {
        $totalScore = $this->fraudAlerts()
            ->where('status', 'pending')
            ->sum('risk_score');

        if ($totalScore >= 80) return 'high';
        if ($totalScore >= 60) return 'medium';
        if ($totalScore >= 30) return 'low';
        return 'none';
    }

    public function canWithdraw(): bool
    {
        return !$this->isHighRisk();
    }

    public function blockAccount(string $reason = 'Atividade suspeita detectada')
    {
        $this->update(['status' => 'blocked']);

        // Criar alerta automático
        FraudAlert::create([
            'user_id' => $this->id,
            'alert_type' => 'ACCOUNT_BLOCKED',
            'risk_score' => 100,
            'description' => $reason,
            'status' => 'investigating'
        ]);
    }
}
