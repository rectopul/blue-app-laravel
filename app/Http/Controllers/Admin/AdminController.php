<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\FraudAlert;
use App\Models\FundInvest;
use App\Models\Package;
use App\Models\Purchase;
use App\Models\Rebate;
use App\Models\User;
use App\Models\UserLedger;
use App\Models\Withdrawal;
use App\Services\FraudDetectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct(private FraudDetectionService $fraudDetectionService) {}
    public function fraudDashboard(FraudDetectionService $fraudService)
    {
        // Estatísticas gerais
        $stats = [
            'alerts_hoje' => FraudAlert::whereDate('created_at', today())->count(),
            'usuarios_alto_risco' => FraudAlert::where('risk_score', '>=', 80)
                ->where('status', 'pending')->distinct('user_id')->count(),
            'saques_bloqueados' => Withdrawal::where('status', 'blocked')
                ->whereDate('created_at', today())->count(),
            'investigacoes_pendentes' => FraudAlert::where('status', 'investigating')->count(),
        ];

        // Alertas recentes de alto risco
        $highRiskAlerts = FraudAlert::with('user')
            ->where('risk_score', '>=', 80)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Relatório dos últimos 7 dias
        $weeklyReport = $fraudService->getFraudReport(7);

        return view('admin.fraud-dashboard', compact('stats', 'highRiskAlerts', 'weeklyReport'));
    }

    public function investigateUser(Request $request, User $user, FraudDetectionService $fraudService)
    {
        // Análise completa do usuário
        $analysis = $fraudService->analyzeUser($user);

        // Histórico de transações
        $deposits = $user->deposits()->orderBy('created_at', 'desc')->limit(10)->get();
        $withdrawals = $user->withdrawals()->orderBy('created_at', 'desc')->limit(10)->get();
        $ledgers = $user->ledgers()->orderBy('created_at', 'desc')->limit(20)->get();

        // Rede de indicação
        $referralCounts = $user->getReferralCounts();

        // Alertas históricos
        $alerts = $user->fraudAlerts()->orderBy('created_at', 'desc')->get();

        return view('admin.investigate-user', compact(
            'user',
            'analysis',
            'deposits',
            'withdrawals',
            'ledgers',
            'referralCounts',
            'alerts'
        ));
    }

    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function commissionPay()
    {

        // Define o prefixo do log
        $logPrefix = '[Commission] ';

        // Obtém as compras ativas
        $purchases = Purchase::where('status', 'active')->get();

        Log::info($logPrefix . "Iniciando processamento de pagamentos diários - " . now()->format('Y-m-d H:i:s'));

        foreach ($purchases as $purchase) {
            $user = User::find($purchase->user_id);
            if ($user) {
                $package = Package::find($purchase->package_id);
                if (!$package) continue;


                DB::beginTransaction();
                try {
                    $package = $purchase->package;

                    $amountPay = $package->price * ($package->commission_with_avg_amount / 100);

                    $analysis = $this->fraudDetectionService->analyzeUser($user);

                    // Atualiza o saldo
                    $user->increment('balance', $amountPay);


                    Log::info($logPrefix . "Pagamento incluido para user_id {$user->id}: {$amountPay}");
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error($logPrefix . "Erro ao processar purchase {$purchase->id}: " . $e->getMessage() . ' [FILE]: ' . $e->getFile() . ' [LINE]:' . $e->getLine());
                    continue;
                }
            }
        }

        Log::info($logPrefix . "Processamento finalizado - " . now()->format('Y-m-d H:i:s'));
    }
    public function commissionBack()
    {

        // Define o prefixo do log
        $logPrefix = '[Commission] ';

        // Obtém as compras ativas
        $purchases = Purchase::where('status', 'active')
            ->where('created_at', '<=', Carbon::today()->addHours(3))
            ->get();

        Log::info($logPrefix . "Iniciando processamento de pagamentos diários - " . now()->format('Y-m-d H:i:s'));

        foreach ($purchases as $purchase) {
            $user = User::find($purchase->user_id);
            if ($user) {
                $package = Package::find($purchase->package_id);
                if (!$package) continue;


                DB::beginTransaction();
                try {
                    $package = $purchase->package;

                    $amountPay = $package->price * ($package->commission_with_avg_amount / 100);

                    $analysis = $this->fraudDetectionService->analyzeUser($user);

                    // Atualiza o saldo
                    $user->decrement('balance', $amountPay);


                    Log::info(
                        $logPrefix .
                            "Pagamento revertido para user_id {$user->id}: {$amountPay} | Criado em: " .
                            $purchase->created_at->format('Y-m-d H:i:s')
                    );
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error($logPrefix . "Erro ao processar purchase {$purchase->id}: " . $e->getMessage() . ' [FILE]: ' . $e->getFile() . ' [LINE]:' . $e->getLine());
                    continue;
                }
            }
        }

        Log::info($logPrefix . "Processamento finalizado - " . now()->format('Y-m-d H:i:s'));
    }
    public function commission()
    {

        // Define o prefixo do log
        $logPrefix = '[Commission] ';

        if (Carbon::now()->hour === 12) {

            // Obtém as compras ativas
            $purchases = Purchase::where('status', 'active')->get();

            Log::info($logPrefix . "Iniciando processamento de pagamentos diários - " . now()->format('Y-m-d H:i:s'));

            foreach ($purchases as $purchase) {
                $user = User::find($purchase->user_id);
                if ($user) {
                    $package = Package::find($purchase->package_id);
                    if (!$package) continue;

                    $today = now()->startOfDay();
                    $purchaseDate = Carbon::parse($purchase->date)->startOfDay();

                    if ($purchaseDate->lessThanOrEqualTo($today)) {
                        DB::beginTransaction();
                        try {
                            $package = $purchase->package;

                            $amountPay = $package->price * ($package->commission_with_avg_amount / 100);

                            $analysis = $this->fraudDetectionService->analyzeUser($user);

                            // Atualiza o saldo
                            $user->increment('balance', $amountPay);

                            // Ledger
                            UserLedger::create([
                                'user_id'       => $user->id,
                                'reason'        => 'daily_income',
                                'perticulation' => UserLedger::generatePerticulation('daily_income', $amountPay),
                                'amount'        => $amountPay,
                                'credit'        => $amountPay,
                                'status'        => 'approved',
                                'date'          => now()
                            ]);

                            // Próxima data
                            $purchase->daily_income += $amountPay;
                            $purchase->date = now()->addDay()->startOfDay();
                            $purchase->save();

                            $expirationDate = Carbon::parse($purchase->purchased_at)->addDays((int)$package->validity);

                            // Verifica validade
                            if (now()->greaterThan($expirationDate)) {
                                $purchase->status = 'inactive';
                                $purchase->save();
                            }

                            Log::info($logPrefix . "Pagamento processado para user_id {$user->id}: {$amountPay}");
                            DB::commit();
                        } catch (\Exception $e) {
                            DB::rollBack();
                            Log::error($logPrefix . "Erro ao processar purchase {$purchase->id}: " . $e->getMessage() . ' [FILE]: ' . $e->getFile() . ' [LINE]:' . $e->getLine());
                            continue;
                        }
                    }
                }
            }

            Log::info($logPrefix . "Processamento finalizado - " . now()->format('Y-m-d H:i:s'));
        }
    }


    public function login_submit(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();
            if ($admin->type == 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Logged In Successful.');
            } else {
                return error_redirect('admin.login', 'error', 'Admin Credentials Very Secured Please Don"t try Again.');
            }
        } else {
            return error_redirect('admin.login', 'error', 'Admin Credentials Does Not Match.');
        }
    }

    public function logout()
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
            return redirect()->route('admin.login')->with('success', 'Logged out successful.');
        } else {
            return error_redirect('admin.login', 'error', 'You are already logged out.');
        }
    }

    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function developer()
    {
        return view('admin.developer');
    }

    public function profile()
    {
        return view('admin.profile.index');
    }

    public function profile_update()
    {
        $admin = Admin::first();
        return view('admin.profile.update-details', compact('admin'));
    }

    public function profile_update_submit(Request $request)
    {
        $admin = Admin::find(1);
        $path = uploadImage(false, $request, 'photo', 'admin/assets/images/profile/', $admin->photo);
        $admin->photo = $path ?? $admin->photo;
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->address = $request->address;
        $admin->update();
        return redirect()->route('admin.profile.update')->with('success', 'Admin profile updated.');
    }

    public function change_password()
    {
        $admin = admin()->user();
        return view('admin.profile.change-password', compact('admin'));
    }

    public function check_password(Request $request)
    {
        $admin = admin()->user();
        $password = $request->password;
        if (Hash::check($password, $admin->password)) {
            return response()->json(['message' => 'Password matched.', 'status' => true]);
        } else {
            return response()->json(['message' => 'Password dose not match.', 'status' => false]);
        }
    }

    public function change_password_submit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required'
        ]);
        if ($validate->fails()) {
            session()->put('errors', true);
            return redirect()->route('admin.changepassword')->withErrors($validate->errors());
        }

        $admin = admin()->user();
        $password = $request->old_password;
        if (Hash::check($password, $admin->password)) {
            if (strlen($request->new_password) > 5 && strlen($request->confirm_password) > 5) {
                if ($request->new_password === $request->confirm_password) {
                    $admin->password = Hash::make($request->new_password);
                    $admin->update();
                    return redirect()->route('admin.changepassword')->with('success', 'Password changed successfully');
                } else {
                    return error_redirect('admin.changepassword', 'error', 'New password and confirm password dose not match');
                }
            } else {
                return error_redirect('admin.changepassword', 'error', 'Password must be greater then 6 or equal.');
            }
        } else {
            return error_redirect('admin.changepassword', 'error', 'Password dose not match');
        }
    }
}
