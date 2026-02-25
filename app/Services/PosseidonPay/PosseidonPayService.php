<?php

namespace App\Services\PosseidonPay;

use App\Models\Gateway;
use App\Models\GatewayCredential;
use Exception;
use Illuminate\Support\Facades\Log;

class PosseidonPayService
{
    private $apiUrl;
    private const VALID_COMPLETED_STATUS = [
        'PAID_OUT',
        'COMPLETED'
    ];
    private const VALID_API_SUCCESS_STATUS = [
        'PAGO',
        'APROVADO',
        'PAGAMENTO_APROVADO',
        'COMPLETED'
    ];

    public function __construct(string $externalreference = '')
    {
        $this->apiUrl = 'https://app.poseidonpay.site/api/v1';
        $this->externalreference = $externalreference;
    }


    public function gerarUuidV4(): string
    {
        $data = random_bytes(16);

        // Ajusta os bits para versão e variante conforme o padrão UUID v4
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // versão 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variante RFC 4122

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    private function normalizarNome(string $nome): string
    {
        // Converte para UTF-8 se necessário
        $nome = mb_convert_encoding($nome, 'UTF-8', 'UTF-8');

        // Remove acentos
        $nome = iconv('UTF-8', 'ASCII//TRANSLIT', $nome);

        // Remove quaisquer caracteres não alfanuméricos adicionais (opcional)
        $nome = preg_replace('/[^A-Za-z0-9\s]/', '', $nome);

        // Remove espaços extras
        $nome = trim(preg_replace('/\s+/', ' ', $nome));

        return $nome;
    }

    private function decodeUnicodeString(string $text): string
    {
        return json_decode('"' . addcslashes($text, '"\\') . '"');
    }

    /**
     * Realiza uma operação de Cash In (recebimento)
     * 
     * @param array{
     *     value_cents: float,
     *     generator_name: string,
     *     generator_document: string,
     *     phone: string,
     *     email?: string,
     *     expiration_time?: int,
     *     external_reference?: string,
     *     callback_url?: string
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
     * } Resposta do servidor
     * @throws PosseidonPayException
     */
    public function cashIn(array $payload): array
    {
        try {

            $identifier = $this->gerarUuidV4();
            $data = [
                'identifier' => $identifier,
                'amount' => $payload['value_cents'],
                'callbackUrl' => $payload['callback_url'] ?? route('posseidonpay.webhook', ['type' => 'deposit']),
                'client' => [
                    'name' => $payload['generator_name'],
                    'email' => $payload['email'] ?? 'suport@vizionpay.com',
                    'phone' => $payload['phone'],
                    'document' => $payload['generator_document']
                ]
            ];
            $public_key = 'brendaappsfc2301_higyp6v42umwuj1w';
            $secret_key = 'z37mm7hlw1nsf7nqt6wzoi1ceojr8pcibxszzdyqeyrjgv8z0mek8n412cfaxmno';



            $response = $this->makeRequest('POST', '/gateway/pix/receive', $data, [
                'x-public-key' => $public_key,
                'x-secret-key' => $secret_key,
            ]);

            $paymentCode = $response['pix'] ? $response['pix']['code'] : null;
            $transactionId = $response['transactionId'] ?? null;

            if (!$paymentCode || !$transactionId) {
                if (isset($response['message'])) {
                    throw new PosseidonPayException('Erro ao gerar transação: ' . $response['message']);
                }

                throw new PosseidonPayException('Erro ao gerar transação: ' . json_encode($response));
            }

            $this->logInfo("[TYPE]:DEPOSIT VIZIONPAY -> Depósito gerado com sucesso: " . json_encode($response));

            return [
                'success' => true,
                'response' => json_encode($response),
                'data' => [
                    'status' => true,
                    'paymentCode' => $paymentCode ?? null,
                    'idTransaction' => $transactionId ?? null,
                    'paymentCodeBase64' => $response['pix']['base64'] ?? null,
                    'externalRefference' => $identifier ?? null
                ]
            ];
        } catch (PosseidonPayException $s) {
            $this->logError("[TYPE]:[VIZIONPAY ERROR] -> " . $s->getMessage());
            throw $s;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * Processa o webhook de Cash In
     * 
     * @param array $webhookData Dados recebidos no webhook
     * @param array $depositData Dados do depósito para validação
     * @param callable|null $callback Função de callback para ser executada após o processamento
     * @return array{
     *      success: boolean,
     *      message: string,
     *      transaction_id: string,
     *      amount: float,
     *      status: string
     * } Status do processamento
     * @throws PosseidonPayException
     */
    public function processCashInWebhook(array $webhookData, ?callable $callback = null): array
    {
        try {
            $this->logInfo('Recebido webhook para processar: ' . json_encode($webhookData));

            $event = $webhookData['event'] ?? null;


            if (empty($event)) {
                throw new PosseidonPayException('Evento do webhook não identificado');
            }

            $transaction = $webhookData['transaction'] ?? null;

            if ($transaction['status'] !== 'COMPLETED') {
                throw new PosseidonPayException('Transação não confirmada pelo gateway');
            }

            $messages = $webhookData['transaction'] ?? null;

            if (!$messages) {
                throw new PosseidonPayException('Informações do webhook não encontrada');
            }

            // Processar status da transação
            $transactionStatus = $webhookData['transaction']['status'] ?? null;
            $transactionId = $webhookData['transaction']['id'] ?? null;
            $amount = $webhookData['transaction']['amount'] ?? null;

            $gatewayTransaction = $this->getTransaction($transactionId);

            $gatewayStatus = $gatewayTransaction['status'] ?? null;

            // if (!$gatewayStatus || !in_array($gatewayStatus, self::VALID_API_SUCCESS_STATUS)) {
            //     throw new PosseidonPayException('Status da transação inválido na consulta API');
            // }

            if (!$transactionId) {
                throw new PosseidonPayException('ID da transação não encontrado no webhook');
            }

            if (empty($amount)) {
                throw new PosseidonPayException('Valor da transação não recebido no webhook');
            }

            $result = [
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'status' => $transactionStatus
            ];

            // Executar a função de callback se ela foi fornecida
            if ($callback !== null && is_callable($callback)) {
                $transactionData = [
                    'id' => $transactionId,
                    'status' => $transactionStatus,
                    'amount' => $amount,
                ];

                call_user_func($callback, $result, $transactionData);
            }

            return $result;
        } catch (Exception $e) {
            $this->logError('Erro ao processar webhook: ' . $e->getMessage() . ' - Dados: ' . json_encode($webhookData));
            throw new PosseidonPayException('Erro ao processar webhook: ' . $e->getMessage());
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
     *     ip?: string,
     *     postbackUrl?: string,
     *     externalreference?: string
     * } $payloadParams
     * 
     * @return array{
     *     success: boolean,
     *     message: string,
     *     data: array{
     *         amount: float,
     *         pixKey: string, 
     *         pix_type: 'cpf'|'email'|'phone',
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
     * @throws PosseidonPayException
     */
    public function cashOut($payloadParams): array
    {
        $externalreference = $this->gerarUuidV4();
        $payload = [
            'identifier' => $payloadParams['externalreference'] ?? $externalreference,
            'amount' => (float) $payloadParams['amount'],
            'pix' => [
                'type' => strtolower($payloadParams['pix_type']),
                'key' => $payloadParams['pix_key'],
            ],
            'owner' => [
                'ip' => $payloadParams['ip'],
                'name' => $this->normalizarNome($payloadParams['name']),
                'document' => [
                    'type' => 'cpf',
                    'number' => $payloadParams['document']
                ],
            ],
            'callbackUrl' => route('posseidonpay.webhook', 'payment')
        ];

        $this->logInfo("[TYPE]WITHDRAWN POSSEIDONPAY -> Iniciando processo de saque: " . json_encode($payload));

        try {
            $public_key = 'brendaappsfc2301_higyp6v42umwuj1w';
            $secret_key = 'z37mm7hlw1nsf7nqt6wzoi1ceojr8pcibxszzdyqeyrjgv8z0mek8n412cfaxmno';

            if (!$public_key || !$secret_key) {
                $this->logError('[TYPE]WITHDRAWN POSSEIDONPAY -> Erro ao gerar credenciais: ' . json_encode($token));
                throw new PosseidonPayException('[TYPE]WITHDRAWN POSSEIDONPAY -> Erro ao gerar token: ' . json_encode($token));
            }

            $response = $this->makeRequest('POST', '/gateway/transfers', $payload, [
                'x-public-key' => $public_key,
                'x-secret-key' => $secret_key,
            ]);

            if (!($response['withdraw'] ?? null)) {
                $this->logError('[TYPE]WITHDRAWN POSSEIDONPAY -> Dados recebidos inválidos: ' . json_encode($response));
                throw new PosseidonPayException('[TYPE]WITHDRAWN POSSEIDONPAY -> Dados recebidos inválidos: ' . json_encode($response));
            }

            $withdraw = $response['withdraw'];

            $idTransaction = $withdraw['id'] ?? null;

            $this->logInfo('[TYPE]WITHDRAWN POSSEIDONPAY -> Processando saque: ' . json_encode($response));

            if (empty($withdraw['status']) || empty($idTransaction)) {
                $this->logError('[TYPE]WITHDRAWN POSSEIDONPAY -> Status ou ID ausente: ' . json_encode($response));
                throw new PosseidonPayException('Erro ao processar saque: ' . json_encode($response));
            }

            // Verificar status
            $status = $withdraw['status'];
            $message = "Saque processado com sucesso!";

            if ($status === 'CANCELED') {
                $message = $withdraw['rejectedReason'];
                throw new PosseidonPayException($message);
            }

            return [
                'success' => true,
                'message' => $message,
                'data' => [
                    'amount' => $withdraw['amount'],
                    'pixKey' => $payloadParams['pix_key'],
                    'pixType' => $payloadParams['pix_type'],
                    'beneficiaryName' => $payloadParams['name'],
                    'beneficiaryDocument' => $payloadParams['document'],
                    'externalreference' => $response['webhookToken'] ?? null,
                    'status' => $withdraw['status'],
                    'valor_liquido' => $withdraw['amount'] ? ($withdraw['amount'] - $withdraw['feeAmount']) : null,
                    'idTransaction' => $idTransaction,
                ]
            ];
        } catch (PosseidonPayException $e) {
            $this->logError('[TYPE]WITHDRAWN POSSEIDONPAY -> Erro ao processar saque: ' . $e->getMessage() . ' na linha ' . $e->getLine());
            throw new PosseidonPayException('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    /**
     * Processa o webhook de Cash Out
     * 
     * @param array $webhookData Dados recebidos no webhook
     * @param callable|null $callback Função de callback para ser executada após o processamento
     * @return array{
     *      success: boolean,
     *      message: string,
     *      transaction_id: string,
     *      amount: float,
     *      status: string
     * } Status do processamento
     * @throws PosseidonPayException
     */
    public function processCashOutWebhook(array $webhookData, ?callable $callback = null): array
    {
        try {


            // Processar status da transação
            /** @var array $withdraw */
            $withdraw = $webhookData['withdraw'];
            $transactionStatus = $withdraw['status'] ?? null;
            $transactionId = $withdraw['id'] ?? null;
            $amount = $withdraw['amount'] ?? null;


            if (!$transactionId || !$transactionStatus) {
                $this->logError("[TYPE]:PAYMENT POSSEIDONPAY -> Erro ao validar informações: " . json_encode($webhookData, JSON_PRETTY_PRINT));
                throw new PosseidonPayException('ID da transação não encontrado no webhook');
            }

            if (!in_array($transactionStatus, self::VALID_COMPLETED_STATUS)) {
                throw new PosseidonPayException('Webhook inválido');
            }

            if (empty($amount)) {
                throw new PosseidonPayException('Valor não recebido no webhook - CashOut');
            }

            $this->logInfo("[POSSEIDONPAY WEBHOOK CASHOUT]: Iniciando processo de saque TRANSACTION_ID: {$transactionId}");

            $result = [
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'status' => $transactionStatus
            ];

            // Executar a função de callback se ela foi fornecida
            if ($callback !== null && is_callable($callback)) {
                $transactionData = [
                    'id' => $transactionId,
                    'status' => $transactionStatus,
                    'amount' => $amount,
                ];

                call_user_func($callback, $result, $transactionData);
            }

            return $result;
        } catch (Exception $e) {
            throw new PosseidonPayException('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    // Com callback example
    // $processor->processCashInWebhook($webhookData, $depositData, function($result, $webhookData, $depositData) {
    //     // Realize operações adicionais aqui
    //     // Por exemplo, atualizar o banco de dados, enviar notificação, etc.
    //     echo "Transação {$result['transaction_id']} processada com status: {$result['status']}";
    // });

    /**
     * Realiza requisições para a API usando cURL
     * 
     * @param string $method Método HTTP
     * @param string $endpoint Endpoint da API
     * @param array $data Dados da requisição
     * @param ?array $headers Headers adicionais
     * @param callable|null $callback Função de callback a ser executada após a requisição
     * @return array Resposta da API
     * @throws PosseidonPayException
     */
    private function makeRequest(string $method, string $endpoint, array $data = [], array $headers = [], ?callable $callback = null): array
    {
        $url = $this->apiUrl . $endpoint;

        $curlHeaders = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        foreach ($headers as $key => $value) {
            $curlHeaders[] = "$key: $value";
        }

        $curl = curl_init();

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        ];

        if (!empty($data) && ($method === 'POST' || $method === 'PUT')) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);

        // Capturar informações adicionais para depuração
        $requestInfo = [
            'url' => $url,
            'method' => $method,
            'headers' => $curlHeaders,
            'data' => $data,
            'http_code' => $httpCode,
            'curl_error' => $error,
            'raw_response' => $response
        ];

        $this->logInfo('Request Vizion info: ' . json_encode($requestInfo));

        curl_close($curl);

        if ($error) {
            throw new PosseidonPayException("Erro cURL: $error");
        }

        $decodedResponse = json_decode($response, true);

        // Informações completas da requisição e resposta para o callback
        $requestResult = [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'http_code' => $httpCode,
            'response' => $decodedResponse,
            'raw_response' => $response,
            'request' => [
                'url' => $url,
                'method' => $method,
                'data' => $data,
                'headers' => $curlHeaders
            ]
        ];

        // Executar o callback se fornecido
        if ($callback !== null && is_callable($callback)) {
            call_user_func($callback, $requestResult);
        }

        if ($httpCode >= 400) {
            $responseBody = is_string($response) ? $response : json_encode($decodedResponse);
            $errorMessage = isset($decodedResponse['message'])
                ? $decodedResponse['message']
                : "Erro HTTP $httpCode";

            throw new PosseidonPayException(
                "Erro na requisição: $errorMessage. Payload: " . json_encode([
                    'payload' => $data,
                    'response' => $decodedResponse,
                    'raw_response' => $responseBody
                ]),
                $httpCode
            );
        }

        return $decodedResponse;
    }


    /**
     * Get transaction details
     * 
     * @param string $transactionId
     * @return array
     * @throws PosseidonPayException
     */
    public function getTransaction(string $transactionId): array
    {
        try {
            $public_key = env('POSSEIDONPAY_PUBLIC_KEY');
            $secret_key = env('POSSEIDONPAY_SECRET_KEY');

            $url = '/gateway/transactions?id=' . $transactionId;

            return $this->makeRequest('GET', $url, [], [
                'x-public-key' => $public_key,
                'x-secret-key' => $secret_key,
            ]);
        } catch (\Exception $e) {
            $this->logError('Failed to fetch transaction. Transaction ID: ' . $transactionId . '. Error: ' . $e->getMessage());
            throw new PosseidonPayException('Failed to fetch transaction details: ' . $e->getMessage());
        }
    }

    /**
     * Valida a assinatura do webhook
     * 
     * @param array $webhookData Dados do webhook
     * @param array $depositData Dados do depósito para validação
     * @throws PosseidonPayException
     */
    private function validateWebhookSignature(array $webhookData): void
    {
        $receivedSignature = $webhookData['transaction'] ?? null;

        if (!$receivedSignature) {
            throw new PosseidonPayException('Status da transação não encontrada.');
        }

        if ($receivedSignature['status'] !== 'PAID_OUT') {
            throw new PosseidonPayException('Status do webhook inválido.');
        }

        $apiResponse = $this->getTransaction($webhookData['idtransaction']);

        // $this->logInfo("[VIZIONPAY] Transaction find: " . json_encode($apiResponse));


        if (!in_array($receivedSignature['status'], self::VALID_COMPLETED_STATUS)) {
            throw new PosseidonPayException('Validação de status reprovada.');
        }
    }

    /**
     * Valida se o valor do depósito corresponde ao valor retornado pela API
     * 
     * @param float $expectedAmount
     * @param float $actualAmount
     * @return bool
     */
    private function validateTransactionAmount(float $expectedAmount, float $actualAmount): bool
    {
        return abs($expectedAmount - $actualAmount) < 0.01;
    }

    /**
     * Obtém o IP IPv4 do cliente
     * 
     * @return string
     */
    private function getClientIp(): string
    {
        $ip = '89.116.115.30';

        $candidatos = [
            $_SERVER['HTTP_CLIENT_IP'] ?? null,
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null,
            $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        foreach ($candidatos as $valor) {
            if ($valor) {
                // Se houver múltiplos IPs no header (ex: "1.2.3.4, 5.6.7.8")
                $ips = explode(',', $valor);
                foreach ($ips as $ipPossivel) {
                    $ipPossivel = trim($ipPossivel);
                    if (filter_var($ipPossivel, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        return $ipPossivel;
                    }
                }
            }
        }

        return $ip; // fallback para localhost
    }

    /**
     * Log de informações
     * 
     * @param string $message
     * @return void
     */
    private function logInfo(string $message): void
    {
        $logMessage = '[' . date('Y-m-d H:i:s') . '] INFO: ' . $message . PHP_EOL;
        Log::channel('posseidonpay')->info($logMessage);
    }

    /**
     * Log de erros
     * 
     * @param string $message
     * @return void
     */
    private function logError(string $message): void
    {
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ERROR: ' . $message . PHP_EOL;
        Log::channel('posseidonpay')->error($logMessage);
    }
}

/**
 * Exceção customizada para erros do SyncPay
 */
class PosseidonPayException extends Exception
{
    // Você pode adicionar métodos específicos para tratamento de erros aqui
}

class PosseidonPayWebhook
{
    public function verifyType($type)
    {
        if ($type === 'deposit') {
        }
    }

    public function processDepositPaid(string $transactionId)
    {
        try {
            //code...
        } catch (PosseidonPayException $e) {
            throw $e;
        }
    }
}
