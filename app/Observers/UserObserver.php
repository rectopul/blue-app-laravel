<?php

namespace App\Observers;

use App\Models\User;
use App\Services\FraudDetectionService;

class UserObserver
{
    public function __construct(private FraudDetectionService $fraudDetectionService) {}

    public function created(User $user)
    {
        // Análise imediata após criação
        dispatch(function () use ($user) {
            $this->fraudDetectionService->analyzeUser($user);
        })->afterResponse();
    }

    public function updating(User $user)
    {
        // Verificar mudanças suspeitas
        if ($user->isDirty(['pix_key', 'phone', 'email'])) {
            dispatch(function () use ($user) {
                $this->fraudDetectionService->analyzeUser($user);
            })->afterResponse();
        }
    }
}
