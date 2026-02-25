<?php

namespace App\Http\Controllers;

use App\Enums\TransactionStatus;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Services\DepositService;
use App\Services\PosseidonPay\PosseidonPayException;
use App\Services\PosseidonPay\PosseidonPayService;
use App\Services\ValorionPay\ValorionPayException;
use App\Services\ValorionPay\ValorionPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhooksController extends Controller
{
    public function __construct(
        private PosseidonPayService $posseidonPay,
        private DepositService $depositService,
        private ValorionPayService $valorionPayService
    ) {}

    /**
     * Processa webhooks de depósito e saque da VizionPay.
     *
     * @param Request $request
     * @param string $type O tipo de webhook (ex: 'payment', 'deposit', etc.).
     * @return \Illuminate\Http\JsonResponse
     */
    public function posseidonPayWebhook(Request $request, $type)
    {
        try {
            $webhookData = $request->all();

            Log::channel('webhook')->info("[VIZIONPAY] -> Webhook Recebido:", [
                'type' => $type,
                'response' => json_encode($webhookData, JSON_PRETTY_PRINT)
            ]);

            // Utiliza um switch para tratar os diferentes tipos de webhooks de forma mais organizada
            switch ($webhookData['event']) {
                case 'TRANSFER_COMPLETED':
                    // Processa o webhook de saque aprovado
                    return $this->processTransferCompleted($webhookData);

                case 'TRANSFER_FAILED':
                    // Processa o webhook de saque falhado
                    return $this->processTransferFailed($webhookData);

                case 'TRANSACTION_PAID':
                    // Processa o webhook de depósito pago
                    return $this->processTransactionPaid($webhookData);
                case 'TRANSACTION_CREATED':
                    // Processa o webhook de depósito pago
                    return response()->json(['message' => 'Transação criada'], 200);
                default:
                    return response()->json(['message' => 'Evento de webhook não reconhecido.'], 400);
            }

            // Retorna um erro caso o evento não seja reconhecido
            return response()->json([
                'message' => 'Evento de webhook não reconhecido.'
            ], 400);
        } catch (PosseidonPayException $s) {
            Log::error("Erro VizzionPay ao processar webhook: " . $s->getMessage());
            return response()->json(['message' => 'Erro interno ao processar webhook.'], 500);
        } catch (\Exception $e) {
            Log::error("Erro geral webhook: " . $e->getTraceAsString());
            Log::error("Erro geral ao processar webhook: " . $e->getMessage());
            return response()->json(['message' => 'Erro interno ao processar webhook.'], 500);
        }
    }

    /**
     * Processa webhooks de depósito e saque da PixUp.
     *
     * @param Request $request
     * @param string $type O tipo de webhook (ex: 'payment', 'deposit', etc.).
     * @return \Illuminate\Http\JsonResponse
     */
    public function pixupWebhookDeposit(Request $request)
    {
        try {
            $webhookData = $request->all();

            Log::info("[VALORIONPAY] -> Webhook Recebido:", [
                'response' => json_encode($webhookData, JSON_PRETTY_PRINT)
            ]);

            return $this->processTransactionPaid($webhookData);

            // Retorna um erro caso o evento não seja reconhecido
            return response()->json([
                'message' => 'Evento de webhook não reconhecido.'
            ], 400);
        } catch (ValorionPayException $s) {
            Log::error("Erro ValorionPay ao processar webhook: " . $s->getMessage());
            return response()->json(['message' => 'Erro interno ao processar webhook.'], 500);
        } catch (\Exception $e) {
            Log::error("Erro geral webhook: " . $e->getTraceAsString());
            Log::error("Erro geral ao processar webhook: " . $e->getMessage());
            return response()->json(['message' => 'Erro interno ao processar webhook.'], 500);
        }
    }

    /**
     * Processa a conclusão de um saque.
     *
     * @param array $webhookData
     * @return \Illuminate\Http\JsonResponse
     */
    private function processTransferCompleted(array $webhookData)
    {
        $transactionData = $this->posseidonPay->processCashOutWebhook($webhookData);

        // Use 'first()' para obter um único modelo, não 'whereIn'
        $withdraw = Withdrawal::where('status', TransactionStatus::PROCESSING)
            ->where('transaction_id', $transactionData['transaction_id'])
            ->first();

        // O erro estava aqui: 'if ($withdraw)' retornaria true se o saque fosse encontrado.
        // O correto é 'if (!$withdraw)' para verificar se o saque não foi encontrado.
        if (!$withdraw) {
            Log::channel('webhook')->warning("[POSSEIDONPAY] -> Saque não identificado: " . $transactionData['transaction_id']);
            return response()->json([
                'message' => 'Saque não identificado'
            ], 404); // Use 404 para "não encontrado"
        }

        $withdraw->status = TransactionStatus::APPROVED;
        $withdraw->save();

        return response()->json([
            'success' => true,
            'message' => 'Saque processado com sucesso'
        ], 200);
    }

    /**
     * Processa a falha de um saque.
     *
     * @param array $webhookData
     * @return \Illuminate\Http\JsonResponse
     */
    private function processTransferFailed(array $webhookData)
    {
        $transactionData = $webhookData['data'];

        // 1. Defina os status que você quer buscar em um array
        $status_para_buscar = [
            TransactionStatus::PROCESSING,
            TransactionStatus::PENDING,
        ];

        $withdraw = Withdrawal::whereIn('status', $status_para_buscar)
            ->where('transaction_id', $transactionData['id'])
            ->first();

        if (!$withdraw) {
            Log::channel('webhook')->warning("[POSSIDONPAY] -> Saque não identificado: " . $transactionData['id']);
            return response()->json([
                'message' => 'Saque não identificado'
            ], 404);
        }

        /** @var \App\Models\User $user */
        $user = $withdraw->user;
        if (!$user) {
            return response()->json(['message' => 'Usuario nao encontrado'], 400);
        }
        $user->addBalance($withdraw->amount);
        $withdraw->status = 'rejected';
        $withdraw->save();

        return response()->json([
            'success' => true,
            'message' => 'Saque processado com falha'
        ], 200);
    }

    /**
     * Processa a falha de um saque.
     *
     * @param array $webhookData
     * @return \Illuminate\Http\JsonResponse
     */
    private function processDepositFailed(array $webhookData)
    {
        $transactionData = $webhookData['data'];

        $deposit = Deposit::where('status', TransactionStatus::PENDING)
            ->where('transaction_id', $transactionData['id'])
            ->first();

        if (!$deposit) {
            Log::channel('webhook')->warning("[POSSEIDONPAY] -> Depósito não identificado: " . $transactionData['id']);
            return response()->json([
                'message' => 'Depósito não identificado'
            ], 404);
        }

        /** @var \App\Models\User $user */
        $user = $deposit->user;
        if (!$user) {
            return response()->json(['message' => 'Usuario nao encontrado'], 400);
        }
        $deposit->status = TransactionStatus::CANCELED;
        $deposit->metadata = $webhookData;
        $deposit->save();

        return response()->json([
            'success' => true,
            'message' => 'Depósito processado com falha'
        ], 200);
    }

    /**
     * Processa o pagamento de um depósito.
     *
     * @param array $webhookData
     * @return \Illuminate\Http\JsonResponse
     */
    private function processTransactionPaid(array $webhookData)
    {
        $valorionPayResponse = $this->valorionPayService->processCashInWebhook($webhookData);
        $transactionId = $valorionPayResponse['transaction_id'];

        $deposit = Deposit::where('transaction_id', $transactionId)
            ->where('status', 'pending')
            ->first();

        if (!$deposit) {
            Log::channel('vizion')->error("[TYPE]:WEBHOOK PIXUP -> Deposito não encontrado TRX: " . $transactionId);
            return response()->json([
                'message' => "Depósito não encontrado"
            ], 404);
        }

        DB::beginTransaction();
        try {
            $this->depositService->approveDeposit($deposit);
            DB::commit();

            return response()->json(['success' => true], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Erro durante o processamento do depósito: " . $e->getMessage());
            return response()->json(['message' => 'Erro interno ao processar webhook.'], 500);
        }
    }
}
