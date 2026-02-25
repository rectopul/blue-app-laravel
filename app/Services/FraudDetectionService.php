<?php
// Service para detecção de fraudes
namespace App\Services;

use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\UserLedger;
use App\Models\FraudAlert;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FraudDetectionService
{
    private $riskThresholds = [
        'low' => 30,
        'medium' => 60,
        'high' => 80
    ];

    /**
     * Análise completa de fraude para um usuário
     */
    public function analyzeUser(User $user): array
    {
        $analysis = [
            'user_id' => $user->id,
            'total_risk_score' => 0,
            'alerts' => [],
            'recommendations' => []
        ];

        // Executar todas as verificações
        $checks = [
            'checkWithdrawalWithoutDeposit',
            'checkSuspiciousIPPattern',
            'checkRapidReferralCreation',
            'checkUnusualWithdrawalPattern',
            'checkBalanceManipulation',
            'checkFakeReferralNetwork',
            'checkMultipleAccountsSameData',
            'checkSuspiciousInvestmentPattern'
        ];

        foreach ($checks as $check) {
            $result = $this->$check($user);
            if ($result['risk_score'] > 0) {
                $analysis['alerts'][] = $result;
                $analysis['total_risk_score'] += $result['risk_score'];

                // Criar alerta no banco de dados
                $this->createFraudAlert($user, $result);
            }
        }

        $analysis['risk_level'] = $this->calculateRiskLevel($analysis['total_risk_score']);
        $analysis['recommendations'] = $this->generateRecommendations($analysis);

        return $analysis;
    }

    /**
     * Verificar saque sem depósito
     */
    private function checkWithdrawalWithoutDeposit(User $user): array
    {
        $hasDeposits = $user->deposits()->where('status', 'approved')->exists();
        $hasWithdrawals = $user->withdrawals()->exists();
        $hasOnlyLedgerCredits = $user->ledgers()
            ->where('credit', '>', 0)
            ->where('reason', 'like', '%daily_income%')
            ->exists();

        if ($hasWithdrawals && !$hasDeposits && $hasOnlyLedgerCredits) {
            return [
                'type' => 'NO_DEPOSIT_WITHDRAWAL',
                'risk_score' => 70,
                'description' => 'Usuário solicitou saque sem nunca ter feito depósito',
                'data' => [
                    'withdrawals_count' => $user->withdrawals()->count(),
                    'deposits_count' => 0,
                    'total_withdrawal_amount' => $user->withdrawals()->sum('amount')
                ]
            ];
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar padrão suspeito de IP
     */
    private function checkSuspiciousIPPattern(User $user): array
    {
        // Verificar se vários usuários compartilham o mesmo IP
        $sameIPUsers = User::where('ip', $user->ip)
            ->where('id', '!=', $user->id)
            ->count();

        if ($sameIPUsers >= 5) {
            return [
                'type' => 'SUSPICIOUS_IP_PATTERN',
                'risk_score' => 50 + ($sameIPUsers * 5),
                'description' => "IP compartilhado por {$sameIPUsers} usuários",
                'data' => [
                    'ip' => $user->ip,
                    'users_count' => $sameIPUsers + 1
                ]
            ];
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar criação rápida de indicados
     */
    private function checkRapidReferralCreation(User $user): array
    {
        $referrals = $user->referrals()
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->get();

        if ($referrals->count() >= 10) {
            // Verificar se os indicados têm padrões suspeitos
            $suspiciousPatterns = 0;
            $ipsUsed = [];

            foreach ($referrals as $referral) {
                if (in_array($referral->ip, $ipsUsed)) {
                    $suspiciousPatterns++;
                }
                $ipsUsed[] = $referral->ip;

                // Verificar se nunca fizeram depósito
                if (!$referral->deposits()->exists()) {
                    $suspiciousPatterns++;
                }
            }

            if ($suspiciousPatterns >= 18) {
                return [
                    'type' => 'RAPID_REFERRAL_CREATION',
                    'risk_score' => 60 + ($suspiciousPatterns * 5),
                    'description' => "Criou {$referrals->count()} indicados em 7 dias com padrões suspeitos",
                    'data' => [
                        'referrals_count' => $referrals->count(),
                        'suspicious_patterns' => $suspiciousPatterns,
                        'period_days' => 7
                    ]
                ];
            }
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar padrão incomum de saques
     */
    private function checkUnusualWithdrawalPattern(User $user): array
    {
        $withdrawals = $user->withdrawals()->get();

        if ($withdrawals->count() == 0) {
            return ['risk_score' => 0];
        }

        $totalWithdrawn = $withdrawals->sum('amount');
        $totalDeposited = $user->deposits()->where('status', 'approved')->sum('amount');

        // Verificar se saque é muito maior que depósito
        if ($totalWithdrawn > ($totalDeposited * 3) && $totalDeposited > 0) {
            return [
                'type' => 'UNUSUAL_WITHDRAWAL_PATTERN',
                'risk_score' => 65,
                'description' => 'Valor de saque muito superior ao depositado',
                'data' => [
                    'total_withdrawn' => $totalWithdrawn,
                    'total_deposited' => $totalDeposited,
                    'ratio' => round($totalWithdrawn / max($totalDeposited, 1), 2)
                ]
            ];
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar manipulação de saldo
     */
    private function checkBalanceManipulation(User $user): array
    {
        // Verificar créditos muito altos sem origem clara
        $totalCredits = $user->ledgers()->sum('credit');
        $totalDebits = $user->ledgers()->sum('debit');
        $totalDeposits = $user->deposits()->where('status', 'approved')->sum('amount');

        $unexplainedCredits = $totalCredits - ($totalDeposits + ($totalDeposits * 0.5)); // 30% de rendimento máximo esperado

        if ($unexplainedCredits > 1000) { // R$ 1000
            return [
                'type' => 'BALANCE_MANIPULATION',
                'risk_score' => 75,
                'description' => 'Créditos não justificados no ledger',
                'data' => [
                    'total_credits' => $totalCredits,
                    'total_deposits' => $totalDeposits,
                    'unexplained_credits' => $unexplainedCredits
                ]
            ];
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar rede de indicação falsa
     */
    private function checkFakeReferralNetwork(User $user): array
    {
        $counts = $user->getReferralCounts();
        $totalReferrals = array_sum($counts);

        if ($totalReferrals >= 20) {
            // Analisar padrões suspeitos na rede
            $level1Users = User::where('ref_by', $user->ref_id)->get();
            $suspiciousCount = 0;
            $totalInvestments = 0;

            foreach ($level1Users as $referral) {
                // Verificar se nunca fizeram depósito real
                if (!$referral->deposits()->where('status', 'approved')->exists()) {
                    $suspiciousCount++;
                }

                // Verificar investimentos sem depósito
                $investments = $referral->ledgers()
                    ->where('reason', 'like', '%daily_income%')
                    ->sum('credit');
                if ($investments > 0) {
                    $totalInvestments += $investments;
                }
            }

            $suspiciousRatio = $suspiciousCount / $level1Users->count();

            if ($suspiciousRatio > 0.6) { // 60% dos indicados são suspeitos
                return [
                    'type' => 'FAKE_REFERRAL_NETWORK',
                    'risk_score' => 55,
                    'description' => 'Rede de indicação com padrões artificiais',
                    'data' => [
                        'total_referrals' => $totalReferrals,
                        'suspicious_count' => $suspiciousCount,
                        'suspicious_ratio' => round($suspiciousRatio * 100, 2)
                    ]
                ];
            }
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar múltiplas contas com dados similares
     */
    private function checkMultipleAccountsSameData(User $user): array
    {
        // Verificar usuários com dados similares
        $similarUsers = User::where(function ($query) use ($user) {
            $query->where('phone', $user->phone)
                ->orWhere('ip', $user->ip);
        })->where('id', '!=', $user->id)->count();

        if ($similarUsers >= 3) {
            return [
                'type' => 'MULTIPLE_ACCOUNTS_SAME_DATA',
                'risk_score' => 60,
                'description' => 'Múltiplas contas compartilham dados pessoais',
                'data' => [
                    'similar_accounts' => $similarUsers
                ]
            ];
        }

        return ['risk_score' => 0];
    }

    /**
     * Verificar padrão suspeito de investimentos
     */
    private function checkSuspiciousInvestmentPattern(User $user): array
    {
        $investmentCredits = $user->ledgers()
            ->where('reason', 'like', '%daily_income%')
            ->where('credit', '>', 0)
            ->get();

        if ($investmentCredits->count() == 0) {
            return ['risk_score' => 0];
        }

        // Verificar se recebe rendimentos sem ter feito depósitos
        $hasDeposits = $user->deposits()->where('status', 'approved')->exists();
        $totalInvestmentReturns = $investmentCredits->sum('credit');

        if (!$hasDeposits && $totalInvestmentReturns > 100) {
            return [
                'type' => 'SUSPICIOUS_INVESTMENT_PATTERN',
                'risk_score' => 70,
                'description' => 'Recebe rendimentos de investimento sem depósitos',
                'data' => [
                    'investment_returns' => $totalInvestmentReturns,
                    'has_deposits' => false
                ]
            ];
        }

        return ['risk_score' => 0];
    }

    /**
     * Criar alerta de fraude
     */
    private function createFraudAlert(User $user, array $alertData): void
    {
        // Verificar se já existe um alerta similar recente
        $existingAlert = FraudAlert::where('user_id', $user->id)
            ->where('alert_type', $alertData['type'])
            ->where('status', 'pending')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->first();

        if (!$existingAlert) {
            FraudAlert::create([
                'user_id' => $user->id,
                'alert_type' => $alertData['type'],
                'risk_score' => $alertData['risk_score'],
                'description' => $alertData['description'],
                'data' => $alertData['data'] ?? [],
                'status' => FraudAlert::STATUS_PENDING
            ]);
        }
    }

    /**
     * Calcular nível de risco
     */
    private function calculateRiskLevel(int $totalScore): string
    {
        if ($totalScore >= $this->riskThresholds['high']) {
            return 'high';
        } elseif ($totalScore >= $this->riskThresholds['medium']) {
            return 'medium';
        } elseif ($totalScore >= $this->riskThresholds['low']) {
            return 'low';
        }
        return 'none';
    }

    /**
     * Gerar recomendações baseadas na análise
     */
    private function generateRecommendations(array $analysis): array
    {
        $recommendations = [];

        if ($analysis['total_risk_score'] >= 80) {
            $recommendations[] = 'Bloquear conta imediatamente';
            $recommendations[] = 'Investigar rede de indicações';
            $recommendations[] = 'Revisar todas as transações';
        } elseif ($analysis['total_risk_score'] >= 60) {
            $recommendations[] = 'Requerer verificação adicional de identidade';
            $recommendations[] = 'Limitar saques até verificação';
            $recommendations[] = 'Monitorar atividade closely';
        } elseif ($analysis['total_risk_score'] >= 30) {
            $recommendations[] = 'Aumentar monitoramento da conta';
            $recommendations[] = 'Requerer comprovantes para próximos saques';
        }

        return $recommendations;
    }

    /**
     * Analisar transação de saque antes da aprovação
     */
    public function analyzeWithdrawal(Withdrawal $withdrawal): array
    {
        $user = $withdrawal->user;
        $analysis = $this->analyzeUser($user);

        // Verificações específicas para saque
        $withdrawalRisk = 0;

        // Verificar se é o primeiro saque sem depósitos
        $hasDeposits = $user->deposits()->where('status', 'completed')->exists();
        if (!$hasDeposits) {
            $withdrawalRisk += 50;
        }

        // Verificar valor do saque em relação ao saldo
        if ($withdrawal->amount > ($user->balance * 0.9)) {
            $withdrawalRisk += 30;
        }

        $analysis['withdrawal_risk'] = $withdrawalRisk;
        $analysis['total_risk_score'] += $withdrawalRisk;
        $analysis['should_approve'] = $analysis['total_risk_score'] < 60;

        return $analysis;
    }

    /**
     * Obter relatório de fraudes
     */
    public function getFraudReport(int $days = 30): array
    {
        $alerts = FraudAlert::with('user')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->get()
            ->groupBy('alert_type');

        $report = [
            'period_days' => $days,
            'total_alerts' => FraudAlert::where('created_at', '>=', Carbon::now()->subDays($days))->count(),
            'by_type' => [],
            'high_risk_users' => [],
            'pending_investigations' => FraudAlert::where('status', 'pending')->count()
        ];

        foreach ($alerts as $type => $typeAlerts) {
            $report['by_type'][$type] = [
                'count' => $typeAlerts->count(),
                'avg_risk_score' => round($typeAlerts->avg('risk_score'), 2),
                'total_risk_score' => $typeAlerts->sum('risk_score')
            ];
        }

        // Usuários de alto risco
        $highRiskUsers = DB::table('fraud_alerts')
            ->select('user_id', DB::raw('SUM(risk_score) as total_risk'))
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('user_id')
            ->having('total_risk', '>=', 80)
            ->orderBy('total_risk', 'desc')
            ->limit(20)
            ->get();

        $report['high_risk_users'] = $highRiskUsers;

        return $report;
    }
}
