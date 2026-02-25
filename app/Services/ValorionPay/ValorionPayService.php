<?php

namespace App\Services\ValorionPay;

use App\Enums\TransactionStatus;
use App\Models\Deposit;
use App\Services\DepositService;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class ValorionPayService
{
    private $client;
    private $apiUrl;
    private const VALID_COMPLETED_STATUS = [
        'PAID_OUT',
        'COMPLETED',
        'PAID'
    ];
    private const VALID_API_SUCCESS_STATUS = [
        'PAGO',
        'APROVADO',
        'PAGAMENTO_APROVADO',
        'COMPLETED'
    ];

    public function __construct(
        string $apiUrl = 'https://api-fila-cash-in-out.onrender.com/',
        string $apiKey = '',
        string $externalreference = ''
    ) {
        $this->apiUrl = $apiUrl;
        $this->apiKey = base64_encode($apiKey);
        $this->externalreference = $externalreference;

        // Inicializa o cliente Guzzle com configurações base
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);
    }

    /**
     * Gera o token com as credenciais
     * @return string
     */
    private function generateToken(string $pix_key = null): string
    {
        try {
            $api_key = env('VALORIONPAY_API_KEY');

            if ($pix_key) {

                $response = $this->makeRequest('POST', '/v2/pix/transaction/auth', [], [
                    'x-api-key' => $api_key,
                    'X-Pix-Key' => $pix_key
                ]);

                if (!($response['token'] ?? null)) {
                    Log::error('Erro ao gerar token: ' . json_encode($response));
                    throw new ValorionPayException('Erro ao gerar token: ' . json_encode($response));
                }

                return $response['token'];
            }

            return $api_key;
        } catch (ValorionPayException $e) {
            throw $e;
        }
    }

    /**
     * Realiza uma operação de Cash In (recebimento)
     * 
     * @param array{
     *     value_cents: float,
     *     generator_name: string,
     *     generator_document?: string,
     *     expiration_time?: int,
     *     external_reference?: string
     * } $payload Payload da transação
     * @return array{
     *      success: boolean,
     *      response: string,
     *      data: array{
     *          status: boolean,
     *          paymentCode: string,
     *          idTransaction: string,
     *          paymentCodeBase64: string,
     *          externalRefference: string
     *      }
     * } Resposta do servidor Status do pagamento, Pix copia e cola, Identificador da transação, QRcode de pagamento, URL de callback e Referencia de sistema
     * @throws ValorionPayException
     */
    public function cashIn(array $payload): array
    {

        try {
            $ip = Request::ip();
            $depositService = new DepositService();
            $token = $this->generateToken();
            $phone = $depositService->generateDataDeposits()['telefone'];

            $data = [
                'amount' => $payload['value_cents'],
                'postbackUrl' => route('valorion.webhook'),
                'ip' => $ip,
                'customer' => [
                    'name' => $payload['generator_name'],
                    'email' => 'suport@valorion.com.br',
                    'cpf' => $payload['generator_document'],
                    'phone' => $phone
                ],
                'items' => [
                    [
                        'title' => 'Depósito via Pix',
                        'quantity' => 1,
                        'unitPrice' => $payload['value_cents'],
                        'tangible' => false
                    ]
                ],
                'metadata' => json_encode([
                    'external_reference' => $payload['external_reference'] ?? $this->externalreference,
                    'generator_name' => $payload['generator_name'],
                    'generator_document' => $payload['generator_document']
                ]),
                'traceable' => false
            ];

            $response = $this->makeRequest('POST', '/v2/pix/charge', $data, [
                'x-api-key' => $token
            ]);

            $paymentCode = $response['paymentCode'] ?? null;
            $transactionId = $response['idTransaction'] ?? null;

            if (!$paymentCode || !$transactionId) {
                if ($response['message']) {
                    throw new ValorionPayException('Erro ao gerar transação: ' . $response['message']);
                }

                throw new ValorionPayException('Erro ao gerar transação: ' . json_encode($response));
            }

            LOG::info("[TYPE]:DEPOSIT ValorionPay -> Depósito gerado com sucesso: ", $response);

            return [
                'success' => true,
                'response' => json_encode($response),
                'data' => [
                    'status' => true,
                    'paymentCode' => $paymentCode,
                    'idTransaction' => $transactionId,
                    'paymentCodeBase64' => $paymentCode,
                    'externalRefference' => $transactionId
                ]
            ];
        } catch (ValorionPayException $s) {
            Log::error("[TYPE]:[ValorionPay ERROR] -> " . $s->getMessage());
            throw $s;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Processa o webhook de Cash In
     * 
     * @param array $webhookData Dados recebidos no webhook
     * @return array{
     *      success: boolean,
     *      message: string,
     *      transaction_id: string,
     *      status: string
     * } Status do processamento
     * @throws ValorionPayException
     */
    public function processCashInWebhook(array $webhookData): array
    {
        try {
            \Log::info('Recebido webhook para processar:', $webhookData);



            $messages = $webhookData['status'] ?? null;

            if (!$messages) {
                throw new ValorionPayException('Informações do webhook não encontrada');
            }
            // Processar status da transação
            $transactionStatus = $webhookData['status'] ?? null;
            $transactionId = $webhookData['idtransaction'] ?? null;

            if (!$transactionId) {
                throw new ValorionPayException('ID da transação não encontrado no webhook');
            }

            $deposit = Deposit::where('transaction_id', $transactionId)->where('status', TransactionStatus::PENDING)->first();

            if (!$deposit) {
                throw new ValorionPayException('Deposito não encontrado');
            }
            // Validar assinatura do webhook
            $this->validateWebhookSignature($webhookData, $deposit);

            return [
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'transaction_id' => $transactionId,
                'status' => $transactionStatus
            ];
        } catch (Exception $e) {
            \Log::error('Erro ao processar webhook:', [
                'webhookData' => $webhookData,
                'error' => $e->getMessage()
            ]);

            throw new ValorionPayException('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    /**
     * Realiza uma operação de Cash Out (pagamento)
     * 
     * @param array{
     *     amount: float,
     *     pix_type: string,
     *     pix_key: string,
     *     name: string,
     *     document: string,
     *     description?: string
     * } $payloadParams
     * 
     * @return array{
     *     success: boolean,
     *     data: array{
     *         amount: float,
     *         pixKey: string, 
     *         pixType: string, 
     *         beneficiaryName: string,
     *         beneficiaryDocument: string, 
     *         postbackUrl: string, 
     *         externalreference: string, 
     *         status: string,
     *         valor_liquido: float, 
     *         idTransaction: string
     *     }
     * }
     * 
     * @throws ValorionPayException
     */
    public function cashOut($payloadParams): array
    {
        $datePart = now()->format('Ymd');
        $randomPart = strtoupper(Str::random(8));
        $pixtype = $payloadParams['pix_type'];

        if ($pixtype === 'CPF') {
            $payloadParams['pix_key'] = preg_replace('/\D/', '', $payloadParams['pix_key']);
        }

        $payload = [
            'amount' => (float) $payloadParams['amount'],
            'pixKey' => $payloadParams['pix_key'],
            'pixType' => $payloadParams['pix_type'],
            'beneficiaryName' => $payloadParams['name'],
            'beneficiaryDocument' => $payloadParams['document'],
            'postbackUrl' => route('valorion.webhook'),
        ];

        Log::info("[TYPE]WITHDRAWN ValorionPay -> Iniciando processo de saque", $payload);

        try {
            $token = $this->generateToken($payloadParams['pix_key']);
            $apiKey = $this->generateToken();

            if (!$token) {

                \Log::error('[TYPE]WITHDRAWN ValorionPay -> Erro ao gerar token:', [
                    'apikey' => $token,
                ]);
                throw new ValorionPayException('[TYPE]WITHDRAWN ValorionPay -> Erro ao gerar token: ' . json_encode($token));
            }

            $response = $this->makeRequest('POST', '/v2/pix/transaction/create', $payload, [
                'x-api-key' => $apiKey,
                'X-Pix-Key' => $payloadParams['pix_key'],
                'Authorization' => 'Bearer ' . $token
            ]);

            $idTransaction = $response['idTransaction'] ?? null;

            if (!$idTransaction) {
                Log::error('[TYPE]WITHDRAWN ValorionPay -> Dados recebidos inválidos:', $response);
                throw new ValorionPayException('[TYPE]WITHDRAWN ValorionPay -> Dados recebidos inválidos: ' . json_encode($response));
            }

            Log::info('[TYPE]WITHDRAWN ValorionPay -> Processando saque:', $response);


            if (empty($response['message'])) {
                Log::error('[TYPE]WITHDRAWN ValorionPay -> Status ausente:', $response);
                throw new ValorionPayException('Erro ao processar saque: ' . json_encode($response));
            }

            return [
                'success' => true,
                'data' => [
                    'amount' => $payloadParams['amount'],
                    'pixKey' => $payloadParams['pix_key'],
                    'pixType' => $payloadParams['pix_type'],
                    'beneficiaryName' => $payloadParams['name'],
                    'beneficiaryDocument' => $payloadParams['document'],
                    'externalreference' => "TX-{$datePart}-{$randomPart}",
                    'valor_liquido' => $payloadParams['amount'],
                    'idTransaction' => "TX-{$datePart}-{$randomPart}",
                    'status' => 'COMPLETE'
                ]
            ];
        } catch (ValorionPayException $e) {
            \Log::error('[TYPE]WITHDRAWN ValorionPay -> Erro ao processar saque:', [
                'webhookData' => $e->getLine(),
                'error' => $e->getMessage()
            ]);

            throw new ValorionPayException('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    /**
     * Processa o webhook de Cash Out
     * 
     * @param array $webhookData Dados recebidos no webhook
     * @return array{
     *      success: boolean,
     *      message: string,
     *      transaction_id: string,
     *      status: string
     * } Status do processamento
     * @throws ValorionPayException
     */
    public function processCashOutWebhook(array $webhookData): array
    {
        try {


            // Processar status da transação
            $transactionStatus = $webhookData['status'] ?? null;
            $transactionId = $webhookData['idtransaction'] ?? null;

            if (!$transactionId || $transactionStatus) {
                Log::error("[TYPE]:DEPOSIT ValorionPay -> Erro ao validar informaçoes", $webhookData);
                throw new ValorionPayException('ID da transação não encontrado no webhook');
            }

            $apiResponse = $this->getTransaction($transactionId);


            if (!in_array($transactionStatus, self::VALID_COMPLETED_STATUS) || !in_array($apiResponse['situacao'], self::VALID_COMPLETED_STATUS)) {
                throw new ValorionPayException('Webhook inválido');
            }



            return [
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'transaction_id' => $transactionId,
                'status' => $transactionStatus
            ];
        } catch (Exception $e) {
            throw new ValorionPayException('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    /**
     * Realiza requisições para a API usando Guzzle
     * 
     * @param string $method Método HTTP
     * @param string $endpoint Endpoint da API
     * @param array $data Dados da requisição
     * @param ?array $headers
     * @return array Resposta da API
     * @throws ValorionPayException
     */
    private function makeRequest(string $method, string $endpoint, array $data = [], $headers = []): array
    {
        $requestId = uniqid('req_');
        try {
            $mergeHeaders = array_merge($this->client->getConfig('headers'), $headers);

            // LOG DE ENVIO
            Log::info("[$requestId] Enviando Requisição API", [
                'method'   => $method,
                'endpoint' => $endpoint,
                'payload'  => $data,
                'headers' => $mergeHeaders // Opcional: logar headers se necessário
            ]);
            $response = $this->client->request($method, $endpoint, [
                'json' => $data,
                'headers' => $mergeHeaders,
                'curl'    => [CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4]
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            // Erros 4xx
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            Log::error('Erro na requisição: ' . json_encode($e->getMessage()));
            throw new ValorionPayException(
                'Erro do cliente: ' . ($responseBody['message'] ?? $e->getMessage()) . 'Payload: ' . json_encode($data) . 'Response: ' . json_encode($responseBody),
                $e->getCode()
            );
        } catch (ServerException $e) {
            // Erros 5xx
            throw new ValorionPayException(
                'Erro do servidor: ' . $e->getMessage() . 'Payload: ' . json_encode($data),
                $e->getCode()
            );
        } catch (RequestException $e) {
            // Outros erros de rede
            throw new ValorionPayException(
                'Erro na requisição: ' . $e->getMessage(),
                $e->getCode()
            );
        }
    }

    /**
     * Get transaction details
     * 
     * @param string $transactionId
     * @return array
     * @throws ValorionPayException
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            $token = $this->generateToken();
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $token
            ])->get('https://api.ValorionPay.pro/s1/getTransaction/api/getTransactionStatus.php?id_transaction=' . $transactionId);

            if (!$response->successful()) {
                throw new ValorionPayException(
                    'API request failed: ' . ($response->json('message') ?? 'Unknown error'),
                    $response->status()
                );
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Failed to fetch transaction', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);
            throw new ValorionPayException('Failed to fetch transaction details: ' . $e->getMessage());
        }
    }

    /**
     * Valida a assinatura do webhook
     * 
     * @param array{
     *      id: int,
     *      user_id: string,
     *      externalreference?: string,
     *      amount: float,
     *      client_name: string,
     *      client_document: string,
     *      client_email: string,
     *      data_registro: string,
     *      adquirente_ref: string,
     *      status: string,
     *      idtransaction: string,
     *      paymentcode: string,
     *      paymentCodeBase64: string,
     *      taxa_deposito: string,
     *      taxa_adquirente: string,
     *      deposito_liquido: string
     * } $webhookData Dados do webhook
     * @throws ValorionPayException
     */
    private function validateWebhookSignature(array $webhookData, Deposit $deposit): void
    {



        $receivedSignature = $webhookData['status'] ?? null; // externalreference

        if (!$receivedSignature) {
            throw new ValorionPayException('Status da transação não encontrada.');
        }

        if ($receivedSignature !== 'PAID_OUT') {
            throw new ValorionPayException('Status do webhook inválido.');
        }

        if (!in_array($receivedSignature, self::VALID_COMPLETED_STATUS)) {
            throw new ValorionPayException('Validação de status reprovada.');
        }

        if (!$this->validateTransactionAmount($deposit->amount, (float) $webhookData['amount'])) {
            throw new ValorionPayException('Valores não condizem.');
        }
    }

    private function validateTransactionAmount(float $expectedAmount, float $actualAmount): bool
    {
        return abs($expectedAmount - $actualAmount) < 0.01;
    }
}

/**
 * Exceção customizada para erros do ValorionPay
 */
class ValorionPayException extends \Exception
{
    // Você pode adicionar métodos específicos para tratamento de erros aqui
}
