<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BitFlowWebhookController extends Controller
{
    /**
     * Webhook Pix In (Pagamento Recebido)
     */
    public function pixIn(Request $request)
    {
        Log::info('BitFlow Webhook Pix In:', $request->all());

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event !== 'cashin.status_changed' || ($data['status'] ?? '') !== 'PAID') {
            return response()->json(['message' => 'Ignorado'], 200);
        }

        $transactionId = $data['providerTransactionId'];
        $externalRef = $data['externalReference'];

        $deposit = Deposit::where('transaction_id', $transactionId)
            ->orWhere('transaction_id', $externalRef)
            ->where('status', 'pending')
            ->first();

        if (!$deposit) {
            Log::warning('BitFlow Webhook: Depósito não encontrado ou já processado.', ['ref' => $externalRef]);
            return response()->json(['message' => 'Não encontrado'], 404);
        }

        DB::beginTransaction();
        try {
            $deposit->status = 'approved';
            $deposit->webhook_data = json_encode($request->all());
            $deposit->save();

            $user = $deposit->user;
            $user->increment('balance', $deposit->amount);

            // Ledger
            UserLedger::create([
                'user_id' => $user->id,
                'reason' => 'deposit',
                'perticulation' => UserLedger::generatePerticulation('deposit', $deposit->amount),
                'amount' => $deposit->amount,
                'credit' => $deposit->amount,
                'status' => 'approved'
            ]);

            // Comissões (Nível 1 a 3)
            $this->processCommissions($user, $deposit->amount);

            DB::commit();
            return response()->json(['message' => 'Sucesso'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao processar BitFlow Webhook Pix In: ' . $e->getMessage());
            return response()->json(['message' => 'Erro'], 500);
        }
    }

    /**
     * Webhook Pix Out (Pagamento Enviado)
     */
    public function pixOut(Request $request)
    {
        Log::info('BitFlow Webhook Pix Out:', $request->all());

        $event = $request->input('event');
        $data = $request->input('data');

        if ($event !== 'cashout.status_changed') {
            return response()->json(['message' => 'Ignorado'], 200);
        }

        $transactionId = $data['providerTransactionId'];
        $status = $data['status'];

        $withdrawal = Withdrawal::where('transaction_id', $transactionId)->first();

        if (!$withdrawal) {
            Log::warning('BitFlow Webhook: Saque não encontrado.', ['tid' => $transactionId]);
            return response()->json(['message' => 'Não encontrado'], 404);
        }

        if ($status === 'COMPLETED') {
            $withdrawal->status = 'approved';
        } elseif (in_array($status, ['REJECTED', 'FAILED', 'CANCELLED'])) {
            // Estorno de saldo se falhar
            if ($withdrawal->status !== 'rejected') {
                $user = $withdrawal->user;
                $user->increment('balance', $withdrawal->amount);
                $withdrawal->status = 'rejected';
            }
        }

        $withdrawal->save();
        return response()->json(['message' => 'Sucesso'], 200);
    }

    private function processCommissions(User $user, float $amount)
    {
        $referrer = $user->referrer;
        $level = 1;
        while ($referrer && $level <= 3) {
            $this->payUserReferral($referrer, $amount, $level, $user->id);
            $referrer = $referrer->referrer;
            $level++;
        }
    }

    private function payUserReferral($user, $amount, $level, $userGetBalance)
    {
        $levels = \App\Models\Rebate::first();
        if (!$levels) return;

        $percent = match ($level) {
            1 => $levels->first_level_percentage ?? 0,
            2 => $levels->second_level_percentage ?? 0,
            3 => $levels->third_level_percentage ?? 0,
            default => 0,
        };

        $payamount = ($amount * $percent) / 100;
        if ($payamount <= 0) return;

        $user->increment('balance', $payamount);

        $levelTexts = [1 => 'first', 2 => 'second', 3 => 'third'];

        UserLedger::create([
            'user_id' => $user->id,
            'reason' => 'referral_commission_level_' . $level,
            'perticulation' => UserLedger::generatePerticulation('referral_commission', $payamount, $level),
            'amount' => $payamount,
            'credit' => $payamount,
            'status' => 'approved',
            'step' => $levelTexts[$level] ?? 'unknown',
            'get_balance_from_user_id' => $userGetBalance,
        ]);
    }
}
