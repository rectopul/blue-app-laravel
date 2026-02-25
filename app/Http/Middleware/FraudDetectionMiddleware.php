<?php

namespace App\Http\Middleware;

use App\Services\FraudDetectionService;
use Closure;
use Illuminate\Http\Request;

class FraudDetectionMiddleware
{
    public function __construct(private FraudDetectionService $fraudDetectionService) {}
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if ($request->route()->getName() === 'api.withdraw.store' && $request->user()) {
            $analysis = $this->fraudDetectionService->analyzeUser($request->user());

            if ($analysis['risk_level'] === 'high') {
                return response()->json([
                    'error' => 'Sua conta foi temporariamente suspensa para verificação de segurança.',
                    'contact_support' => true
                ], 403);
            }

            // Adicionar informações da análise à request
            $request->merge(['fraud_analysis' => $analysis]);
        }

        return $next($request);
    }
}
