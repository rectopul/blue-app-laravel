<?php

namespace App\Services\BitFlow;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class BitFlowService
{
    private $apiUrl;
    private $clientId;
    private $clientSecret;
    private $publicKey;

    public function __construct()
    {
        $settings = Setting::first();
        $this->apiUrl = config('services.bitflow.api_url', 'https://bitflow-backend.onrender.com'); // TODO: Ajustar URL base
        $this->clientId = $settings->bitflow_client_id;
        $this->clientSecret = $settings->bitflow_client_secret;
        $this->publicKey = $settings->bitflow_public_key;
    }

    /**
     * Get OAuth2 Token
     */
    private function getToken(): string
    {
        $cacheKey = 'bitflow_access_token';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::post("{$this->apiUrl}/auth/oauth2/token", [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ]);

        if (!$response->successful()) {
            Log::error('BitFlow Auth Error: ' . $response->body());
            throw new BitFlowException('Erro de autenticação com BitFlow.');
        }

        $data = $response->json();
        $token = $data['access_token'];

        // Cache token based on expires_in (default 1h)
        Cache::put($cacheKey, $token, ($data['expires_in'] ?? 3600) - 60);

        return $token;
    }

    /**
     * Cash In (Pix In)
     */
    public function cashIn(array $payload): array
    {
        try {
            $token = $this->getToken();

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'X-API-Key' => $this->publicKey,
            ])->post("{$this->apiUrl}/cashin/api", [
                'amountCents' => (int) ($payload['amount'] * 100), // Converte R$ para centavos
                'urlCallBack' => route('bitflow.webhook.pix-in'),
                'customer' => [
                    'name' => $payload['customer_name'],
                    'email' => $payload['customer_email'],
                    'cpf' => $payload['customer_document'],
                    'phone' => $payload['customer_phone'],
                    'externaRef' => $payload['external_reference'],
                ],
                'items' => [
                    [
                        'title' => 'Pagamento',
                        'quantity' => 1,
                        'unitPriceCents' => (int) ($payload['amount'] * 100),
                        'tangible' => false,
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('BitFlow CashIn Error: ' . $response->body());
                throw new BitFlowException('Erro ao gerar Pix In no BitFlow: ' . $response->json('message', 'Erro desconhecido'));
            }

            $data = $response->json();

            // Transform response to match system expectations
            return [
                'success' => true,
                'response' => $response->body(),
                'data' => [
                    'status' => true,
                    'paymentCode' => $data['paymentCode'],
                    'idTransaction' => $data['providerTransactionId'],
                    'paymentCodeBase64' => $data['paymentCode'], // BitFlow doesn't provide base64 in example, use plain code or implement base64 if needed
                    'externalRefference' => $data['providerTransactionId'],
                ]
            ];
        } catch (\Exception $e) {
            Log::error('BitFlow CashIn Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cash Out (Pix Out)
     */
    public function cashOut(array $payload): array
    {
        try {
            $token = $this->getToken();

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$token}",
                'X-API-Key' => $this->publicKey,
            ])->post("{$this->apiUrl}/cashout/api", [
                'amountCents' => (int) ($payload['amount'] * 100),
                'pixKeyType' => $this->mapPixKeyType($payload['pix_type']),
                'urlCallback' => route('bitflow.webhook.pix-out'),
                'pixKey' => $payload['pix_key'],
                'beneficiaryName' => $payload['name'],
                'beneficiaryDocument' => $payload['document'],
                'description' => $payload['description'] ?? 'Saque de saldo',
                'externalReference' => $payload['external_reference'],
                'urlCallback' => route('bitflow.webhook.pix-out'),
            ]);

            if (!$response->successful()) {
                Log::error('BitFlow CashOut Error: ' . $response->body());
                throw new BitFlowException('Erro ao gerar Pix Out no BitFlow: ' . $response->json('message', 'Erro desconhecido'));
            }

            $data = $response->json();

            return [
                'success' => true,
                'data' => [
                    'amount' => $payload['amount'],
                    'pixKey' => $payload['pix_key'],
                    'pixType' => $payload['pix_type'],
                    'beneficiaryName' => $payload['name'],
                    'beneficiaryDocument' => $payload['document'],
                    'externalreference' => $payload['external_reference'],
                    'valor_liquido' => $payload['amount'],
                    'idTransaction' => $data['providerTransactionId'] ?? $data['id'],
                    'status' => $data['status'],
                ]
            ];
        } catch (\Exception $e) {
            Log::error('BitFlow CashOut Exception: ' . $e->getMessage());
            throw $e;
        }
    }

    private function mapPixKeyType($type): string
    {
        return match (strtoupper($type)) {
            'CPF' => 'CPF',
            'CNPJ' => 'CNPJ',
            'EMAIL' => 'EMAIL',
            'PHONE', 'TELEFONE' => 'PHONE',
            'RANDOM', 'EVP' => 'EVP',
            default => 'EVP',
        };
    }
}
