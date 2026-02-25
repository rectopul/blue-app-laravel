<?php

use App\Http\Controllers\Api\FraudController;
use App\Http\Controllers\Api\OnepayController;
use App\Http\Controllers\user\PurchaseController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\WithdrawController;
use App\Http\Controllers\WebhooksController;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware(['throttle:60,1'])->group(function () {

    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('deposit', [UserController::class, 'depositStore'])->name('api.deposit.store');
        Route::post('withdraw', [WithdrawController::class, 'apiWithdraww'])->name('api.withdraw.store');

        // Package
        Route::post('purchase/confirmation/{id}', [PurchaseController::class, 'purchaseConfirmation'])->name('api.purchase.confirmation');

        Route::prefix('fraud')->group(function () {
            Route::get('check-user/{user}', [FraudController::class, 'checkUser']);
            Route::post('check-withdrawal', [FraudController::class, 'checkWithdrawal']);
            Route::get('stats', [FraudController::class, 'stats']);
            Route::post('alerts/{alert}/false-positive', [FraudController::class, 'markFalsePositive']);
        });
    });

    Route::middleware('auth:sanctum')->post('/user/purchase', [PurchaseController::class, 'purchaseStore'])->name('user.purchase.store');
});

Route::post('/webhook/pixup/confirm_deposits', [WebhooksController::class, 'pixupWebhookDeposit'])->name('pixup.webhook');
Route::post('/webhook/pixup/confirm_deposits', [WebhooksController::class, 'pixupWebhookDeposit'])->name('valorion.webhook');
Route::post('/webhook/vizionpay/{type}', [WebhooksController::class, 'posseidonPayWebhook'])->name('posseidonpay.webhook');
Route::get('/user/network/{user}', [UserController::class, 'processIndividualComissions'])->name('user.process.individual.comissions');
