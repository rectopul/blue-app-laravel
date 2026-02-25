<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CountVisits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $today = now()->toDateString();

        \App\Models\Visit::updateOrCreate(
            ['date' => $today],
            ['count' => \DB::raw('count + 1')]
        );

        return $next($request);
    }
}
