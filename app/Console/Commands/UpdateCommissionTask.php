<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Purchase;
use App\Models\Package;
use App\Models\UserLedger;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UpdateCommissionTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-commission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza as comissões diárias dos usuários baseado nos pacotes ativos';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Updating commissions...');
        // Definindo timezone de São Paulo
        date_default_timezone_set('America/Sao_Paulo');

        $logDate = Carbon::now()->format('Y-m-d');
        $logFileName = "commission-updates-{$logDate}.log";

        Log::build([
            'driver' => 'single',
            'path' => storage_path("logs/{$logFileName}"),
        ])->info("Iniciando atualização de comissões em " . now());

        $today = Carbon::now()->startOfDay();

        // Verifica se é fim de semana (sábado = 6, domingo = 0)
        if ($today->dayOfWeek === 0 || $today->dayOfWeek === 6) {
            Log::build([
                'driver' => 'single',
                'path' => storage_path("logs/{$logFileName}"),
            ])->info("Hoje é fim de semana. Pagamentos não serão processados.");
            return;
        }

        // Busca todos os purchases ativos que devem ser pagos hoje
        $purchases = Purchase::where('status', 'active')
            ->whereDate('date', $today)
            ->get();

        foreach ($purchases as $purchase) {
            $user = User::find($purchase->user_id);
            if (!$user) continue;

            $package = Package::find($purchase->package_id);
            if (!$package) continue;

            DB::beginTransaction();
            try {
                // Atualiza o saldo do usuário
                $user->balance += $purchase->daily_income;
                $user->save();

                // Atualiza a data do próximo pagamento
                // Se hoje for sexta-feira, adiciona 3 dias para pular o fim de semana
                if ($today->dayOfWeek === 5) {
                    $purchase->date = now()->addDays(3)->startOfDay();
                } else {
                    $purchase->date = now()->addDay()->startOfDay();
                }
                $purchase->save();

                // Registra o ledger
                $ledger = new UserLedger();
                $ledger->user_id = $user->id;
                $ledger->reason = 'daily_income';
                $ledger->perticulation = $package->name . ' Commission Added';
                $ledger->amount = $purchase->daily_income;
                $ledger->credit = $purchase->daily_income;
                $ledger->status = 'approved';
                $ledger->date = now();
                $ledger->save();

                // Verifica validade
                $checkExpire = Carbon::parse($purchase->validity);
                if ($checkExpire->isPast()) {
                    $purchase->status = 'inactive';
                    $purchase->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->logMessage("Erro ao processar purchase {$purchase->id}: {$e->getMessage()}", 'error', $logFileName);
                continue;
            }
        }

        $this->info('Commissions updated successfully!');
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
