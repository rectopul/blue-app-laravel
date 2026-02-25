<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Não autorizado.'], 401);
        }

        // Verificação rápida antes da transaction (opcional)
        if (!$this->canUserCheckin($user)) {
            return response()->json(['message' => 'Você já realizou seu check-in hoje!'], 409);
        }

        try {
            DB::beginTransaction();

            // Double-check dentro da transaction para evitar race conditions
            if ($user->checkins()->whereDate('date', Carbon::today())->exists()) {
                DB::rollBack();
                return response()->json(['message' => 'Você já realizou seu check-in hoje!'], 409);
            }

            // Cria o checkin (usando relação, user_id será preenchido automaticamente)
            $user->checkins()->create([
                'amount' => 1.00,
                'status' => 'active',
                'date' => Carbon::now(),
            ]);

            // Incrementa o saldo do usuário
            $user->increment('balance', 1.00);

            DB::commit();

            return response()->json([
                'message' => 'Check-in realizado com sucesso! Você recebeu R$ 1,00 de bônus.'
            ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao processar check-in: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao processar check-in.'], 500);
        }
    }

    public function canUserCheckin($user)
    {
        // Retorna true se NÃO existe checkin hoje
        return !$user->checkins()->whereDate('date', Carbon::today())->exists();
    }
}
