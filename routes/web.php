<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ManageWithdrawController;
use App\Http\Controllers\user\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('clear', function () {
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    return redirect()->back();
});

Route::middleware(['throttle:60,1'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('login');
    });

    require __DIR__ . '/admin.php';
    require __DIR__ . '/user.php';

    Route::get('download-apk', [UserController::class, 'download_apk'])->name('user.download.apk');
});

Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/deposit/status/{id}', [UserController::class, 'getDepositStatus'])->name('deposit.status');
    Route::post('/apiPayment', [UserController::class, 'apiPayment'])->name('apiPayment');
    Route::post('/connect/webhook', [UserController::class, 'apiPayment'])->name('connect.webhook');
    Route::post('apiWithdraw', [ManageWithdrawController::class, 'webhookWithdrawn'])->name('apiWithdraw');

    // BitFlow Webhooks
    Route::post('webhook/bitflow/pix-in', [\App\Http\Controllers\Webhooks\BitFlowWebhookController::class, 'pixIn'])->name('bitflow.webhook.pix-in');
    Route::post('webhook/bitflow/pix-out', [\App\Http\Controllers\Webhooks\BitFlowWebhookController::class, 'pixOut'])->name('bitflow.webhook.pix-out');

    //CronJob
    Route::get('commission-interest', [AdminController::class, 'commission']);
    Route::get('commission-revert', [AdminController::class, 'commissionBack']);
    Route::get('commission-pay', [AdminController::class, 'commissionPay']);

    require __DIR__ . '/auth.php';
});
