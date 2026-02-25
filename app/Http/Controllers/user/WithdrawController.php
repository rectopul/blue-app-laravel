<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Deposit;
use App\Models\Purchase;
use App\Models\Setting;
use App\Services\FraudDetectionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WithdrawController extends Controller
{
    public function __construct(private FraudDetectionService $fraudDetectionService)
    {
        $this->fraudDetectionService = $fraudDetectionService;
    }

    public function withdraw()
    {
        /*if (user()->gateway_method == null || user()->gateway_number == null) {
            return redirect()->route('user.bank.create')->with('success', 'First create your account.');
        }*/

        // Todos os saques
        $withdrawals = Withdrawal::where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'desc') // Ordena pelos mais recentes
            ->get(['id', 'amount', 'created_at']);

        $saquesCount = $withdrawals->count(); // Total de saques
        $ultimoSaque = $withdrawals->first(); // Último saque (registro mais recente)
        // dd($ultimoSaque);
        $lastSaque = $ultimoSaque->amount ?? 0;
        $setting = Setting::first();

        $user = auth()->user();

        $token = $user->createToken('MeuTokenAPI')->plainTextToken;

        return view('blue-app.withdraw', compact('saquesCount', 'lastSaque', 'setting', 'user', 'token'));
    }

    public function withdraw_history()
    {
        return view('app.main.withdraw_history');
    }

    public function withdrawRequest(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'amount' => 'required|numeric',
        ]);

        if (setting('w_time_status') == 'inactive') {
            return response()->json([
                'success' => false,
                'message' => 'You trying to CashOut illegal timezone.'
            ], 422);
        }

        if ($request->amount == null) {
            return response()->json([
                'success' => false,
                'message' => 'CashOut Amount is Required.'
            ], 422);
        }

        $user = Auth::user();

        if (!$user->pix_type) {
            return response()->json([
                'success' => false,
                'message' => 'Cadastre sua carteira'
            ], 422);
        }

        if (!$user->pix_key) {
            return response()->json([
                'success' => false,
                'message' => 'Cadastre sua carteira'
            ], 422);
        }

        if (!$user->gateway_number) {
            return response()->json([
                'success' => false,
                'message' => 'Cadastre sua carteira'
            ], 422);
        }

        if (!$user->realname) {
            return response()->json([
                'success' => false,
                'message' => 'Cadastre sua carteira'
            ], 422);
        }

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()
            ], 422);
        }

        if ($request->amount <= $user->balance) {
            $userPurchase = Purchase::where('user_id', auth()->user()->id)->count();

            if ($userPurchase === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ative seu primeiro investimento para liberar a opção de saque!'
                ], 422);
            }

            if ($request->amount >= setting('minimum_withdraw')) {
                if ($request->amount <= setting('maximum_withdraw')) {
                    $charge = 1;
                    if (setting('withdraw_charge') > 0) {
                        $charge = ($request->amount * setting('withdraw_charge')) / 100;
                    }

                    //Update User Balance
                    $balance = $user->balance - $request->amount;
                    User::where('id', $user->id)->update([
                        'balance' => $balance,
                    ]);

                    //Withdraw
                    $withdrawal = new Withdrawal();
                    $withdrawal->user_id = $user->id;
                    $withdrawal->method_name = $user->gateway_method;
                    $withdrawal->name = $user->realname;
                    $withdrawal->cpf = $user->gateway_number;
                    $withdrawal->pix_type = $user->pix_type;
                    $withdrawal->pix_key = $user->pix_key;
                    $withdrawal->address = $user->gateway_number;
                    $withdrawal->amount = $request->amount;
                    $withdrawal->charge = $charge;
                    $withdrawal->oid = rand(000000, 999999) . rand(000000, 999999) . rand(000000, 999999);
                    $withdrawal->final_amount = $request->amount - $charge;
                    $withdrawal->status = 'pending';
                    $withdrawal->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Solicitação de saque realizada com sucesso'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'CashOut amount less then ' . setting('maximum_withdraw')
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'CashOut amount greater then ' . setting('minimum_withdraw')
                ], 422);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'CashOut balance insufficient'
            ], 422);
        }
    }

    public function apiWithdraww(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:5',
            'document' => 'required|string|min:11',
            'name' => 'required|string|min:3',
            'pixType' => 'required|string|in:CPF,RANDOM,EMAIL,PHONE',
            'pixKey' => 'required|string',
            'ip' => 'nullable|ipv4',
        ]);


        // if (setting('w_time_status') == 'inactive') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'You trying to CashOut illegal timezone.'
        //     ], 422);
        // }

        $user = Auth::user();


        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()
            ], 422);
        }

        if ($request->amount <= $user->balance) {
            $userPurchase = Purchase::where('user_id', auth()->user()->id)->count();

            if ($userPurchase === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Faça 1 investimento para realizar saques'
                ], 422);
            }

            // 1. VERIFICAÇÃO BÁSICA DE SALDO
            if ($user->balance < $request->amount) {
                return response()->json(['error' => 'Saldo insuficiente'], 400);
            }

            // 2. ANÁLISE DE FRAUDE ANTES DE PROCESSAR
            $analysis = $this->fraudDetectionService->analyzeUser($user);

            // Bloquear se risco muito alto
            // if ($analysis['risk_level'] === 'high') {
            //     return response()->json([
            //         'error' => 'Sua conta foi temporariamente suspensa para verificação de segurança.',
            //         'contact_support' => true,
            //         'risk_level' => $analysis['risk_level']
            //     ], 403);
            // }

            // Aplicar limites baseados no risco
            $limits = config('fraud_detection.withdrawal_limits');
            $userLimit = $limits[$analysis['risk_level']] ?? null;

            // if ($userLimit && $request->amount > $userLimit) {
            //     return response()->json([
            //         'error' => "Limite de saque para seu perfil é R$ {$userLimit}",
            //         'max_amount' => $userLimit,
            //         'risk_level' => $analysis['risk_level']
            //     ], 400);
            // }

            if ($request->amount >= setting('minimum_withdraw')) {
                if ($request->amount <= setting('maximum_withdraw')) {
                    $charge = 1;
                    if (setting('withdraw_charge') > 0) {
                        $charge = ($request->amount * setting('withdraw_charge')) / 100;
                    }

                    //Update User Balance
                    $balance = $user->balance - $request->amount;
                    User::where('id', $user->id)->update([
                        'balance' => $balance,
                    ]);

                    // 3. CRIAR SOLICITAÇÃO DE SAQUE
                    $withdrawal = Withdrawal::create([
                        'user_id'      => $user->id,
                        'method_name'  => $user->gateway_method,
                        'name'         => $request->name,
                        'cpf'          => $request->document,
                        'amount'       => $request->amount,
                        'final_amount' => $request->amount - $charge,
                        'charge'       => $charge,
                        'oid'          => rand(000000, 999999) . rand(000000, 999999) . rand(000000, 999999),
                        'pix_key'      => $request->pixKey,
                        'pix_type'     => $request->pixType,
                        'address'      => 'PIXUP',
                        'status'       => $this->determineInitialStatus($analysis),
                    ]);

                    Log::info('Novo saque solicitado', [
                        'withdrawal_id' => $withdrawal->id,
                        'user_id' => $user->id,
                        'initial_status' => $withdrawal->status,
                        'risk_level' => $analysis['risk_level']
                    ]);

                    // 4. ANÁLISE ESPECÍFICA DO SAQUE
                    $withdrawalAnalysis = $this->fraudDetectionService->analyzeWithdrawal($withdrawal);

                    // Atualizar status se necessário
                    if (!$withdrawalAnalysis['should_approve']) {
                        $withdrawal->update(['status' => 'under_review']);
                    }

                    return response()->json([
                        'success' => true,
                        'withdrawal_id' => $withdrawal->id,
                        'status' => $withdrawal->status,
                        'message' => 'Solicitação de saque realizada com sucesso',
                        'estimated_processing_time' => $this->getProcessingTime($analysis['risk_level'])
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'CashOut amount less then ' . setting('maximum_withdraw')
                    ], 422);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'CashOut amount greater then ' . setting('minimum_withdraw')
                ], 422);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'CashOut balance insufficient'
            ], 422);
        }
    }

    private function determineInitialStatus($analysis): string
    {
        switch ($analysis['risk_level']) {
            case 'high':
                return 'blocked';
            case 'medium':
                return 'under_review';
            case 'low':
                return 'pending';
            default:
                return 'pending';
        }
    }

    private function getProcessingTime($riskLevel): string
    {
        return match ($riskLevel) {
            'high' => 'Indefinido - conta em verificação',
            'medium' => '24-48 horas',
            'low' => '2-12 horas',
            default => '30 minutos - 2 horas'
        };
    }
}
