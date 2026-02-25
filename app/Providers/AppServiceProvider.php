<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use App\Services\FraudDetectionService;
use Illuminate\Support\ServiceProvider;

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

        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\AnalyzeFraudCommand::class,
            ]);
        }
    }
}
