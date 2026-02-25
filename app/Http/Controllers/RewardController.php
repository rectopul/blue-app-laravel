<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $rewards = $user->rewards()->orderBy('created_at', 'desc')->paginate(10);
        return view('rewards.index', compact('rewards'));
    }

    public function claimWeeklyReward()
    {
        $user = auth()->user();

        // Verificar se o usuário tem direito a recompensa semanal
        $weeklyCheckins = $user->checkins()
            ->where('checked_at', '>=', Carbon::now()->subDays(7))
            ->count();

        if ($weeklyCheckins < 5) {
            return redirect()->route('checkins.index')
                ->with('error', 'Você precisa fazer check-in pelo menos 5 dias na semana para receber a recompensa semanal.');
        }

        // Verificar se o usuário já recebeu a recompensa esta semana
        $rewardThisWeek = $user->rewards()
            ->where('type', 'weekly_checkin')
            ->where('created_at', '>=', Carbon::now()->startOfWeek())
            ->exists();

        if ($rewardThisWeek) {
            return redirect()->route('checkins.index')
                ->with('error', 'Você já recebeu sua recompensa semanal!');
        }

        // Calcular o valor da recompensa com base no número de check-ins
        $rewardAmount = $weeklyCheckins * 0.50; // R$ 0,50 por check-in

        // Criar recompensa
        $reward = $user->rewards()->create([
            'amount' => $rewardAmount,
            'type' => 'weekly_checkin',
            'description' => 'Recompensa semanal por ' . $weeklyCheckins . ' check-ins',
            'status' => 'processed',
            'processed_at' => Carbon::now(),
        ]);

        // Atualizar saldo do usuário
        $user->balance += $rewardAmount;
        $user->save();

        return redirect()->route('checkins.index')
            ->with('success', "Recompensa semanal recebida com sucesso! Você ganhou R$ " . number_format($rewardAmount, 2));
    }
}
