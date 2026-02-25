<?php

namespace App\Services;

use App\Models\User;
use App\Exceptions\SecuritySuspensionException;
use Illuminate\Support\Facades\{Cache, Log};

class AntifraudService
{
    /**
     * Verificar usuário por comportamento suspeito
     */
    public function checkUser(User $user, string $ip): void
    {
        // Verificar se usuário está suspenso
        if ($user->status === 'suspended') {
            throw new SecuritySuspensionException();
        }

        // Verificar tentativas muito frequentes
        $this->checkFrequentAttempts($user->id, $ip);

        // Verificar padrões suspeitos
        $this->checkSuspiciousPatterns($user, $ip);
    }

    /**
     * Verificar tentativas muito frequentes
     */
    protected function checkFrequentAttempts(int $userId, string $ip): void
    {
        $key = "frequent_attempts_{$userId}_{$ip}";
        $attempts = Cache::get($key, []);

        // Adicionar tentativa atual
        $attempts[] = now()->timestamp;

        // Manter apenas últimas 10 tentativas
        $attempts = array_slice($attempts, -10);

        // Verificar se há mais de 5 tentativas em 1 minuto
        $recentAttempts = array_filter($attempts, function ($timestamp) {
            return $timestamp > (now()->timestamp - 60);
        });

        if (count($recentAttempts) >= 10) {
            Log::alert('Suspicious frequent attempts detected', [
                'user_id' => $userId,
                'ip' => $ip,
                'attempts_in_minute' => count($recentAttempts)
            ]);
            throw new SecuritySuspensionException();
        }

        Cache::put($key, $attempts, now()->addHour());
    }

    /**
     * Verificar padrões suspeitos
     */
    protected function checkSuspiciousPatterns(User $user, string $ip): void
    {
        // Verificar múltiplos IPs em pouco tempo
        $ipKey = "user_ips_{$user->id}";
        $userIps = Cache::get($ipKey, []);

        if (!in_array($ip, $userIps)) {
            $userIps[] = $ip;

            // Manter apenas últimos 5 IPs
            $userIps = array_slice($userIps, -5);

            // Se mais de 3 IPs diferentes em 1 hora, marcar como suspeito
            if (count($userIps) > 3) {
                Log::warning('Multiple IPs detected for user', [
                    'user_id' => $user->id,
                    'ips' => $userIps,
                    'current_ip' => $ip
                ]);
            }

            Cache::put($ipKey, $userIps, now()->addHour());
        }

        // Verificar horário suspeito (ex: 2h-6h da manhã com muitas transações)
        $hour = now()->hour;
        if ($hour >= 2 && $hour <= 6) {
            $nightKey = "night_purchases_{$user->id}";
            $nightPurchases = Cache::get($nightKey, 0) + 1;

            if ($nightPurchases > 3) {
                Log::warning('Suspicious night activity', [
                    'user_id' => $user->id,
                    'hour' => $hour,
                    'night_purchases' => $nightPurchases
                ]);
            }

            Cache::put($nightKey, $nightPurchases, now()->addDays(1));
        }
    }
}
