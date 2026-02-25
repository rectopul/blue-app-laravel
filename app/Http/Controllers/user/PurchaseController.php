<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\PurchaseRequest;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\Rebate;
use App\Models\User;
use App\Models\UserLedger;
use App\Services\{FraudDetectionService, PurchaseService};
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function __construct(private FraudDetectionService $fraudDetectionService, private PurchaseService $purchaseService) {}

    public function purchase_vip($id)
    {
        $package = Package::find($id);
        return view('app.main.vip_confirm', compact('package'));
    }

    public function purchaseStore(PurchaseRequest $request)
    {
        $user = Auth::user();
        $package = Package::findOrFail($request->package_id);
        $rebate = Rebate::first();

        // Check status
        if ($package->status != 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Este pacote está inativo no momento.'
            ], 400);
        }

        $analysis = $this->fraudDetectionService->analyzeUser($user);

        // Bloquear se risco muito alto
        if ($analysis['risk_level'] === 'high') {
            return response()->json([
                'error' => 'Sua conta foi temporariamente suspensa para verificação de segurança.',
                'contact_support' => true,
                'risk_level' => $analysis['risk_level']
            ], 403);
        }

        // Check if user already has an active purchase for this package
        $existingPurchase = Purchase::where('user_id', $user->id)
            ->where('package_id', $package->id)
            ->where('status', 'active')
            ->count();

        if ($existingPurchase > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Você já possui este investimento ativo.'
            ]);
        }

        // Check if user has sufficient balance
        if ($package->price > $user->balance) {
            return response()->json([
                'status' => false,
                'message' => 'Saldo insuficiente para realizar este investimento.'
            ], 400);
        }

        // Begin transaction to ensure data integrity
        DB::beginTransaction();

        try {
            // Update user balance and investor status
            User::where('id', $user->id)->update([
                'balance' => $user->balance - $package->price,
                'investor' => 1
            ]);

            $amountPay = $package->price * ($package->commission_with_avg_amount / 100);

            // Create purchase record
            $purchase = new Purchase();
            $purchase->user_id = $user->id;
            $purchase->package_id = $package->id;
            $purchase->amount = $package->price;
            $purchase->daily_income = $amountPay;
            $purchase->date = now()->addHours(24);
            $purchase->validity = now()->addDays($package->validity);
            $purchase->status = 'active';
            $purchase->save();

            // Process referral commissions
            $this->processReferralCommissions($user, $package, $rebate);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Parabéns. Você acabou de adquirir um excelente investimento!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro ao processar sua compra. Por favor, tente novamente.'
            ], 500);
        }
    }

    /**
     * Credit commission to a user and create ledger entry
     */
    private function creditCommission($user, $sourceUser, $amount, $description, $step)
    {
        // Update user balance
        User::where('id', $user->id)->update([
            'balance' => $user->balance + $amount
        ]);

        // Create ledger entry
        $ledger = new UserLedger();
        $ledger->user_id = $user->id;
        $ledger->get_balance_from_user_id = $sourceUser->id;
        $ledger->reason = 'commission';
        $ledger->perticulation = $description;
        $ledger->amount = $amount;
        $ledger->debit = $amount;
        $ledger->status = 'approved';
        $ledger->step = $step;
        $ledger->date = date('d-m-Y H:i');
        $ledger->save();
    }

    /**
     * Process referral commissions for up to three levels
     */
    private function processReferralCommissions($user, $package, $rebate)
    {
        // First level referral
        $firstRef = User::where('ref_id', $user->ref_by)->first();
        if ($firstRef) {
            $amount = $package->price * $rebate->interest_commission1 / 100;
            $this->creditCommission($firstRef, $user, $amount, 'First Level Commission Received', 'first');

            // Second level referral
            $secondRef = User::where('ref_id', $firstRef->ref_by)->first();
            if ($secondRef) {
                $amount = $package->price * $rebate->interest_commission2 / 100;
                $this->creditCommission($secondRef, $user, $amount, 'Second Level Commission Received', 'second');

                // Third level referral
                $thirdRef = User::where('ref_id', $secondRef->ref_by)->first();
                if ($thirdRef) {
                    $amount = $package->price * $rebate->interest_commission3 / 100;
                    $this->creditCommission($thirdRef, $user, $amount, 'Third Level Commission Received', 'third');
                }
            }
        }
    }

    /**
     * Confirmação de compra de pacote
     */
    public function purchaseConfirmation(Request $request, int $packageId): JsonResponse
    {
        try {
            // Validação básica
            $request->validate([
                'confirmation' => 'required|boolean|accepted'
            ]);

            $user = Auth::user();

            // Log da tentativa de compra
            Log::info('Purchase attempt', [
                'user_id' => $user->id,
                'package_id' => $packageId,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            // Processar compra através do service
            $result = $this->purchaseService->processPurchase($user, $packageId, $request->ip());

            return response()->json([
                'status' => 'success',
                'message' => 'Parabéns, você adquiriu um investimento!',
                'purchase_id' => $result['purchase_id'],
                'package_name' => $result['package_name'],
                'amount' => $result['amount'],
                'new_balance' => $result['new_balance']
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 400);
        } catch (\App\Exceptions\InsufficientBalanceException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Saldo insuficiente'
            ], 400);
        } catch (\App\Exceptions\PackageNotAvailableException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pacote não disponível'
            ], 400);
        } catch (\App\Exceptions\DuplicatePurchaseException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Você já possui uma compra ativa deste pacote'
            ], 409);
        } catch (\App\Exceptions\SecuritySuspensionException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sua conta foi suspensa para análise de segurança'
            ], 403);
        } catch (\App\Exceptions\RateLimitExceededException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Muitas tentativas. Tente novamente em alguns minutos'
            ], 429);
        } catch (\Exception $e) {
            Log::error('Purchase error', [
                'user_id' => Auth::id(),
                'package_id' => $packageId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno do servidor'
            ], 500);
        }
    }

    public function purchaseConfirmationWeb($id)
    {
        $package = Package::find($id);
        $user = Auth::user();
        $rebate = Rebate::first();

        //Check status
        if ($package->status != 'active') {
            return back()->with('success', 'This is in-activate');
        }

        $analysis = $this->fraudDetectionService->analyzeUser($user);

        // Bloquear se risco muito alto
        if ($analysis['risk_level'] === 'high') {
            return back()->with('error', 'Sua conta foi temporariamente suspensa para verificação de segurança.');
        }

        //check exists
        $eee = Purchase::where('user_id', $user->id)->where('package_id', $package->id)->where('status', 'active')->count();
        if ($eee > 0) {
            return back()->with('success', 'This is activate your account');
        }

        if ($package) {
            if ($package->price <= $user->balance) {
                User::where('id', $user->id)->update([
                    'balance' => $user->balance - $package->price,
                    'investor' => 1
                ]);

                //Purchase Table Create
                $purchase = new Purchase();
                $purchase->user_id = Auth::id();
                $purchase->package_id = $package->id;
                $purchase->amount = $package->price;
                $purchase->daily_income = $package->commission_with_avg_amount / $package->validity;
                $purchase->date = now()->addHours(24);
                $purchase->validity = now()->addDays($package->validity);
                $purchase->status = 'active';
                $purchase->save();

                $first_ref = User::where('ref_id', Auth::user()->ref_by)->first();
                if ($first_ref) {
                    $amount = $package->price * $rebate->interest_commission1 / 100;

                    User::where('id', $first_ref->id)->update([
                        'balance' => $first_ref->balance + $amount
                    ]);

                    $ledger = new UserLedger();
                    $ledger->user_id = $first_ref->id;
                    $ledger->get_balance_from_user_id = $user->id;
                    $ledger->reason = 'commission';
                    $ledger->perticulation = 'First Level Commission Received';
                    $ledger->amount = $amount;
                    $ledger->debit = $amount;
                    $ledger->status = 'approved';
                    $ledger->step = 'first';
                    $ledger->date = date('d-m-Y H:i');
                    $ledger->save();

                    $second_ref = User::where('ref_id', $first_ref->ref_by)->first();
                    if ($second_ref) {
                        $amount = $package->price * $rebate->interest_commission2 / 100;
                        User::where('id', $second_ref->id)->update([
                            'balance' => $second_ref->balance + $amount
                        ]);

                        $ledger = new UserLedger();
                        $ledger->user_id = $second_ref->id;
                        $ledger->get_balance_from_user_id = $user->id;
                        $ledger->reason = 'commission';
                        $ledger->perticulation = 'Second Level Commission Received';
                        $ledger->amount = $amount;
                        $ledger->debit = $amount;
                        $ledger->status = 'approved';
                        $ledger->step = 'second';
                        $ledger->date = date('d-m-Y H:i');
                        $ledger->save();

                        $third_ref = User::where('ref_id', $second_ref->ref_by)->first();
                        if ($third_ref) {
                            $amount = $package->price * $rebate->interest_commission3 / 100;
                            User::where('id', $third_ref->id)->update([
                                'balance' => $third_ref->balance + $amount
                            ]);

                            $ledger = new UserLedger();
                            $ledger->user_id = $third_ref->id;
                            $ledger->get_balance_from_user_id = $user->id;
                            $ledger->reason = 'commission';
                            $ledger->perticulation = 'Third Level Commission Received';
                            $ledger->amount = $amount;
                            $ledger->debit = $amount;
                            $ledger->status = 'approved';
                            $ledger->step = 'third';
                            $ledger->date = date('d-m-Y H:i');
                            $ledger->save();
                        }
                    }
                }
                return back()->with('success', 'Parabéns. Você acabou de adquirir um excelente investimento!');
            } else {
                return back()->with('success', 'Saldo insuficiente');
            }
        } else {
            return back()->with('success', "It's our default VIP");
        }
    }


    public function vip_confirm($vip_id)
    {
        $vip = Package::find($vip_id);
        return view('app.main.vip_confirm', compact('vip'));
    }

    protected function ref_user($ref_by)
    {
        return User::where('ref_id', $ref_by)->first();
    }
}
