<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SyncPay;
use App\Http\Requests\DepositRequest;
use App\Models\{Deposit, Package, PaymentMethod, Purchase, Setting, User, UserLedger, Withdrawal, Checkin, Rebate, VipSlider};
use App\Services\ConnectPayService;
use App\Services\FraudDetectionService;
use App\Services\VizionPay\VizionPayException;
use App\Services\VizionPay\VizionPayService;
use App\Services\PosseidonPay\PosseidonPayService;
use App\Services\ValorionPay\ValorionPayService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{

    public function __construct(
        private ConnectPayService $connect,
        private SyncPay $syncpay,
        private VizionPayService $vizionpay,
        private FraudDetectionService $fraudDetectionService,
        private PosseidonPayService $posseidonpay,
        private ValorionPayService $valorionPayService
    ) {}

    /**
     * Obter rendimento da última semana e seu percentual de crescimento
     * 
     * @param int $userId ID do usuário
     * @return array Dados de rendimento e percentual de crescimento
     */
    public function getWeeklyEarnings($userId)
    {
        // Definindo os períodos de tempo
        $today = Carbon::now();
        $startCurrentWeek = Carbon::now()->startOfWeek();
        $endCurrentWeek = Carbon::now();
        $startPreviousWeek = Carbon::now()->subWeek()->startOfWeek();
        $endPreviousWeek = Carbon::now()->subWeek()->endOfWeek();

        // Filtro para considerar apenas entradas de rendimento
        // Adapte o 'reason' conforme a sua lógica de negócios
        $earningsReasons = ['income', 'interest', 'profit', 'dividend'];

        // Consulta para rendimento da última semana
        $currentWeekEarnings = UserLedger::where('user_id', $userId)
            ->whereIn('reason', $earningsReasons)
            ->whereBetween('date', [$startCurrentWeek, $endCurrentWeek])
            ->sum('credit');

        // Consulta para rendimento da semana anterior
        $previousWeekEarnings = UserLedger::where('user_id', $userId)
            ->whereIn('reason', $earningsReasons)
            ->whereBetween('date', [$startPreviousWeek, $endPreviousWeek])
            ->sum('credit');

        // Calcular percentual de crescimento
        $percentageGrowth = 0;
        if ($previousWeekEarnings > 0) {
            $percentageGrowth = (($currentWeekEarnings - $previousWeekEarnings) / $previousWeekEarnings) * 100;
        } elseif ($currentWeekEarnings > 0 && $previousWeekEarnings == 0) {
            $percentageGrowth = 100; // Considerar como crescimento de 100% se antes era zero
        }

        return [
            'current_week_earnings' => $currentWeekEarnings,
            'previous_week_earnings' => $previousWeekEarnings,
            'percentage_growth' => $percentageGrowth,
            'is_positive_growth' => $percentageGrowth >= 0,
            'date_range' => [
                'current_week' => [
                    'start' => $startCurrentWeek->format('Y-m-d'),
                    'end' => $endCurrentWeek->format('Y-m-d'),
                ],
                'previous_week' => [
                    'start' => $startPreviousWeek->format('Y-m-d'),
                    'end' => $endPreviousWeek->format('Y-m-d'),
                ],
            ]
        ];
    }

    private function canUserCheckin($user)
    {
        // Pega o último check-in do usuário e garante que a data seja tratada como Carbon
        $lastCheckin = $user->checkins()->latest('date')->first();

        // Se não houver check-in anterior, o usuário pode fazer o check-in
        if (!$lastCheckin) {
            return true;
        }

        // Converte a data para um objeto Carbon e verifica se é hoje
        $lastCheckinDate = Carbon::parse($lastCheckin->date);

        // Verifica se o último check-in foi hoje
        return !$lastCheckinDate->isToday();
    }

    public function dashboard()
    {
        $packages = Package::where('status', 'active')->where('featured', 0)->get();
        $featuredPackages = Package::where('status', 'active')->where('featured', 1)->get();
        $token = auth()->user()->createToken('api-token')->plainTextToken;
        $vipSlides = VipSlider::all();
        $setting = Setting::first();
        $checkinValue = $setting->checkin ?? 0;
        $user = auth()->user();
        $weeklyEarnings = $this->getWeeklyEarnings($user->id);
        $userCheckin = $this->canUserCheckin($user);

        // Dados reais para o dashboard blue-app
        $walletBalance = $user->balance;

        $dailyEarningsToday = UserLedger::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->whereIn('reason', ['income', 'interest', 'profit', 'dividend', 'commission', 'commission_indication'])
            ->sum('credit');

        $dailyRate = $setting->avg_daily_rate ?? 1.2;

        $investments = Purchase::where('user_id', $user->id)
            ->with('package')
            ->orderByDesc('id')
            ->get();

        return view('blue-app.dashboard', compact(
            'packages',
            'featuredPackages',
            'checkinValue',
            'token',
            'vipSlides',
            'user',
            'weeklyEarnings',
            'userCheckin',
            'walletBalance',
            'dailyEarningsToday',
            'dailyRate',
            'investments'
        ));
    }


    /*public function single_deposit__pay($amount, $channel)
    {
        $channel = PaymentMethod::where('name', $channel)->first();

        return view('app.main.deposit.recharge_confirm', compact('amount', 'channel'));
    }*/
    private function generateRandomName()
    {
        $firstNames = ['Lucas', 'Mateus', 'Julia', 'Mariana', 'Felipe', 'Carla', 'Renan', 'Paula', 'Bruno', 'Ana'];
        $lastNames = ['Silva', 'Santos', 'Oliveira', 'Costa', 'Martins', 'Almeida', 'Ribeiro', 'Lima', 'Barros', 'Moura'];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    private function generateRandomCPF()
    {
        $n = [];
        for ($i = 0; $i < 9; $i++) {
            $n[$i] = rand(0, 9);
        }

        // Digito 1
        $d1 = 0;
        for ($i = 0, $j = 10; $i < 9; $i++, $j--) {
            $d1 += $n[$i] * $j;
        }
        $d1 = 11 - ($d1 % 11);
        $d1 = ($d1 >= 10) ? 0 : $d1;

        // Digito 2
        $d2 = 0;
        for ($i = 0, $j = 11; $i < 9; $i++, $j--) {
            $d2 += $n[$i] * $j;
        }
        $d2 += $d1 * 2;
        $d2 = 11 - ($d2 % 11);
        $d2 = ($d2 >= 10) ? 0 : $d2;

        return implode('', $n) . $d1 . $d2;
    }

    /*public function single_deposit__pay($amount, $channel)
    {
        $user = auth()->user();

        $payload = [
            'value_cents' => $amount,
            'generator_name' => $this->generateRandomName(),
            'generator_document' => $this->generateRandomCPF()
        ];

        $deposit = $this->syncpay->cashIn($payload);

        $paymentCodeBase64 = $deposit['data']['paymentCodeBase64'];
        $paymentCode = $deposit['data']['paymentCode'];

        $model = new Deposit();
        $model->user_id = $user->id;
        $model->method_name = $channel;
        $model->address = 'PrimePag';
        $model->order_id = rand(0, 999999);
        $model->transaction_id = $deposit['data']['idTransaction'];
        $model->amount = $amount;
        $model->date = date('d-m-Y H:i:s');
        $model->status = 'pending';
        $model->save();
        
        $depositId = $model->id;

        $channel = PaymentMethod::where('name', $channel)->first();

        return view('app.main.deposit.recharge_confirm', compact('amount', 'channel', 'paymentCodeBase64', 'paymentCode', 'deposit', 'depositId'));
    }*/

    public function single_deposit__pay($amount, $channel)
    {
        $user = auth()->user();

        Log::info('Iniciando processo de depósito.', [
            'user_id' => $user->id,
            'amount' => $amount,
            'channel' => $channel,
        ]);

        $payload = [
            'value_cents' => $amount,
            'generator_name' => $this->generateRandomName(),
            'generator_document' => $this->generateRandomCPF(),
            'phone' => $user->phone,
        ];

        Log::debug('Payload para cashIn:', $payload);

        try {
            $deposit = $this->valorionPayService->cashIn($payload);

            Log::info('Resposta do cashIn recebida com sucesso.', [
                'response' => $deposit
            ]);

            $paymentCodeBase64 = $deposit['data']['paymentCodeBase64'];
            $paymentCode = $deposit['data']['paymentCode'];

            $model = new Deposit();
            $model->user_id = $user->id;
            $model->method_name = $channel;
            $model->address = 'PrimePag';
            $model->order_id = rand(0, 999999);
            $model->transaction_id = $deposit['data']['idTransaction'];
            $model->amount = $amount;
            $model->date = date('d-m-Y H:i:s');
            $model->status = 'pending';
            $model->save();

            Log::info('Depósito registrado no banco de dados.', [
                'deposit_id' => $model->id,
                'transaction_id' => $model->transaction_id,
            ]);

            $depositId = $model->id;

            $channel = PaymentMethod::where('name', $channel)->first();

            return view('app.main.deposit.recharge_confirm', compact('amount', 'channel', 'paymentCodeBase64', 'paymentCode', 'deposit', 'depositId'));
        } catch (\Exception $e) {
            Log::error('Erro durante o processo de depósito.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Você pode retornar uma view de erro ou redirecionar com mensagem
            return redirect()->back()->with('error', 'Erro ao processar depósito. Tente novamente mais tarde.');
        }
    }

    public function depositStore(DepositRequest $request)
    {
        $user = auth()->user();

        Log::info('Iniciando processo de depósito.', [
            'user_id' => $user->id,
            'amount' => $request->amount,
            'phone' => $user->phone,
            'channel' => 'PIX',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $payload = [
            'value_cents' => $request->amount,
            'generator_name' => $this->generateRandomName(),
            'generator_document' => $this->generateRandomCPF(),
            'phone' => $user->phone,
        ];

        // Verificar limite de tentativas por usuário
        $recentAttempts = Deposit::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinutes(10))
            ->count();

        if ($recentAttempts >= 30) {
            Log::warning('Muitas tentativas de depósito em pouco tempo.', [
                'user_id' => $user->id,
                'attempts' => $recentAttempts,
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Muitas tentativas. Aguarde alguns minutos.',
            ], 429);
        }

        try {

            // $deposit = $this->posseidonpay->cashIn($payload);
            $deposit = $this->valorionPayService->cashIn($payload);



            Log::info('CashIn Response: ', [
                'response' => $deposit
            ]);

            $paymentCodeBase64 = $deposit['data']['paymentCodeBase64'];
            $paymentCode = $deposit['data']['paymentCode'];
            $transactionId = $deposit['data']['idTransaction'];

            $securityHash = hash_hmac(
                'sha256',
                $user->id . '|' . $request->amount . '|' . $transactionId,
                config('app.key')
            );

            $model = new Deposit();
            $model->user_id = $user->id;
            $model->method_name = 'PIX';
            $model->address = 'PIXUP';
            $model->order_id = rand(0, 999999);
            $model->transaction_id = $transactionId;
            $model->amount = $request->amount;
            $model->security_hash = $securityHash;
            $model->webhook_data = $deposit['raw_response'] ?? json_encode($deposit);
            $model->ip_address = request()->ip();
            $model->user_agent = request()->userAgent();
            $model->date = date('d-m-Y H:i:s');
            $model->status = 'pending';
            $model->save();

            Log::info('Depósito registrado no banco de dados.', [
                'deposit_id' => $model->id,
                'transaction_id' => $model->transaction_id,
            ]);

            Log::channel('deposits')->info('Novo depósito criado para usuário: ' . $user->phone, [
                'deposit_id' => $model->id,
                'user_id' => $user->id,
                'amount' => $model->amount,
                'transaction_id' => $model->transaction_id,
                'status' => $model->status,
            ]);

            $depositId = $model->id;


            return response()->json([
                'status' => 'success',
                'message' => 'Depósito criado com sucesso.',
                'data' => [
                    'amount' => $request->amount,
                    'channel' => 'PIX',
                    'paymentCodeBase64' => $paymentCodeBase64,
                    'paymentCode' => $paymentCode,
                    'depositId' => $depositId,
                ]
            ]);
        } catch (VizionPayException $e) {
            Log::error('Erro durante o processo de depósito.', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao processar depósito. Tente novamente mais tarde.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao processar depósito. Tente novamente mais tarde.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getDepositStatus($id)
    {
        $deposit = \App\Models\Deposit::find($id);

        if (!$deposit) {
            return response()->json(['status' => 'not_found'], 404);
        }

        return response()->json(['status' => $deposit->status]);
    }


    /*public function apiPayment(Request $request)
    {
        $model = new Deposit();
        $model->user_id = auth()->id();
        $model->method_name = $request->channel;
        $model->address = $request->address;
        $model->order_id = rand(0,999999);
        $model->transaction_id = $request->transaction;
        $model->amount = $request->amount;
        $model->date = date('d-m-Y H:i:s');
        $model->status = 'pending';
        $model->save();

        return redirect('deposit')->with('success', 'Successful');
    }*/

    public function apiPayment(Request $request)
    {
        Log::debug('Recebido webhook na rota apiPayment');

        $webHookData = $request->all();
        Log::debug('Dados recebidos no webhook', ['webHookData' => $webHookData]);

        if ($webHookData['event'] === 'TRANSACTION_CREATED') {
            Log::debug('Webhook - Transação criada, aguardando pagamento. TRANSACTION_ID: ' . json_encode($webHookData));
            return response()->json(['status' => true, 'message' => 'Transação criada.']);
        }

        $verify = $this->vizionpay->processCashInWebhook($webHookData);
        // $verify = $this->syncpay->processCashInWebhook($webHookData);
        Log::debug('Resultado da verificação do webhook', ['verify' => $verify]);

        if ($verify['success'] === true) {
            Log::debug('Verificação do webhook bem-sucedida');

            $transactionId = $verify['transaction_id'];

            $deposit = Deposit::where('transaction_id', $transactionId)->where('status', 'pending')->first();
            Log::debug('Consulta por depósito', ['transaction_id' => $transactionId, 'deposit' => $deposit]);

            if (!$deposit) {
                Log::warning('Webhook - Depósito não encontrado.', [
                    'transaction_id' => $transactionId,
                ]);

                return response()->json(['error' => 'Deposit not found'], 404);
            }

            if ($deposit) {
                Log::debug('Depósito encontrado', ['deposit_id' => $deposit->id]);

                if ($deposit->status == 'approved') {
                    Log::warning('Transação já aprovada anteriormente', ['deposit_id' => $deposit->id]);

                    return response()->json([
                        'status' => 'fail',
                        'message' => 'Estra transação já foi processada.'
                    ], 400);
                }

                DB::beginTransaction();

                try {
                    $deposit->status = 'approved';
                    $deposit->webhook_data = json_encode($request->all());
                    $deposit->save();
                    Log::debug('Depósito atualizado para aprovado', ['deposit_id' => $deposit->id]);

                    $user = $deposit->user;
                    $oldBalance = $user->balance;

                    $user->increment('balance', $deposit->amount);

                    Log::info('Depósito aprovado - Saldo atualizado.', [
                        'deposit_id' => $deposit->id,
                        'user_id' => $user->id,
                        'old_balance' => $oldBalance,
                        'new_balance' => $user->fresh()->balance,
                        'amount_added' => $deposit->amount,
                    ]);

                    // Ledger
                    UserLedger::create([
                        'user_id'       => $user->id,
                        'reason'        => 'deposit',
                        'perticulation' => UserLedger::generatePerticulation('deposit', $deposit->amount),
                        'amount'        => $deposit->amount,
                        'credit'        => $deposit->amount,
                        'status'        => 'approved'
                    ]);

                    // Ao processar um depósito
                    $referrer = $user->referrer; // Nível 1 (quem indicou o usuário atual)
                    $level = 1;

                    Log::info('Iniciando processo de indicação.');

                    while ($referrer && $level <= 3) {
                        Log::info('Lista de usuário para comissão de indicação', [
                            'level' => $level,
                            'referrer_id' => $referrer->id,
                            'referrer_name' => $referrer->name,
                        ]);

                        $this->payUserReferral($referrer, $deposit->amount, $level, $user->id);

                        // sobe para o próximo nível
                        $referrer = $referrer->referrer;
                        $level++;
                    }

                    DB::commit();

                    return response()->json(['message' => 'Deposit approved'], 200);
                } catch (VizionPayException $e) {
                    DB::rollBack();

                    return response()->json(['error' => 'Processing failed: ' . $e->getMessage()], 500);
                } catch (\Exception $e) {
                    DB::rollBack();

                    Log::error('Erro ao processar webhook de depósito.', [
                        'deposit_id' => $deposit->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);

                    return response()->json(['error' => 'Processing failed'], 500);
                }
            } else {
                Log::error('Depósito não encontrado', ['transaction_id' => $verify['transaction_id']]);

                return response()->json([
                    'status' => 'error',
                    'message' => 'Deposit not found.'
                ], 404);
            }
        } else {
            Log::error('Falha na verificação do webhook', ['verify' => $verify]);

            return response()->json([
                'status' => 'error',
                'message' => 'Webhook verification failed.'
            ], 400);
        }
    }
    public function processIndividualComissions(Request $request, User $user)
    {
        Log::debug('Processando individualmente as comissões de indicação para o usuário', ['user_id' => $user->id]);

        $network = $user->getNetworkAttribute();

        $rebateSettings = Rebate::first();

        foreach ($network as $referral) {
            // Se $referral for Model
            $referralId = $referral->id ?? ($referral['id'] ?? null);
            $nivel      = $referral->nivel ?? ($referral['nivel'] ?? null);
            $refName    = $referral->name ?? ($referral['name'] ?? '');

            if (!$referralId || !$nivel) {
                continue;
            }

            $purchase = Purchase::where('user_id', $referralId)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            if (!$purchase) {
                continue;
            }

            $amountBase = $purchase->amount ?? null;
            if (!$amountBase) {
                Log::warning('Purchase sem valor base para comissão', [
                    'purchase_id' => $purchase->id,
                    'referral_id' => $referralId
                ]);
                continue;
            }

            $percent = match ((int) $nivel) {
                1 => (float) ($rebateSettings->first_level_percentage ?? 0),
                2 => (float) ($rebateSettings->second_level_percentage ?? 0),
                3 => (float) ($rebateSettings->third_level_percentage ?? 0),
                default => 0,
            };

            $commissionAmount = ((float) $amountBase * $percent) / 100;

            Log::debug('Detalhes da comissão calculada', [
                'referral_id' => $referralId,
                'referral_name' => $refName,
                'nivel' => $nivel,
                'percent' => $percent,
                'amount_base' => $amountBase,
                'commission_amount' => $commissionAmount,
            ]);

            if ($commissionAmount > 0) {
                $user->addBalance($commissionAmount);

                $user->ledgers()->create([
                    'reference_type' => 'commission',
                    'get_balance_from_user_id' => $referral->id,
                    'credit' => $commissionAmount,
                    'debit' => 0,
                    'date' => now(),
                    'step' => $nivel,
                    'status' => 'approved',
                    'reason' => "commission_indication",
                    'perticulation' => "Comissão de indicação do nível {$nivel} de " . $referral->name ?? $referral->phone,
                    'amount' => $commissionAmount,
                ]);

                Log::info('Comissão adicionada', [
                    'user_id' => $user->id,
                    'referral_id' => $referralId,
                    'commission_amount' => $commissionAmount,
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Comissões processadas com sucesso.',
        ]);
    }



    public function payUserReferral($user, $amount, $level, $userGetBalance)
    {
        $levels =  Rebate::first();

        if (!$levels) {
            throw new \Exception("Configuração de rebate não encontrada");
        }

        $percent = match ($level) {
            1 => $levels->first_level_percentage ?? 0,
            2 => $levels->second_level_percentage ?? 0,
            3 => $levels->third_level_percentage ?? 0,
            default => 0,
        };

        $payamount = ($amount * $percent) / 100;

        Log::info("Pagando comissão de indicação", [
            'user_id' => $user->id,
            'level' => $level,
            'percent' => $percent,
            'payamount' => $payamount,
        ]);

        if ($payamount <= 0) {
            return;
        }



        DB::transaction(function () use ($user, $payamount, $level, $userGetBalance) {
            $user->increment('balance', $payamount);

            $levels = [
                1 => 'first',
                2 => 'second',
                3 => 'third',
            ];

            $levelText = $levels[$level] ?? 'unknown';

            UserLedger::create([
                'user_id'                  => $user->id,
                'reason'                   => 'referral_commission_level_' . $level,
                'perticulation'            => UserLedger::generatePerticulation('referral_commission', $payamount, $level),
                'amount'                   => $payamount,
                'credit'                   => $payamount,
                'status'                   => 'approved',
                'step'                     => $levelText,
                'get_balance_from_user_id' => $userGetBalance,
            ]);
        });
    }


    public function vip()
    {
        return view('app.main.vip');
    }


    public function description()
    {
        return view('app.main.description');
    }


    public function rating_immediate()
    {
        return view('app.main.rating_immediate');
    }

    public function message()
    {
        return view('app.main.message');
    }

    public function purchase_history()
    {
        $purchases = Purchase::with('package')
            ->where('status', 'active')
            ->where('user_id', auth()->id())
            ->whereHas('package') // só traz se o package existir
            ->orderByDesc('id')
            ->get();
        return view('app.main.purchase_history', compact('purchases'));
    }

    public function historyTransactions($condition = null)
    {
        $user = auth()->user();
        $ledgers = $user->ledgers()->orderBy('id', 'desc')->paginate(20);
        return view('blue-app.transactions', compact('ledgers'));
    }

    public function updatePixAccount(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'realname' => 'required|string|min:3',
            'pix_type' => 'required|in:CPF,EMAIL,PHONE',
            'pix_key' => 'required',
        ]);

        $validator->after(function ($validator) use ($request) {
            $type = $request->pix_type;
            $key = $request->pix_key;

            if ($type === 'CPF') {
                $digits = preg_replace('/\D/', '', $key);
                if (strlen($digits) !== 11) {
                    $validator->errors()->add('pix_key', 'O CPF deve conter 11 números.');
                }
            } elseif ($type === 'EMAIL') {
                if (!filter_var($key, FILTER_VALIDATE_EMAIL)) {
                    $validator->errors()->add('pix_key', 'Informe um e-mail válido.');
                }
            } elseif ($type === 'PHONE') {
                $digits = preg_replace('/\D/', '', $key);
                if (strlen($digits) < 10) {
                    $validator->errors()->add('pix_key', 'Informe um telefone válido com DDD.');
                }
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $user->realname = $request->realname;
        $user->pix_type = $request->pix_type;
        $user->pix_key = $request->pix_key;

        // Se for CPF, também atualizamos o gateway_number que é usado em alguns lugares do sistema
        if ($request->pix_type === 'CPF') {
            $user->gateway_number = preg_replace('/\D/', '', $request->pix_key);
        }

        $user->gateway_method = 'PIX';
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Sua conta de saque PIX foi atualizada!'
        ]);
    }

    public function history($condition = null)
    {
        $user = auth()->user();
        $deposits = $user->deposits()->orderBy('id', 'desc')->get();
        $withdrawals = $user->withdrawals()->orderBy('id', 'desc')->get();
        $commissions = $user->commissions()->orderBy('id', 'desc')->get();
        $ledgers = $user->ledgers()->orderBy('id', 'desc')->get();
        return view('app.main.history', compact('condition', 'deposits', 'withdrawals', 'commissions', 'ledgers'));
    }

    public function history_all()
    {
        return view('app.main.history_all');
    }

    public function ordered()
    {
        $user = auth()->user();
        $packages = Package::whereIn('id', my_active_vips())->where('status', 'active')->get();
        $title = 'Meus investimentos';
        return view('app.main.ordered', compact('user', 'packages', 'title'));
    }

    public function packages()
    {
        $user = auth()->user();
        $packages = Package::where('status', 'active')->where('featured', 0)->get();
        $featuredPackages = Package::where('status', 'active')->where('featured', 1)->get();
        $token = auth()->user()->createToken('token-name')->plainTextToken;
        $setting = Setting::first();
        $checkinValue = $setting->checkin;
        $user = auth()->user();
        $weeklyEarnings = $this->getWeeklyEarnings($user->id);
        $title = 'Pacotes de investimento';
        return view('app.main.packages', compact('user', 'packages', 'title', 'featuredPackages', 'token'));
    }


    public function exchange()
    {
        return view('app.main.exchange');
    }

    public function checkin()
    {
        $user = \auth()->user();
        if ($user->checkin > 0) {
            $checkin = new Checkin();
            $checkin->user_id = $user->id;
            $checkin->date = date('Y-m-d');
            $checkin->amount = $user->checkin;
            $checkin->save();

            $userUpdate = User::where('id', $user->id)->first();
            $userUpdate->balance = $user->balance + $user->checkin;
            $userUpdate->checkin = 0;
            $userUpdate->save();

            $ledger = new UserLedger();
            $ledger->user_id = $user->id;
            $ledger->reason = 'checkin';
            $ledger->perticulation = 'checkin commission received';
            $ledger->amount = $user->checkin;
            $ledger->debit = $user->checkin;
            $ledger->status = 'approved';
            $ledger->step = 'third';
            $ledger->date = date('d-m-Y H:i');
            $ledger->save();

            return response()->json(['message' => 'Check-in balance received.']);
        } else {
            return response()->json(['message' => 'Check-in balance 0']);
        }
    }

    public function vip_commission()
    {
        return view('app.main.vip_commission');
    }


    public function promotion()
    {
        return view('app.main.promotion');
    }

    public function task()
    {
        $user = Auth::user();
        //First Level Users
        $first_level_users = User::where('ref_by', $user->ref_id)->get();
        $first_level_users_ids = [];
        foreach ($first_level_users as $user) {
            array_push($first_level_users_ids, $user->id);
        }

        //Second Level Users
        $second_level_users_ids = [];
        foreach ($first_level_users as $element) {
            $users = User::where('ref_by', $element->ref_id)->get();
            foreach ($users as $user) {
                array_push($second_level_users_ids, $user->id);
            }
        }
        $second_level_users = User::whereIn('id', $second_level_users_ids)->get();

        //Third Level Users
        $third_level_users_ids = [];
        foreach ($second_level_users as $element) {
            $users = User::where('ref_by', $element->ref_id)->get();
            foreach ($users as $user) {
                array_push($third_level_users_ids, $user->id);
            }
        }
        $third_level_users = User::whereIn('id', $third_level_users_ids)->get();
        $team_size = $first_level_users->count() + $second_level_users->count() + $third_level_users->count();

        //Get level wise user ids
        $first_ids = $first_level_users->pluck('id'); //first
        $second_ids = $second_level_users->pluck('id'); //Second
        $third_ids = $third_level_users->pluck('id'); //Third

        $lv1Recharge = Deposit::whereIn('user_id', $first_ids)->where('status', 'approved')->sum('amount');
        $lv2Recharge = Deposit::whereIn('user_id', $second_ids)->where('status', 'approved')->sum('amount');
        $lv3Recharge = Deposit::whereIn('user_id', $third_ids)->where('status', 'approved')->sum('amount');
        $lvTotalDeposit = $lv1Recharge + $lv2Recharge + $lv3Recharge;

        $lv1Withdraw = Withdrawal::whereIn('user_id', $first_ids)->where('status', 'approved')->sum('amount');
        $lv2Withdraw = Withdrawal::whereIn('user_id', $second_ids)->where('status', 'approved')->sum('amount');
        $lv3Withdraw = Withdrawal::whereIn('user_id', $third_ids)->where('status', 'approved')->sum('amount');
        $lvTotalWithdraw = $lv1Withdraw + $lv2Withdraw + $lv3Withdraw;

        $activeMembers1 = Deposit::whereIn('user_id', $first_ids)->where('status', 'approved')->groupBy('user_id')->count();
        $activeMembers2 = Deposit::whereIn('user_id', $second_ids)->where('status', 'approved')->groupBy('user_id')->count();
        $activeMembers3 = Deposit::whereIn('user_id', $third_ids)->where('status', 'approved')->groupBy('user_id')->count();


        $Lv1active = 0;
        $Lv2active = 0;
        $Lv3active = 0;

        foreach ($first_level_users as $uuss) {
            $purchase = Purchase::where('user_id', $uuss->id)->first();
            if ($purchase) {
                $Lv1active = $Lv1active + 1;
            }
        }
        foreach ($second_level_users as $uuss) {
            $purchase = Purchase::where('user_id', $uuss->id)->first();
            if ($purchase) {
                $Lv2active = $Lv2active + 1;
            }
        }
        foreach ($third_level_users as $uuss) {
            $purchase = Purchase::where('user_id', $uuss->id)->first();
            if ($purchase) {
                $Lv3active = $Lv3active + 1;
            }
        }

        $teamTotalActiveMembers = $Lv1active + $Lv2active + $Lv3active;


        return view('app.main.task', compact('team_size', 'teamTotalActiveMembers', 'lv1Recharge', 'lv2Recharge', 'lv3Recharge', 'lv1Withdraw', 'lv2Withdraw', 'lv3Withdraw', 'first_level_users', 'second_level_users', 'third_level_users'));
    }

    public function task_history()
    {
        return view('app.main.task_history');
    }

    public function reword_history()
    {
        return view('app.main.reword_history');
    }

    public function recharge_history()
    {
        return view('app.main.deposit_history');
    }

    public function commission()
    {
        return view('app.main.commission');
    }

    public function amount_history()
    {
        return view('app.main.amount_history');
    }


    public function profile()
    {
        $user = auth()->user();
        $team_size = $user->referrals()->count();
        $referralCounts = $user->getReferralCounts();
        $first_level_users = $referralCounts['level_1'];
        $third_level_users = $referralCounts['level_3'];
        $second_level_users = $referralCounts['level_2'];

        // Stats para o novo perfil
        $totalDeposited = $user->deposits()->where('status', 'approved')->sum('amount');
        $totalWithdrawn = $user->withdrawals()->where('status', 'approved')->sum('amount');
        $todayEarnings = $user->ledgers()->whereDate('created_at', Carbon::today())->where('credit', '>', 0)->sum('credit');

        return view('blue-app.profile', compact(
            'user',
            'team_size',
            'first_level_users',
            'third_level_users',
            'second_level_users',
            'totalDeposited',
            'totalWithdrawn',
            'todayEarnings'
        ));
    }

    public function team()
    {
        return view('app.main.team.index');
    }


    public function setting()
    {
        return view('app.main.mine.setting');
    }

    public function recharge()
    {
        $token = auth()->user()->createToken('deposit-token')->plainTextToken;
        $balance = auth()->user()->balance;
        $minDeposit = Setting::first()->min_deposit;
        return view('blue-app.deposit', compact('token', 'balance', 'minDeposit'));
    }

    public function recharge_amount($amount)
    {
        return view('app.main.deposit.recharge_confirm', compact('amount'));
    }

    public function payment_confirm($amount, $payment_method)
    {
        $payment_method = PaymentMethod::where('name', $payment_method)->inRandomOrder()->first();
        if (!$payment_method) {
            return back()->with('success', 'Method not available.');
        }

        return view('app.main.deposit.payment-confirm', compact('amount', 'payment_method'));
    }

    public function depositSubmit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'acc_acount' => 'required',
            'amount' => 'required',
            'payment_method' => 'required',
            'transaction_id' => 'required',
            'photo' => 'required',
        ]);

        if ($validate->fails()) {
            return back()->withErrors($validate->errors());
        }

        $model = new Deposit();
        $model->user_id = Auth::id();

        $path = uploadImage(false, $request, 'photo', 'upload/payment/', 200, 200, $model->photo);
        $model->photo = $path ?? $model->photo;

        $model->method_name = $request->payment_method;
        $model->method_number = $request->acc_acount;
        $model->order_id = rand(00000, 99999);
        $model->transaction_id = $request->transaction_id;
        $model->amount = $request->amount;
        $model->final_amount = $request->amount;
        $model->date = date('d-m-Y H:i:s');
        $model->status = 'pending';
        $model->save();
        return redirect()->route('user.deposit')->with('success', 'Successful');
    }

    public function update_profile(Request $request)
    {
        $user = User::find(Auth::id());
        $path = uploadImage(false, $request, 'photo', 'upload/profile/', 200, 200, $user->photo);
        $user->photo = $path ?? $user->photo;

        $user->update();
        return redirect()->route('my.profile')->with('success', 'Successful');
    }

    public function personal_details()
    {
        return view('app.main.update_personal_details');
    }

    public function card()
    {
        $methods = PaymentMethod::where('status', 'active')->where('id', '!=', 4)->get();

        return view('app.main.gateway_setup', compact('methods'));
    }

    public function setupGateway(Request $request)
    {
        if ($request->name == '' || $request->gateway_method == '' || $request->gateway_number == '') {
            return redirect()->back()->with('success', 'Please enter correct bank info');
        }


        User::where('id', Auth::id())->update([
            'name' => $request->name,
            'gateway_method' => $request->gateway_method,
            'gateway_number' => $request->gateway_number,
        ]);
        return redirect()->back()->with('success', 'Bank info created.');
    }

    public function invite()
    {
        return view('app.main.invite');
    }

    public function level()
    {
        return view('app.main.level');
    }


    public function service()
    {
        return view('app.main.service');
    }


    public function appreview()
    {
        return view('app.main.appreview');
    }

    public function rule()
    {
        return view('app.main.rule');
    }

    public function partner()
    {
        return view('app.main.partner');
    }

    public function climRecord()
    {
        return view('app.main.climRecord');
    }

    public function add_bank()
    {
        return view('app.main.gateway_setup');
    }

    public function add_bank_create()
    {
        return view('app.main.add_bank_create');
    }

    public function setting_change_password(Request $request)
    {
        //Check current password
        $user = User::find(Auth::id());
        if (Hash::check($request->old_password, $user->password)) {
            if ($request->new_password == $request->confirm_password) {
                $user->password = Hash::make($request->new_password);
                $user->update();
                return redirect()->route('login_password')->with('success', 'Password changed');
            } else {
                return redirect()->route('login_password')->with('success', 'Password not match.');
            }
        } else {
            return redirect()->route('login_password')->with('success', 'Password not match');
        }
    }

    public function confirm_submit(Request $request)
    {
        $data = $request->all();
        $model = new Deposit();
        $model->user_id = $data['ui'];
        $model->method_name = $data['pm'];
        $model->method_number = '01000000000';
        $model->order_id = $data['oid'];
        $model->transaction_id = $data['tid'];
        $model->number = $data['aca'];
        $model->amount = $data['amount'];
        $model->final_amount = $data['amount'];
        $model->usdt = $data['amount'] / setting('rate');
        $model->date = Carbon::now();
        $model->status = 'pending';
        $model->save();
        return response()->json(['status' => true, 'data' => $data]);
    }

    public function download_apk()
    {
        $file = public_path('metamax.apk');
        return response()->file($file, [
            'Content-Type' => 'application/vnd.android.package-archive',
            'Content-Disposition' => 'attachment; filename="metamax.apk"',
        ]);
    }
}
