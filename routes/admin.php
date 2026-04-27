<?php

use App\Http\Controllers\Admin\AddonUpdateController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\RolePermisionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VersionUpdateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');


// Manage role Route Start
Route::group(['prefix' => 'roles', 'as' => 'roles.', 'middleware' => ['can:Manage Moderator']], function () {
    Route::get('/', [RolePermisionController::class, 'list'])->name('index');
    Route::post('store', [RolePermisionController::class, 'store'])->name('store');
    Route::get('edit/{id}', [RolePermisionController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [RolePermisionController::class, 'update'])->name('update');
    Route::post('destroy/{id}', [RolePermisionController::class, 'delete'])->name('destroy');
    Route::get('permission/{id}', [RolePermisionController::class, 'permission'])->name('permission');
    Route::post('permission-update', [RolePermisionController::class, 'permissionUpdate'])->name('permission-update');
});
// Manage role Route end

// Manage users Route Start
Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('update/{id}', [UserController::class, 'update'])->name('update');
    Route::post('destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
});
// Manage users Route end

Route::get('script-'.now()->format('Ymd'), [VersionUpdateController::class, 'pathFile'])->name('script-file');
Route::post('script-file', [VersionUpdateController::class, 'downloadPathFile'])->name('load-script-file');
Route::post('store-script-file', [VersionUpdateController::class, 'storePathFile'])->name('store-script-file');

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.', 'middleware' => ['can:Manage Application Setting']], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index');
        Route::get('edit/{id}', [GatewayController::class, 'edit'])->name('edit');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::get('syncs', [GatewayController::class, 'syncs'])->name('syncs');
    });

    //common setting update
    Route::post('common-settings-update', [SettingController::class, 'commonSettingUpdate'])->name('common.settings.update')->middleware('isDemo');



    Route::get('email-template', [EmailTemplateController::class, 'emailTemplate'])->name('email-template');
    Route::get('email-template-config', [EmailTemplateController::class, 'emailTemplateConfig'])->name('email.template.config');
    Route::post('email-template-config-update', [EmailTemplateController::class, 'emailTemplateConfigUpdate'])->name('email.template.config.update');
    Route::get('preview-test-mail/{id}', [EmailTemplateController::class, 'previewMailTest'])->name('preview-test-mail');
    Route::post('send-test-mail/{id}', [EmailTemplateController::class, 'sendTestMail'])->name('send-test-mail');

    Route::get('notify-template', [NotificationController::class, 'notifyTemplate'])->name('notify-template');
    Route::get('notify-template-config', [NotificationController::class, 'notifyTemplateConfig'])->name('notify.template.config');
    Route::post('notify-template-config-update', [NotificationController::class, 'notifyTemplateConfigUpdate'])->name('notify.template.config.update');

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('update', [ProfileController::class, 'update'])->name('update')->middleware('isDemo');
        Route::get('password', [ProfileController::class, 'password'])->name('password');
        Route::post('password-update', [ProfileController::class, 'passwordUpdate'])->name('password.update')->middleware('isDemo');
    });
});

// version update
Route::get('version-update', [VersionUpdateController::class, 'versionFileUpdate'])->name('version-update');
Route::post('version-update', [VersionUpdateController::class, 'versionFileUpdateStore'])->name('version-update-store');
Route::get('version-update-execute', [VersionUpdateController::class, 'versionUpdateExecute'])->name('version-update-execute');
Route::get('version-delete', [VersionUpdateController::class, 'versionFileUpdateDelete'])->name('version-delete');

Route::group(['prefix' => 'addon', 'as' => 'addon.'], function () {
    Route::get('details/{code}', [AddonUpdateController::class, 'addonDetails'])->name('details')->withoutMiddleware(['addon']);
    Route::post('store', [AddonUpdateController::class, 'addonFileStore'])->name('store')->withoutMiddleware(['addon']);
    Route::post('execute', [AddonUpdateController::class, 'addonFileExecute'])->name('execute')->withoutMiddleware(['addon']);
    Route::get('delete/{code}', [AddonUpdateController::class, 'addonFileDelete'])->name('delete')->withoutMiddleware(['addon']);
});

Route::group(['prefix' => 'subscription', 'as' => 'subscription.'], function () {
    Route::get('/', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('index');
});
