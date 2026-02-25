<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;

class AtivoPay
{
    private $client;

    public function __construct(string $externalreference = '')
    {
        $this->apiUrl = rtrim('https://api.conta.ativopay.com/');
        $this->secretKey = '';
        $this->apiSecret = '';
        $this->apiKey = base64_encode($this->apiSecret . ':' . $this->secretKey);
        $this->externalreference = $externalreference;

        // Inicializa o cliente Guzzle com configurações base
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 30,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'authorization' => 'Basic ' . $this->apiKey,
            ]
        ]);
    }

    /**
     * Realiza uma operação de Cash In (recebimento)
     * 
     * @param array{
     *     value_cents: floar,
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
     * @throws AtivoPayException
     */
    public function cashIn(array $payload): array
    {
        try {
            Log::debug('Iniciando processo de cashIn AtivoPay', ['payload' => $payload]);

            $ip = Request::ip();
            Log::debug('IP detectado', ['ip' => $ip]);

            $token = $this->generateToken();
            Log::debug('Token gerado com sucesso');

            $data = [
                'amount' => $payload['value_cents'],
                'postbackUrl' => route('apiPayment'),
                'paymentMethod' => 'pix',
                'installments' => 0,
                'ip' => $ip,
                'splits' => [
                    'recipientId' => env('ATIVOPAY_RECIPIENT_ID'),
                    'amount' => $payload['value_cents'],
                    'chargeProcessingFee' => true,
                ],
                'items' => [
                    'title' => 'Cash In',
                    'unitPrice' => $payload['value_cents'],
                    'quantity' => 1,
                ],
                'pix' => [
                    'expiresInDays' => 1
                ],
                'customer' => [
                    'name' => $payload['generator_name'],
                    'email' => 'suport@syncpay.com',
                ]
            ];

            Log::debug('Dados da requisição montados', ['data' => $data]);

            $response = $this->makeRequest('POST', '/v1/gateway/api/', $data, [
                'Authorization' => 'Basic ' . $token
            ]);

            Log::debug('Resposta da API recebida', ['response' => $response]);

            if (!isset($response['paymentCode'])) {
                Log::error('paymentCode não encontrado na resposta da API', ['response' => $response]);
                throw new AtivoPayException('Erro ao gerar transação');
            }

            $resultado = [
                'success' => true,
                'response' => json_encode($response),
                'data' => [
                    'status' => true,
                    'paymentCode' => $response['paymentCode'] ?? null,
                    'idTransaction' => $response['idTransaction'] ?? null,
                    'paymentCodeBase64' => $response['paymentCodeBase64'] ?? null,
                    'externalRefference' => $response['idTransaction'] ?? null
                ]
            ];

            Log::debug('cashIn finalizado com sucesso', ['resultado' => $resultado]);

            return $resultado;
        } catch (AtivoPayException $e) {
            Log::error('Erro no cashIn: ' . $e->getMessage(), ['exception' => $e]);
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
     * @throws AtivoPayException
     */
    public function processCashInWebhook(array $webhookData): array
    {
        try {
            \Log::info('Recebido webhook para processar:', $webhookData);
            // Validar assinatura do webhook
            $this->validateWebhookSignature($webhookData);

            $messages = $webhookData['status'] ?? null;

            if (!$messages) {
                throw new AtivoPayException('Informações do webhook não encontrada');
            }
            // Processar status da transação
            $transactionStatus = $webhookData['status'] ?? null;
            $transactionId = $webhookData['idtransaction'] ?? null;

            if (!$transactionId) {
                throw new AtivoPayException('ID da transação não encontrado no webhook');
            }

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

            throw new AtivoPayException('Erro ao processar webhook: ' . $e->getMessage());
        }
    }

    /**
     * Realiza uma operação de Cash Out (pagamento)
     * 
     * @param float $amount Valor do pagamento
     * @param string $beneficiaryName Nome do destinatário
     * @param string $beneficiaryDocument Documento do destinatário
     * @param string $pixKey Chave pix
     * @param string $pixType Tipo de chave pix
     * @param string $description Descrição do pagamento
     * @param string $postbackUrl Url de webhook
     * @return array{
     *      success: boolean,
     *      data: array{
     *          amount: float,
     *          pixKey: string, 
     *          pixType: string, 
     *          beneficiaryName: string,
     *          beneficiaryDocument: string, 
     *          postbackUrl: string, 
     *          externalreference: string, 
     *          status: string,
     *          valor_liquido: float, 
     *          idTransaction: string
     *      },
     * } Resposta do servidor
     * @throws AtivoPayException
     */
    public function cashOut(float $amount, string $beneficiaryName, string $beneficiaryDocument, string $pixKey, string $pixType): array
    {
        $payload = [
            'amount' => $amount,
            'pixKey' => $pixKey,
            'pixType' => $pixType,
            'beneficiaryName' => $beneficiaryName,
            'beneficiaryDocument' => $beneficiaryDocument,
            'description' => 'Saque pix',
            'postbackUrl' => env('WEBHOOK_CASHOUT')
        ];

        try {
            $token = $this->generateToken();

            \Log::error('Erro ao processar pagamento:', [
                'apikey' => $token,
            ]);

            $response = $this->makeRequest('POST', '/c1/cashout/api/', $payload, [
                'Authorization' => 'Basic ' . $token
            ]);

            if (!($response['data'] ?? null)) {
                \Log::error('Erro ao processar pagamento:', [
                    'webhookData' => json_encode($response)
                ]);
                throw new AtivoPayException('Erro ao processar saque: ' . json_encode($response));
            }

            $idTransaction = $response['data']['idTransaction'] ?? null;

            \Log::error('Processando saque:', [
                'webhookData' => json_encode($response)
            ]);

            // if (!$idTransaction) {
            //     \Log::error('Undefined idTransaction:', [
            //         'webhookData' => json_encode($response)
            //     ]);
            //     throw new AtivoPayException('Erro ao processar saque: ' . json_encode($response));
            // }

            if (empty($response['data']['status'])) {
                \Log::error('Erro ao processar pagamento:', [
                    'webhookData' => json_encode($response),
                    'error' => $e->getMessage()
                ]);
                throw new AtivoPayException('Erro ao processar saque: ' . json_encode($response));
            }

            return [
                'success' => true,
                'data' => [
                    'amount' => $response['data']['amount'],
                    'pixKey' => $response['data']['pixKey'],
                    'pixType' => $response['data']['pixType'],
                    'beneficiaryName' => $response['data']['beneficiaryName'],
                    'beneficiaryDocument' => $response['data']['beneficiaryDocument'],
                    'externalreference' => $response['data']['externalreference'],
                    'status' => $response['data']['status'],
                    'valor_liquido' => $response['data']['valor_liquido'],
                    'idTransaction' => $idTransaction,
                    'status' => $response['data']['status']
                ]
            ];
        } catch (AtivoPayException $e) {
            \Log::error('Erro ao processar saque:', [
                'webhookData' => $e->getLine(),
                'error' => $e->getMessage()
            ]);

            throw new AtivoPayException('Erro ao processar webhook: ' . $e->getMessage());
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
     * @throws AtivoPayException
     */
    public function processCashOutWebhook(array $webhookData): array
    {
        try {
            // Validar assinatura do webhook
            $this->validateWebhookSignature($webhookData);

            // Processar status da transação
            $transactionStatus = $webhookData['status'] ?? null;
            $transactionId = $webhookData['idtransaction'] ?? null;

            if (!$transactionId) {
                throw new AtivoPayException('ID da transação não encontrado no webhook');
            }

            return [
                'success' => true,
                'message' => 'Webhook processado com sucesso',
                'transaction_id' => $transactionId,
                'status' => $transactionStatus
            ];
        } catch (Exception $e) {
            throw new AtivoPayException('Erro ao processar webhook: ' . $e->getMessage());
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
     * @throws AtivoPayException
     */
    private function makeRequest(string $method, string $endpoint, array $data = [], $headers = []): array
    {
        try {
            $mergeHeaders = array_merge($this->client->getConfig('headers'), $headers);


            $response = $this->client->request($method, $endpoint, [
                'json' => $data,
                'headers' => $mergeHeaders
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (ClientException $e) {
            // Erros 4xx
            $responseBody = json_decode($e->getResponse()->getBody()->getContents(), true);
            throw new AtivoPayException(
                'Erro do cliente: ' . ($responseBody['message'] ?? $e->getMessage()) . 'Payload: ' . json_encode($data),
                $e->getCode()
            );
        } catch (ServerException $e) {
            // Erros 5xx
            throw new AtivoPayException(
                'Erro do servidor: ' . $e->getMessage() . 'Payload: ' . json_encode($data),
                $e->getCode()
            );
        } catch (RequestException $e) {
            // Outros erros de rede
            throw new AtivoPayException(
                'Erro na requisição: ' . $e->getMessage(),
                $e->getCode()
            );
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
     * @throws AtivoPayException
     */
    private function validateWebhookSignature(array $webhookData): void
    {


        $receivedSignature = $webhookData['status'] ?? null; // externalreference

        if (!$receivedSignature) {
            throw new AtivoPayException('Tipo de webhook não assinado.');
        }

        if ($receivedSignature !== 'PAID_OUT') {
            throw new AtivoPayException('Assinatura do webhook não encontrada.');
        }
    }
}

/**
 * Exceção customizada para erros do SyncPay
 */
class AtivoPayException extends \Exception
{
    // Você pode adicionar métodos específicos para tratamento de erros aqui
}
