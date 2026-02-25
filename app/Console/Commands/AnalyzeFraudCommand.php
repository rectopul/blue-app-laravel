<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\FraudDetectionService;

class AnalyzeFraudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fraud:analyze {--user-id= : Analisar usuário específico} {--days=7 : Analisar usuários ativos nos últimos N dias}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executar análise de fraude em usuários';

    public function __construct(private FraudDetectionService $fraudDetectionService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($userId = $this->option('user-id')) {
            $user = User::findOrFail($userId);
            $this->analyzeUser($user);
        } else {
            $days = $this->option('days');
            $users = User::where('created_at', '>=', now()->subDays($days))
                ->orWhereHas('withdrawals', function ($query) use ($days) {
                    $query->where('created_at', '>=', now()->subDays($days));
                })
                ->get();

            $this->info("Analisando {$users->count()} usuários...");
            $bar = $this->output->createProgressBar($users->count());

            foreach ($users as $user) {
                $this->analyzeUser($user);
                $bar->advance();
            }

            $bar->finish();
        }

        $this->newLine();
        $this->info('Análise de fraude concluída!');
    }

    private function analyzeUser(User $user)
    {
        try {
            $analysis = $this->fraudDetectionService->analyzeUser($user);

            if ($analysis['total_risk_score'] > 0) {
                $this->warn("Usuário {$user->id} - Risco: {$analysis['risk_level']} (Score: {$analysis['total_risk_score']})");
            }
        } catch (\Exception $e) {
            $this->error("Erro ao analisar usuário {$user->id}: {$e->getMessage()}");
        }
    }
}
