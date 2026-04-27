<?php

use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\GatewayController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\RolePermisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AddonUpdateController;
use App\Http\Controllers\VersionUpdateController;
use App\Models\Language;
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

Route::get('/local/{ln}', function ($ln) {
    $language = Language::where('iso_code', $ln)->first();
    if (!$language) {
        $language = Language::where('default', 1)->first();
        if ($language) {
            $ln = $language->iso_code;
        }
    }
    session()->put('local', $ln);
    return redirect()->back();
})->name('local');



Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('user-overview-chart-data', [DashboardController::class, 'userOverviewChartData'])->name('user-overview-chart-data');
Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::group(['middleware' => []], function () {
        Route::get('application-settings', [SettingController::class, 'applicationSetting'])->name('application-settings');
        Route::get('configuration-settings', [SettingController::class, 'configurationSetting'])->name('configuration-settings');
        Route::get('configuration-settings/configure', [SettingController::class, 'configurationSettingConfigure'])->name('configuration-settings.configure');
        Route::get('configuration-settings/help', [SettingController::class, 'configurationSettingHelp'])->name('configuration-settings.help');
        Route::post('application-settings-update', [SettingController::class, 'applicationSettingUpdate'])->name('application-settings.update');
        Route::post('configuration-settings-update', [SettingController::class, 'configurationSettingUpdate'])->name('configuration-settings.update');
        Route::post('application-env-update', [SettingController::class, 'saveSetting'])->name('settings_env.update');
        Route::get('logo-settings', [SettingController::class, 'logoSettings'])->name('logo-settings');

        Route::group(['prefix' => 'currency', 'as' => 'currencies.'], function () {
            Route::get('', [CurrencyController::class, 'index'])->name('index');
            Route::post('currency', [CurrencyController::class, 'store'])->name('store');
            Route::get('edit/{id}', [CurrencyController::class, 'edit'])->name('edit');
            Route::patch('update/{id}', [CurrencyController::class, 'update'])->name('update');
            Route::post('delete/{id}', [CurrencyController::class, 'delete'])->name('delete');
        });
        Route::group(['prefix' => 'language', 'as' => 'languages.'], function () {
            Route::get('/', [LanguageController::class, 'index'])->name('index');
            Route::post('store', [LanguageController::class, 'store'])->name('store');
            Route::get('edit/{id}/{iso_code?}', [LanguageController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [LanguageController::class, 'update'])->name('update');
            Route::get('translate/{id}', [LanguageController::class, 'translateLanguage'])->name('translate');
            Route::post('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
            Route::post('delete/{id}', [LanguageController::class, 'delete'])->name('delete');
            Route::post('update-language/{id}', [LanguageController::class, 'updateLanguage'])->name('update-language');
            Route::get('translate/{id}/{iso_code?}', [LanguageController::class, 'translateLanguage'])->name('translate');
            Route::get('update-translate/{id}', [LanguageController::class, 'updateTranslate'])->name('update.translate');
            Route::post('import', [LanguageController::class, 'import'])->name('import')->middleware('isDemo');
            Route::get('download/{id}', [LanguageController::class, 'download'])->name('download');
            Route::post('upload/{id}', [LanguageController::class, 'upload'])->name('upload');
        });
        Route::get('storage-settings', [SettingController::class, 'storageSetting'])->name('storage.index');
        Route::post('storage-settings', [SettingController::class, 'storageSettingsUpdate'])->name('storage.update');
        Route::get('social-login-settings', [SettingController::class, 'socialLoginSetting'])->name('social-login');
        Route::get('google-recaptcha-settings', [SettingController::class, 'googleRecaptchaSetting'])->name('google-recaptcha');
        Route::get('google-analytics-settings', [SettingController::class, 'googleAnalyticsSetting'])->name('google.analytics');

        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('create', [UserController::class, 'create'])->name('create');
            Route::post('store', [UserController::class, 'store'])->name('store')->middleware('isDemo');
            Route::get('edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::post('update/{id}', [UserController::class, 'update'])->name('update')->middleware('isDemo');
            Route::get('delete/{id}', [UserController::class, 'delete'])->name('delete')->middleware('isDemo');
        });



    Route::get('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-test', [SettingController::class, 'mailTest'])->name('mail.test');


    //Start:: Maintenance Mode
    Route::get('maintenance-mode-changes', [SettingController::class, 'maintenanceMode'])->name('maintenance');
    Route::post('maintenance-mode-changes', [SettingController::class, 'maintenanceModeChange'])->name('maintenance.change')->middleware('isDemo');
    //End:: Maintenance Mode

    Route::get('cache-settings', [SettingController::class, 'cacheSettings'])->name('cache-settings');
    Route::get('cache-update/{id}', [SettingController::class, 'cacheUpdate'])->name('cache-update');
    Route::get('storage-link', [SettingController::class, 'storageLink'])->name('storage.link');
    Route::get('security-settings', [SettingController::class, 'securitySettings'])->name('security.settings');

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.'], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::get('syncs', [GatewayController::class, 'syncs'])->name('syncs');
    });

    //Features Settings
    Route::get('cookie-settings', [SettingController::class, 'cookieSetting'])->name('cookie-settings');
    Route::post('cookie-settings-update', [SettingController::class, 'cookieSettingUpdated'])->name('cookie.settings.update');


    //common setting update
    Route::post('common-settings-update', [SettingController::class, 'commonSettingUpdate'])->name('common.settings.update')->middleware('isDemo');

    Route::get('email-template', [EmailTemplateController::class, 'emailTemplate'])->name('email-template');
    Route::get('email-template-config', [EmailTemplateController::class, 'emailTemplateConfig'])->name('email.template.config');
    Route::post('email-template-config-update', [EmailTemplateController::class, 'emailTemplateConfigUpdate'])->name('email.template.config.update');
    Route::get('preview-test-mail/{id}', [EmailTemplateController::class, 'previewMailTest'])->name('preview-test-mail');
    Route::post('send-test-mail/{id}', [EmailTemplateController::class, 'sendTestMail'])->name('send-test-mail');

    // notify tamp
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
});

Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
    Route::get('/', [RolePermisionController::class, 'list'])->name('index');
    Route::get('add-new', [RolePermisionController::class, 'addNew'])->name('add-new');
    Route::post('store', [RolePermisionController::class, 'store'])->name('store');
    Route::get('edit/{id}', [RolePermisionController::class, 'edit'])->name('edit');
    Route::post('destroy/{id}', [RolePermisionController::class, 'delete'])->name('destroy');
    Route::get('permission/{id}', [RolePermisionController::class, 'permission'])->name('permission');
    Route::post('permission-update', [RolePermisionController::class, 'permissionUpdate'])->name('permission-update');
});


//users
Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('list', [UserController::class, 'userList'])->name('list');
    Route::get('add-new', [UserController::class, 'userAdd'])->name('add-new');
    Route::post('store', [UserController::class, 'store'])->name('store');
    Route::get('details-{id}', [UserController::class, 'userDetails'])->name('details');
    Route::get('edit-{id}', [UserController::class, 'edit'])->name('edit');
    Route::post('update-{id}', [UserController::class, 'update'])->name('update')->middleware('isDemo');
    Route::get('suspend-{id}', [UserController::class, 'userSuspend'])->name('suspend');
    Route::post('delete-{id}', [UserController::class, 'userDelete'])->name('delete');
    Route::get('activity-{id}', [UserController::class, 'userActivity'])->name('activity');
});

Route::group(['prefix' => 'notification', 'as' => 'notification.'], function () {
    Route::get('notification-mark-all-as-read', [NotificationController::class, 'notificationMarkAllAsRead'])->name('notification-mark-all-as-read');
    Route::get('view/{id}', [NotificationController::class, 'notificationView'])->name('view');
    Route::get('notification-mark-as-read/{id}', [NotificationController::class, 'notificationMarkAsRead'])->name('notification-mark-as-read');
});


Route::get('version-update', [VersionUpdateController::class, 'versionFileUpdate'])->name('file-version-update');
Route::post('version-update', [VersionUpdateController::class, 'versionFileUpdateStore'])->name('file-version-update-store');
Route::get('version-update-execute', [VersionUpdateController::class, 'versionUpdateExecute'])->name('file-version-update-execute');
Route::get('version-delete', [VersionUpdateController::class, 'versionFileUpdateDelete'])->name('file-version-delete');

