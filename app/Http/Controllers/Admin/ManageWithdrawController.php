<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SyncPay;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdrawal;
use App\Services\ValorionPay\ValorionPayService;
use Illuminate\Http\Request;

class ManageWithdrawController extends Controller
{

    private $syncpay;

    public function __construct(
        SyncPay $syncpay,
        private ValorionPayService $valorionPayService,
    ) {
        $this->syncpay = $syncpay;
    }

    public function webhookWithdrawn(Request $request)
    {
        $data = $request->all();

        try {
            $verify = $this->syncpay->processCashOutWebhook($data);

            if ($verify['transaction_id']) {
                $transactionId = $verify['transaction_id'];

                $whithdraw = Withdrawal::where('transaction_id', $transactionId)->first();

                if ($whithdraw) {
                    $whithdraw->status = 'approved';
                    $whithdraw->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Saque processado com sucesso'
                    ], 200);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'IdTransaction não encontrado'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao processar saque: ' . $e->getMessage()
            ], 404);
        }
    }

    public function pendingWithdraw()
    {
        $title = 'Pending';
        $withdraws = Withdrawal::with(['user', 'payment_method'])
            ->whereIn('status', ['pending', 'under_review', 'blocked'])
            ->orderByDesc('id')
            ->get();

        // Itera sobre os saques para adicionar os dados da equipe
        foreach ($withdraws as $withdraw) {
            if ($withdraw->user) {
                $user = $withdraw->user;

                // Busca os IDs dos usuários de cada nível
                $first_level_users = User::where('ref_by', $user->ref_id)->pluck('id');
                $second_level_users = User::whereIn('ref_by', $first_level_users->toArray())->pluck('id');
                $third_level_users = User::whereIn('ref_by', $second_level_users->toArray())->pluck('id');

                // Calcula o tamanho total da equipe
                $team_size = $first_level_users->count() + $second_level_users->count() + $third_level_users->count();

                // Calcula depósitos e saques totais por nível
                $lv1Recharge = Deposit::whereIn('user_id', $first_level_users)->where('status', 'approved')->sum('amount');
                $lv2Recharge = Deposit::whereIn('user_id', $second_level_users)->where('status', 'approved')->sum('amount');
                $lv3Recharge = Deposit::whereIn('user_id', $third_level_users)->where('status', 'approved')->sum('amount');
                $lvTotalDeposit = $lv1Recharge + $lv2Recharge + $lv3Recharge;

                $lv1Withdraw = Withdrawal::whereIn('user_id', $first_level_users)->where('status', 'approved')->sum('amount');
                $lv2Withdraw = Withdrawal::whereIn('user_id', $second_level_users)->where('status', 'approved')->sum('amount');
                $lv3Withdraw = Withdrawal::whereIn('user_id', $third_level_users)->where('status', 'approved')->sum('amount');
                $lvTotalWithdraw = $lv1Withdraw + $lv2Withdraw + $lv3Withdraw;

                // Adiciona as estatísticas da equipe ao objeto do usuário
                $withdraw->user->team_stats = [
                    'team_size' => $team_size,
                    'total_deposit' => $lvTotalDeposit,
                    'total_withdraw' => $lvTotalWithdraw
                ];

                // Adiciona os depósitos e saques do próprio usuário
                $withdraw->user->my_deposit = Deposit::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
                $withdraw->user->my_withdraw = Withdrawal::where('user_id', $user->id)->where('status', 'approved')->sum('amount');
            }
        }

        // Retorna a view com os dados já prontos
        return view('admin.pages.withdraw.list', [
            'withdraws' => $withdraws,
            'title' => 'Withdraws'
        ]);
    }

    public function rejectedWithdraw()
    {
        $title = 'Rejected';
        $withdraws = Withdrawal::with(['user', 'payment_method'])->where('status', 'rejected')->orderByDesc('id')->get();
        return view('admin.pages.withdraw.list', compact('withdraws', 'title'));
    }

    public function approvedWithdraw()
    {
        $title = 'Approved';
        $withdraws = Withdrawal::with(['user', 'payment_method'])->where('status', 'approved')->orderByDesc('id')->get();
        return view('admin.pages.withdraw.list', compact('withdraws', 'title'));
    }

    private function getClientIPv4(Request $request): ?string
    {
        // 1. Prioriza header Cloudflare (CF-Connecting-IP) se existir
        $ip = $request->header('CF-Connecting-IP')
            ?? $request->header('X-Forwarded-For')
            ?? $request->ip();

        if (! $ip) {
            return null;
        }

        // Se veio uma lista em X-Forwarded-For, pega o primeiro (cliente original)
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }

        // Se for IPv4 mapeado em IPv6 (ex: ::ffff:192.0.2.128), extrai a parte IPv4
        if (strpos($ip, '::ffff:') === 0) {
            $ip = substr($ip, 7);
        }

        // Valida que é IPv4
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) ? $ip : null;
    }

    public function withdrawStatus(Request $request, $id)
    {


        try {
            $withdraw = Withdrawal::find($id);
            $user = User::find($withdraw->user_id);

            $ip = $this->getClientIPv4($request) ?? '82.25.67.40';

            if (!$withdraw) {
                return redirect()->back()->with('success', 'Transação não encontrada');
            }

            if ($request->status == 'rejected') {
                $userRe = User::find($withdraw->user_id);
                $userRe->balance = $userRe->balance + $withdraw->amount;
                $userRe->save();

                $withdraw->status = 'rejected';
                $withdraw->save();

                return redirect()->back()->with('success', 'Withdraw status change successfully.');
            }

            $pixType = '';
            switch ($withdraw->pix_type) {
                case 'RANDOM':
                    $pixType = 'random';
                    break;
                case 'CPF':
                    $pixType = 'CPF';
                    break;

                default:
                    $pixType = $withdraw->pix_type;
                    break;
            }

            // Normaliza PIX KEY conforme tipo
            $pixKey = $withdraw->pix_key;

            $pixType = strtolower($withdraw->pix_type);

            switch ($pixType) {
                case 'phone':
                    // remove tudo que não é número
                    $pixKey = preg_replace('/\D+/', '', $pixKey);

                    // valida tamanho mínimo (ex: DDD + número)
                    if (strlen($pixKey) < 10 || strlen($pixKey) > 13) {
                        throw new \InvalidArgumentException('Telefone PIX inválido.');
                    }
                    break;

                case 'CPF':
                    $pixKey = preg_replace('/\D+/', '', $pixKey);

                    if (strlen($pixKey) !== 11) {
                        throw new \InvalidArgumentException('CPF PIX inválido.');
                    }
                    break;

                case 'email':
                    if (!filter_var($pixKey, FILTER_VALIDATE_EMAIL)) {
                        throw new \InvalidArgumentException('E-mail PIX inválido.');
                    }
                    break;

                case 'random':
                    // chave aleatória → mantém como está
                    break;
            }

            $pixType = $pixType;

            // Normaliza documento (CPF)
            $document = preg_replace('/\D+/', '', $withdraw->cpf);

            $pixType = $withdraw->pix_type;

            // Normaliza documento (CPF)
            $document = preg_replace('/\D+/', '', $withdraw->cpf);

            // processa o saque
            $payloadParams = [
                'amount' => $withdraw->final_amount,
                'pix_type' => $pixType,
                'pix_key' => $pixKey,
                'name' => $withdraw->name,
                'document' => $document,
                'ip' => $ip
            ];

            $saque = $this->valorionPayService->cashOut($payloadParams);

            if (!$saque['data']) {
                return redirect()->back()->with('error', 'Erro ao processar pix: ' . $e->getMessage());
            }

            $withdraw->transaction_id = $saque['data']['idTransaction'] ?? null;
            $withdraw->status = $request->status;
            $withdraw->save();
            return redirect()->back()->with('success', 'Withdraw status change successfully.');
        } catch (\Exception $e) {
            \Log::error('Erro ao alterar saque:', [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', 'Falha: ' . $e->getMessage());
        }
    }

    public function aproveAll(Request $request)
    {
        $values = $request->input('values');

        if (!$values || !is_array($values)) {
            return back()->with('error', 'Nenhum saque selecionado.');
        }

        $aproveds = [];

        foreach ($values as $id) {
            $withdraw = Withdrawal::find($id);

            if (!$withdraw || $withdraw->status !== 'pending') {
                continue;
            }

            $user = User::find($withdraw->user_id);

            try {
                $pixType = match ($withdraw->pix_type) {
                    'random' => 'token',
                    'CPF' => 'cpf',
                    default => $withdraw->pix_type,
                };

                $saque = $this->valorionPayService->cashOut(
                    $withdraw->final_amount,
                    $withdraw->name,
                    $withdraw->cpf,
                    $withdraw->pix_key,
                    $pixType
                );

                if (empty($saque['data'])) {
                    continue;
                }

                $withdraw->transaction_id = $saque['data']['idTransaction'];
                $withdraw->status = 'approved';
                $withdraw->save();

                $aproveds[] = $withdraw;
            } catch (\Exception $e) {
                \Log::error("Erro ao aprovar saque ID $id: " . $e->getMessage());
            }
        }

        return back()->with('success', count($aproveds) . ' saques aprovados com sucesso.');
    }

    public function withdrawChangeStatus(Request $request, $id)
    {


        try {
            $withdraw = Withdrawal::find($id);
            $user = User::find($withdraw->user_id);

            if (!$withdraw) {
                return response()->json([
                    'success' => false,
                    'message' => 'Saque não encontrado'
                ], 400);
            }

            if ($request->status == 'rejected') {
                $userRe = User::find($withdraw->user_id);
                $userRe->balance = $userRe->balance + $withdraw->amount;
                $userRe->save();

                $withdraw->status = 'rejected';
                $withdraw->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Saque alterado com sucesso'
                ], 200);
            }

            $pixType = '';
            switch ($withdraw->pix_type) {
                case 'random':
                    $pixType = 'RANDOM';
                    break;
                case 'CPF':
                    $pixType = 'cpf';
                    break;

                default:
                    $pixType = $withdraw->pix_type;
                    break;
            }

            // processa o saque
            $saque = $this->syncpay->cashOut($withdraw->final_amount, $withdraw->name, $withdraw->cpf, $withdraw->pix_key, $pixType);

            if (!$saque['data']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao processar pix: ' . $e->getMessage()
                ], 400);
            }

            $withdraw->transaction_id = null;
            $withdraw->status = $request->status;
            $withdraw->save();
            return response()->json([
                'success' => true,
                'message' => 'Saque alterado com sucesso'
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao alterar saque:', [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Falha: ' . $e->getMessage()
            ], 400);
        }
    }

    /*public function aproveAll(Request $request)
    {
        $values = $request->values; // Verifica se o array de IDs foi enviado corretamente

        if (!$values || !is_array($values)) {
            return response()->json(['message' => 'Nenhum valor válido fornecido.'], 400);
        }

        $aproveds = [];

        foreach ($values as $id) {
            $withdraw = Withdrawal::find($id);

            if (!$withdraw) {
                continue; // Ignora IDs inválidos
            }

            $user = User::find($withdraw->user_id);

            try {

                $pixType = '';
                switch ($withdraw->pix_type) {
                    case 'random':
                        $pixType = 'token';
                        break;
                    case 'CPF':
                        $pixType = 'cpf';
                        break;

                    default:
                        $pixType = $withdraw->pix_type;
                        break;
                }

                // Processa o saque
                $saque = $this->syncpay->cashOut(
                    $withdraw->final_amount,
                    $user->realname,
                    $withdraw->cpf,
                    $withdraw->pix_key,
                    $pixType
                );

                if (empty($saque['data'])) {
                    return response()->json(['message' => 'Erro ao processar PIX.'], 400);
                }

                $withdraw->transaction_id = $saque['data']['idTransaction'];
                $withdraw->status = 'approved';
                $withdraw->save();

                $aproveds[] = $withdraw;
            } catch (\Exception $e) {
                return response()->json(['message' => 'Erro ao processar todos os saques: ' . $e->getMessage()], 500);
            }
        }

        return response()->json($aproveds, 200);
    }*/
}
