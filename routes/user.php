<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\user\MiningController;
use App\Http\Controllers\user\PurchaseController;
use App\Http\Controllers\user\SpinController;
use App\Http\Controllers\user\TeamController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\user\WithdrawController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\user\TaskController;
use App\Http\Controllers\Api\OnepayController;
use App\Modules\Gamification\Controllers\GamificationController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/home', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('setting', [UserController::class, 'setting'])->name('setting');
    Route::post('change-password', [UserController::class, 'setting_change_password'])->name('setting.change.password');

    Route::get('/change/password', [ProfileController::class, 'change_password'])->name('user.change.password');
    Route::post('/change/password/confirm', [ProfileController::class, 'change_password_confirm'])->name('user.change.password.confirmation');

    Route::get('/change/tpassword', [ProfileController::class, 'change_tpassword'])->name('user.change.tpassword');
    Route::post('/change/tpassword/confirm', [ProfileController::class, 'change_tpassword_confirm'])->name('user.change.tpassword.confirmation');

    Route::get('my-personal-details', [UserController::class, 'personal_details'])->name('user.personal-details');
    Route::post('my-personal-details', [UserController::class, 'personal_details_submit'])->name('user.personal-details-submit');

    //Bank Setup
    Route::get('add-bank', [UserController::class, 'add_bank'])->name('user.bank');
    Route::get('add-bank-create', [UserController::class, 'add_bank_create'])->name('user.bank.create');
    Route::post('/setup/gateway', [UserController::class, 'setupGateway'])->name('setup.gateway.submit');

    //deposit
    Route::get('/deposit', [UserController::class, 'recharge'])->name('user.deposit');
    Route::get('user/payment/{amount}/{method}', [UserController::class, 'payment_confirm']);

    //Withdraw
    Route::get('withdraw', [WithdrawController::class, 'withdraw'])->name('user.withdraw');
    Route::post('withdraw', [WithdrawController::class, 'apiWithdraww'])->name('api.withdraw.store');
    Route::post('withdraw-request', [WithdrawController::class, 'withdrawRequest'])->name('user.withdraw.request');

    //History & Ledger
    Route::get('recharge/history', [UserController::class, 'recharge_history'])->name('recharge.history');
    Route::get('withdraw/history', [WithdrawController::class, 'withdraw_history'])->name('withdraw.history');
    Route::get('commission/history', [UserController::class, 'commission'])->name('commission');
    Route::get('task/history', [UserController::class, 'task_history'])->name('task.history');
    Route::get('reword/history', [UserController::class, 'reword_history'])->name('reword.history');
    Route::get('spin/history', [SpinController::class, 'spin_history'])->name('spin.history');
    Route::get('amount/history', [UserController::class, 'amount_history'])->name('user.balance.history');
    Route::get('transactions', [UserController::class, 'historyTransactions'])->name('transactions.history');
    Route::get('/history/{condition?}', [UserController::class, 'history'])->name('history');
    Route::get('/all/history', [UserController::class, 'history_all'])->name('history.all');

    Route::get('package/show', [UserController::class, 'ordered'])->name('packages.show');
    Route::get('packages', [UserController::class, 'packages'])->name('packages.list');

    //VIP
    Route::get('/vip', [UserController::class, 'vip'])->name('vip');
    Route::get('/description', [UserController::class, 'description'])->name('description');
    Route::get('/ordered', [UserController::class, 'ordered'])->name('ordered');
    Route::get('/vip/commission', [UserController::class, 'vip_commission'])->name('vip.commission');
    Route::get('/task', [UserController::class, 'task'])->name('task');
    Route::get('/promotion', [UserController::class, 'promotion'])->name('promotion');

    // Task System
    Route::get('/tasks', [TaskController::class, 'index'])->name('user.tasks.index');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('user.tasks.show');
    Route::post('/tasks/complete/{id}', [TaskController::class, 'complete'])->name('user.tasks.complete');

    Route::get('vip/confirm/{id}', [PurchaseController::class, 'purchase_vip'])->name('vip.confirm');
    Route::get('purchase/confirmation/{id}', [PurchaseController::class, 'purchaseConfirmationWeb'])->name('purchase.confirmation');

    Route::get('rating-immediate', [UserController::class, 'rating_immediate'])->name('rating-immediate');

    //invite
    Route::get('/invite', [UserController::class, 'invite'])->name('user.invite');
    Route::get('/level', [UserController::class, 'level'])->name('vip.level');
    Route::get('/service', [UserController::class, 'service'])->name('user.service');

    //Team
    Route::get('my-team', [TeamController::class, 'team'])->name('user.team');
    Route::get('team-details/{generation}', [TeamController::class, 'team_details'])->name('team.details');
    Route::get('purchase/history', [UserController::class, 'purchase_history'])->name('purchase.history');

    Route::get('about', function () {
        return view('app.main.about');
    })->name('about');

    //Bonus
    Route::get('message', [UserController::class, 'message'])->name('message');
    Route::get('spin', [SpinController::class, 'spin'])->name('spin');
    Route::get('submit-bonus-check/{code}', [SpinController::class, 'submitbonuscheck']);
    Route::post('submit-bonus-amount', [SpinController::class, 'submitbonusamount']);

    //Investment
    Route::get('received-amount', [MiningController::class, 'received_amount'])->name('user.received.amount');

    // Gamification
    Route::post('gamification/collect/{id}', [GamificationController::class, 'collectEgg'])->name('gamification.collect');

    Route::get('online-payment/{amount}', [OnepayController::class, 'onlinePay']);
    Route::get('finance-order-trace', [OnepayController::class, 'traceFinance']);
});
