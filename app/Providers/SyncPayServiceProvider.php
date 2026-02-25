<?php

namespace App\Providers;

use App\Http\Controllers\SyncPay;
use Illuminate\Support\ServiceProvider;

class SyncPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SyncPay::class, function ($app) {
            return new SyncPay(
                'https://api.syncpay.pro',
                env('SYNCPAY_API_KEY', ''), // API Key do .env
                env('SYNCPAY_REFERENCE', 'testDepositApaxPay')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
