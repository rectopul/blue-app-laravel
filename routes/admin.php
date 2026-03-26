<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BonusController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\HiruSliderController;
use App\Http\Controllers\Admin\IconController;
use App\Http\Controllers\Admin\ManageUserController;
use App\Http\Controllers\Admin\ManageWithdrawController;
use App\Http\Controllers\Admin\NoticeController;
use App\Http\Controllers\Admin\FundController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\RebateController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VipSliderController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.login');
    });
    Route::get('login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('login', [AdminController::class, 'login_submit'])->name('admin.login-submit');
});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    //All Table Status
    Route::post('/table/status', [CommonController::class, 'status']);

    // Novas rotas para gerenciamento de usuários
    Route::post('user/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('admin.user.reset-password');
    Route::post('user/{id}/update-balance', [AdminUserController::class, 'updateBalance'])->name('admin.user.update-balance');
    Route::post('user/{id}/gift-bonus', [AdminUserController::class, 'giftBonus'])->name('admin.user.gift-bonus');

    //ADMIN PROFILE
    Route::get('profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::get('change/password', [AdminController::class, 'change_password'])->name('admin.changepassword');
    Route::post('check/password', [AdminController::class, 'check_password'])->name('admin.check.password');
    Route::post('change/password', [AdminController::class, 'change_password_submit'])->name('admin.changepasswordsubmit');
    Route::get('profile/update', [AdminController::class, 'profile_update'])->name('admin.profile.update');
    Route::post('profile/update', [AdminController::class, 'profile_update_submit'])->name('admin.profile.update-submit');

    //Notice
    Route::get('salary', [AdminController::class, 'salaryView'])->name('admin.salary');
    Route::get('salary-submit', [AdminController::class, 'salary'])->name('admin.salary.submit');
    Route::get('notice', [NoticeController::class, 'index'])->name('admin.notice.index');
    Route::get('notice/view/{id}', [NoticeController::class, 'view'])->name('admin.notice.view');
    Route::get('notice/create/{id?}', [NoticeController::class, 'create'])->name('admin.notice.create');
    Route::post('notice/insert-update', [NoticeController::class, 'insert_or_update'])->name('admin.notice.insert');
    Route::delete('notice/delete/{id}', [NoticeController::class, 'delete'])->name('admin.notice.delete');
    Route::get('purchase_delete/{id}', [ManageUserController::class, 'purchase_delete'])->name('admin.purchase-delete');

    //Slider
    Route::get('hiruslider', [HiruSliderController::class, 'index'])->name('admin.hiruslider.index');
    Route::get('hiruslider/create/{id?}', [HiruSliderController::class, 'create'])->name('admin.hiruslider.create');
    Route::post('hiruslider/insert-update', [HiruSliderController::class, 'insert_or_update'])->name('admin.hiruslider.insert');
    Route::delete('hiruslider/delete/{id}', [HiruSliderController::class, 'delete'])->name('admin.hiruslider.delete');

    //Fund
    Route::get('fund', [FundController::class, 'index'])->name('admin.fund.index');
    Route::get('fund/create/{id?}', [FundController::class, 'create'])->name('admin.fund.create');
    Route::post('fund/insert-update', [FundController::class, 'insert_or_update'])->name('admin.fund.insert');
    Route::delete('fund/delete/{id}', [FundController::class, 'delete'])->name('admin.fund.delete');
    Route::get('fund/view/{id}', [FundController::class, 'view'])->name('admin.fund.view');

    //Manage Customers
    Route::get('customers', [ManageUserController::class, 'customers'])->name('admin.customer.index');
    Route::get('customers/status/{id}', [ManageUserController::class, 'customersStatus'])->name('admin.customer.status');
    Route::get('customers/login/{id}', [ManageUserController::class, 'user_acc_login'])->name('admin.customer.login');
    Route::post('customers/change-password', [ManageUserController::class, 'user_acc_password'])->name('admin.customer.change-password');
    Route::get('search/user', [ManageUserController::class, 'search'])->name('admin.search.user');
    Route::get('search/user/action', [ManageUserController::class, 'searchSubmit'])->name('admin.search.submit');
    Route::post('provide/bonus/code', [ManageUserController::class, 'bonusCode'])->name('admin.customer.bonus');

    //Ban/Unban
    Route::get('/user-unban/{id}', [ManageUserController::class, 'unban'])->name('admin.user.unban');
    Route::get('/user-ban/{id}', [ManageUserController::class, 'ban'])->name('admin.user.ban');

    //Purchase Record
    Route::get('purchase/record', [ManageUserController::class, 'purchaseRecord'])->name('admin.purchase.index');
    Route::get('developer', [AdminController::class, 'developer'])->name('admin.developer.index');

    //VIP
    Route::get('package', [PackageController::class, 'index'])->name('admin.package.index');
    Route::get('set-bonus-vip/{id}', [PackageController::class, 'set_bonus_vip']);
    Route::get('package/create/{id?}', [PackageController::class, 'create'])->name('admin.package.create');
    Route::post('package/insert-update', [PackageController::class, 'insert_or_update'])->name('admin.package.insert');
    Route::delete('package/delete/{id}', [PackageController::class, 'delete'])->name('admin.package.delete');

    //Task
    Route::get('task', [TaskController::class, 'index'])->name('admin.task.index');
    Route::get('task/create/{id?}', [TaskController::class, 'create'])->name('admin.task.create');
    Route::post('task/insert-update', [TaskController::class, 'insert_or_update'])->name('admin.task.insert');
    Route::delete('task/delete/{id}', [TaskController::class, 'delete'])->name('admin.task.delete');

    //bonus
    Route::get('bonus', [BonusController::class, 'index'])->name('admin.bonus.index');
    Route::get('bonus/status/{id}', [BonusController::class, 'status'])->name('admin.bonus.status');
    Route::get('bonus/create/{id?}', [BonusController::class, 'create'])->name('admin.bonus.create');
    Route::post('bonus/insert-update', [BonusController::class, 'insert_or_update'])->name('admin.bonus.insert');
    Route::delete('bonus/delete/{id}', [BonusController::class, 'delete'])->name('admin.bonus.delete');
    Route::get('bonus/uses', [BonusController::class, 'bonuslist'])->name('admin.bonuslist.index');

    //VIP slider
    Route::get('vipslider', [VipSliderController::class, 'index'])->name('admin.vipslider.index');
    Route::get('vipslider/create/{id?}', [VipSliderController::class, 'create'])->name('admin.vipslider.create');
    Route::post('vipslider/insert-update', [VipSliderController::class, 'insert_or_update'])->name('admin.vipslider.insert');
    Route::delete('vipslider/delete/{id}', [VipSliderController::class, 'delete'])->name('admin.vipslider.delete');

    //Payment
    Route::get('method', [PaymentMethodController::class, 'index'])->name('admin.method.index');
    Route::get('method/create/{id?}', [PaymentMethodController::class, 'create'])->name('admin.method.create');
    Route::post('method/insert-update', [PaymentMethodController::class, 'insert_or_update'])->name('admin.method.insert');
    Route::delete('method/delete/{id}', [PaymentMethodController::class, 'delete'])->name('admin.method.delete');

    //Handle Customer Payment
    Route::get('customer/pending/payment', [ManageUserController::class, 'pendingPayment'])->name('admin.payment.pending');
    Route::get('customer/approved/payment', [ManageUserController::class, 'approvedPayment'])->name('admin.payment.approved');
    Route::get('customer/rejected/payment', [ManageUserController::class, 'rejectedPayment'])->name('admin.payment.rejected');
    Route::post('customer/payment/status/{id}', [ManageUserController::class, 'paymentStatus'])->name('payment.status.change');
    Route::get('customer/payment/approved/{id}', [ManageUserController::class, 'paymentStatusApproved'])->name('payment.status.change.approved');
    Route::get('customer/payment/rejected/{id}', [ManageUserController::class, 'paymentStatusRejected'])->name('payment.status.change.rejected');
    Route::get('customer/payment/pending/{id}', [ManageUserController::class, 'paymentStatusPending'])->name('payment.status.change.pending');

    //Handle Customer Withdraw
    Route::get('customer/pending/withdraw', [ManageWithdrawController::class, 'pendingWithdraw'])->name('admin.withdraw.pending');
    Route::get('customer/approved/withdraw', [ManageWithdrawController::class, 'approvedWithdraw'])->name('admin.withdraw.approved');
    Route::get('customer/rejected/withdraw', [ManageWithdrawController::class, 'rejectedWithdraw'])->name('admin.withdraw.rejected');
    Route::post('customer/withdraw/status/{id}', [ManageWithdrawController::class, 'withdrawStatus'])->name('withdraw.status.change');
    Route::post('customer/withdraw/approve-selected', [ManageWithdrawController::class, 'aproveAll'])->name('withdraw.approve.selected');

    //Settings
    Route::get('setting', [SettingController::class, 'index'])->name('admin.setting.index');
    Route::post('setting/insert-update', [SettingController::class, 'insert_or_update'])->name('admin.setting.insert');

    Route::get('rebate', [RebateController::class, 'index'])->name('admin.rebate.index');
    Route::post('rebate/insert-update', [RebateController::class, 'insert_or_update'])->name('admin.rebate.insert');

    //Icons
    Route::get('icon', [IconController::class, 'index'])->name('admin.icon.index');
    Route::post('icon/insert-update', [IconController::class, 'insert_or_update'])->name('admin.icon.insert');

    // Gamification Management
    Route::get('gamification', [\App\Http\Controllers\Admin\Gamification\AdminGamificationController::class, 'index'])->name('admin.gamification.index');
    Route::get('gamification/create', [\App\Http\Controllers\Admin\Gamification\AdminGamificationController::class, 'create'])->name('admin.gamification.create');
    Route::post('gamification/store', [\App\Http\Controllers\Admin\Gamification\AdminGamificationController::class, 'store'])->name('admin.gamification.store');
    Route::get('gamification/edit/{id}', [\App\Http\Controllers\Admin\Gamification\AdminGamificationController::class, 'edit'])->name('admin.gamification.edit');
    Route::post('gamification/update/{id}', [\App\Http\Controllers\Admin\Gamification\AdminGamificationController::class, 'update'])->name('admin.gamification.update');
    Route::delete('gamification/delete/{id}', [\App\Http\Controllers\Admin\Gamification\AdminGamificationController::class, 'destroy'])->name('admin.gamification.delete');

    //Balance add/minus
    Route::get('balance/add', [ManageUserController::class, 'add_balance'])->name('admin.user.balance.add');
    Route::get('balance/minus', [ManageUserController::class, 'minus_balance'])->name('admin.user.balance.minus');

    //List
    Route::get('mining/with-customer', [ManageUserController::class, 'continue_mining'])->name('admin.mining_purchase.index');
});
