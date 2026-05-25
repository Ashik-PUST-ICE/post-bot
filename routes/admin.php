<?php

use App\Http\Controllers\Admin\AddonUpdateController;
use App\Http\Controllers\Admin\AiAgentController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\MetaAppController;
use App\Http\Controllers\Admin\MetaOAuthController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\PlatformController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReplyTemplateController;
use App\Http\Controllers\Admin\RolePermisionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VersionUpdateController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\QueueSettingController;
use App\Http\Controllers\Admin\AdminMailController;
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
    Route::get('/', [SubscriptionController::class, 'index'])->name('index');
    Route::post('cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
    Route::get('get-package', [SubscriptionController::class, 'getPackage'])->name('get.package');
    Route::post('get-gateway', [SubscriptionController::class, 'getGateway'])->name('get.gateway');
    Route::get('get-currency', [SubscriptionController::class, 'getCurrencyByGateway'])->name('get.currency');
    Route::post('checkout', [SubscriptionController::class, 'checkout'])->name('checkout');
    Route::get('verify', [SubscriptionController::class, 'verify'])->name('verify');
    Route::get('failed', [SubscriptionController::class, 'failed'])->name('failed');
});

// ─── Platform Connections ────────────────────────────────────────────────────
Route::group(['prefix' => 'platforms', 'as' => 'platforms.'], function () {
    Route::get('/', [PlatformController::class, 'index'])->name('index');
    Route::get('get-data', [PlatformController::class, 'getData'])->name('get.data');
    Route::post('store', [PlatformController::class, 'store'])->name('store');
    Route::get('get-info', [PlatformController::class, 'getInfo'])->name('get.info');
    Route::post('update/{id}', [PlatformController::class, 'update'])->name('update');
    Route::post('toggle-auto-reply/{id}', [PlatformController::class, 'toggleAutoReply'])->name('toggle-auto-reply');
    Route::post('resubscribe/{id}', [PlatformController::class, 'resubscribe'])->name('resubscribe');
    Route::post('destroy/{id}', [PlatformController::class, 'destroy'])->name('destroy');
});

// ─── Inbox / Conversations ───────────────────────────────────────────────────
Route::group(['prefix' => 'inbox', 'as' => 'inbox.'], function () {
    Route::get('/', [InboxController::class, 'index'])->name('index');
    Route::get('get-data', [InboxController::class, 'getData'])->name('get.data');
    Route::get('conversation/{id}', [InboxController::class, 'show'])->name('show');
    Route::get('conversation/{id}/messages', [InboxController::class, 'getMessages'])->name('messages');
    Route::post('conversation/{id}/reply', [InboxController::class, 'reply'])->name('reply');
    Route::post('conversation/{id}/status', [InboxController::class, 'updateStatus'])->name('update.status');
});

// ─── AI Agent Settings ───────────────────────────────────────────────────────
Route::group(['prefix' => 'ai-agent', 'as' => 'ai-agent.'], function () {
    Route::get('/', [AiAgentController::class, 'index'])->name('index');
    Route::get('knowledge', [AiAgentController::class, 'knowledge'])->name('knowledge');
    Route::post('update', [AiAgentController::class, 'update'])->name('update');
    Route::post('keyword/store', [AiAgentController::class, 'storeKeyword'])->name('keyword.store');
    Route::post('keyword/destroy/{id}', [AiAgentController::class, 'destroyKeyword'])->name('keyword.destroy');
    Route::post('test-connection', [AiAgentController::class, 'testConnection'])->name('test.connection');
    Route::get('models-for-provider', [AiAgentController::class, 'modelsForProvider'])->name('models.for.provider');
});


// ─── Meta App Configuration (Facebook / WhatsApp / Instagram) ────────────────
Route::group(['prefix' => 'meta-app', 'as' => 'meta-app.'], function () {
    Route::get('/', [MetaAppController::class, 'index'])->name('index');
    Route::post('update', [MetaAppController::class, 'update'])->name('update');
    Route::post('regenerate-token', [MetaAppController::class, 'regenerateVerifyToken'])->name('regenerate-token');
    Route::get('check-connection', [MetaAppController::class, 'checkConnection'])->name('check.connection');
});

// ─── Meta OAuth 2.0 Flow ────────────────────────────────────────────────────
Route::group(['prefix' => 'meta-oauth', 'as' => 'meta-oauth.'], function () {
    Route::get('redirect', [MetaOAuthController::class, 'redirect'])->name('redirect');
    Route::get('callback', [MetaOAuthController::class, 'callback'])->name('callback');
    Route::get('picker', [MetaOAuthController::class, 'picker'])->name('picker');
    Route::post('save-page', [MetaOAuthController::class, 'savePage'])->name('save.page');
});

// ─── Queue Settings ─────────────────────────────────────────────────────
Route::group(['prefix' => 'queue', 'as' => 'queue.'], function () {
    Route::get('status', [QueueSettingController::class, 'status'])->name('status');
    Route::post('save', [QueueSettingController::class, 'save'])->name('save');
    Route::post('retry-failed', [QueueSettingController::class, 'retryFailed'])->name('retry.failed');
    Route::post('flush-failed', [QueueSettingController::class, 'flushFailed'])->name('flush.failed');
});

// ─── Quick Reply Templates ───────────────────────────────────────────────────
Route::group(['prefix' => 'reply-templates', 'as' => 'reply-templates.'], function () {
    Route::get('/', [ReplyTemplateController::class, 'index'])->name('index');
    Route::get('get-data', [ReplyTemplateController::class, 'getData'])->name('get.data');
    Route::post('store', [ReplyTemplateController::class, 'store'])->name('store');
    Route::get('get-info', [ReplyTemplateController::class, 'getInfo'])->name('get.info');
    Route::post('destroy/{id}', [ReplyTemplateController::class, 'destroy'])->name('destroy');
    Route::get('for-inbox', [ReplyTemplateController::class, 'forInbox'])->name('for.inbox');
});

// ─── Mail / Email ────────────────────────────────────────────────────────────
Route::group(['prefix' => 'mail', 'as' => 'mail.'], function () {
    Route::get('config',             [AdminMailController::class, 'configIndex'])->name('config');
    Route::post('config/save',       [AdminMailController::class, 'configSave'])->name('config.save');
    Route::post('config/test',       [AdminMailController::class, 'configTest'])->name('config.test');
    Route::get('templates',          [AdminMailController::class, 'templates'])->name('templates');
    Route::get('templates/get',      [AdminMailController::class, 'getTemplate'])->name('templates.get');
    Route::post('templates/update',  [AdminMailController::class, 'updateTemplate'])->name('templates.update');
    Route::post('send',              [AdminMailController::class, 'sendToCustomer'])->name('send');
});

// ─── Notifications ───────────────────────────────────────────────────────────
Route::group(['prefix' => 'notifications', 'as' => 'notifications.'], function () {
    Route::get('/', [NotificationController::class, 'allNotification'])->name('index');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('mark-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('mark.read');
    Route::get('mark-all-read', [NotificationController::class, 'notificationMarkAllAsRead'])->name('mark.all.read');
    Route::get('delete/{id}', [NotificationController::class, 'notificationDelete'])->name('delete');
});
