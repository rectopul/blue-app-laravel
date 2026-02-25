<?php

namespace App\Console\Commands;

use App\Http\Controllers\admin\AdminController;
use App\Models\User;
use App\Models\UserLedger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class DailyTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executa tarefas diárias à meia-noite';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $logDate = Carbon::now()->format('Y-m-d');
        $logFileName = "daily-payments-{$logDate}.log";

        Log::build([
            'driver' => 'single',
            'path' => storage_path("logs/{$logFileName}"),
        ])->info("Iniciando processamento de pagamentos diários em " . now());

        try {
            // Busca apenas usuários com saldo a receber
            $users = User::where('receive_able_amount', '>', 0)->get();

            DB::beginTransaction();

            foreach ($users as $user) {
                try {
                    $this->processUserPayment($user, $logFileName);
                } catch (\Exception $e) {
                    $this->logMessage(
                        "Erro ao processar pagamento do usuário {$user->name} (ID: {$user->id}): {$e->getMessage()}",
                        'error',
                        $logFileName
                    );
                    continue;
                }
            }

            DB::commit();
            $this->logMessage("Processamento finalizado com sucesso!", 'info', $logFileName);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logMessage("Erro crítico durante o processamento: {$e->getMessage()}", 'error', $logFileName);
            throw $e;
        }
    }

    private function processUserPayment(User $user, string $logFileName)
    {
        $amount = $user->receive_able_amount;

        // Cria o registro no ledger
        $ledger = new UserLedger([
            'user_id' => $user->id,
            'reason' => 'daily_income',
            'perticulation' => 'Commission Received',
            'amount' => $amount,
            'credit' => $amount,
            'status' => 'approved',
            'date' => now()
        ]);
        $ledger->save();

        // Atualiza o saldo do usuário
        $user->balance += $amount;
        $user->receive_able_amount = 0;
        $user->save();

        $message = sprintf(
            "Usuário: %s (ID: %d) recebeu %.2f em %s",
            $user->name,
            $user->id,
            $amount,
            now()->format('Y-m-d H:i:s')
        );

        $this->logMessage($message, 'info', $logFileName);
    }

    private function logMessage(string $message, string $level, string $logFileName)
    {
        // Log no arquivo específico
        Log::build([
            'driver' => 'single',
            'path' => storage_path("logs/{$logFileName}"),
        ])->$level($message);

        // Log no console
        if ($level === 'error') {
            $this->error($message);
        } else {
            $this->info($message);
        }

        // Log padrão do Laravel
        Log::$level($message);
    }
}
