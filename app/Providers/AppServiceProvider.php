<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Services\FraudDetectionService;
use App\Modules\Gamification\Services\GamificationService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(FraudDetectionService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);

        View::composer('*', function ($view) {
            if (auth()->check()) {
                $gamificationService = app(GamificationService::class);
                $pageName = Route::currentRouteName();
                $activeEgg = $gamificationService->getEggForPage(auth()->user(), $pageName);
                $view->with('activeEgg', $activeEgg);
            }
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\AnalyzeFraudCommand::class,
            ]);
        }
    }
}
