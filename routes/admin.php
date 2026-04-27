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
    Route::post('destroy/{id}', [RolePermisionController::class, 'destroy'])->name('destroy');
    Route::get('permissions/{id}', [RolePermisionController::class, 'permissions'])->name('permissions');
    Route::post('permissions/{id}', [RolePermisionController::class, 'updatePermissions'])->name('update.permissions');
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
    Route::group(['middleware' => []], function () {
        Route::get('application-settings', [SettingController::class, 'applicationSetting'])->middleware('can:Manage Application Setting')->name('application-settings');
        Route::get('configuration-settings', [SettingController::class, 'configurationSetting'])->middleware('can:Manage Application Setting')->name('configuration-settings');
        Route::get('configuration-settings/configure', [SettingController::class, 'configurationSettingConfigure'])->middleware('can:Manage Application Setting')->name('configuration-settings.configure');
        Route::get('configuration-settings/help', [SettingController::class, 'configurationSettingHelp'])->middleware('can:Manage Application Setting')->name('configuration-settings.help');
        Route::post('application-settings-update', [SettingController::class, 'applicationSettingUpdate'])->middleware('can:Manage Application Setting')->name('application-settings.update')->middleware('isDemo');;
        Route::post('configuration-settings-update', [SettingController::class, 'configurationSettingUpdate'])->middleware('can:Manage Application Setting')->name('configuration-settings.update')->middleware('isDemo');;
        Route::post('application-env-update', [SettingController::class, 'saveSetting'])->middleware('can:Manage Application Setting')->name('settings_env.update');
        Route::get('logo-settings', [SettingController::class, 'logoSettings'])->middleware('can:Manage Application Setting')->name('logo-settings');
        Route::get('color-settings', [SettingController::class, 'colorSettings'])->middleware('can:Manage Application Setting')->name('color-settings');

        Route::group(['prefix' => 'currency', 'as' => 'currencies.', 'middleware' => ['can:Manage Application Setting']], function () {
            Route::get('', [CurrencyController::class, 'index'])->name('index');
            Route::post('currency', [CurrencyController::class, 'store'])->name('store');
            Route::get('edit/{id}', [CurrencyController::class, 'edit'])->name('edit');
            Route::patch('update/{id}', [CurrencyController::class, 'update'])->name('update');
            Route::post('delete/{id}', [CurrencyController::class, 'delete'])->name('delete');
        });

        Route::get('storage-settings', [SettingController::class, 'storageSetting'])->middleware('can:Manage Application Setting')->name('storage.index');
        Route::post('storage-settings', [SettingController::class, 'storageSettingsUpdate'])->middleware('can:Manage Application Setting')->name('storage.update');
        Route::get('google-recaptcha-settings', [SettingController::class, 'googleRecaptchaSetting'])->middleware('can:Manage Application Setting')->name('google-recaptcha');
        Route::get('google-analytics-settings', [SettingController::class, 'googleAnalyticsSetting'])->middleware('can:Manage Application Setting')->name('google.analytics');
    });

    Route::get('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-configuration', [SettingController::class, 'mailConfiguration'])->name('mail-configuration');
    Route::post('mail-test', [SettingController::class, 'mailTest'])->name('mail.test');

    Route::get('sms-configuration', [SettingController::class, 'smsConfiguration'])->name('sms-configuration');
    Route::post('sms-configuration', [SettingController::class, 'smsConfigurationStore'])->name('sms-configuration');
    Route::post('sms-test', [SettingController::class, 'smsTest'])->name('sms.test');


    //Start:: Maintenance Mode
    Route::get('maintenance-mode-changes', [SettingController::class, 'maintenanceMode'])->name('maintenance');
    Route::post('maintenance-mode-changes', [SettingController::class, 'maintenanceModeChange'])->name('maintenance.change')->middleware('isDemo');
    //End:: Maintenance Mode

    Route::get('cache-settings', [SettingController::class, 'cacheSettings'])->name('cache-settings');
    Route::get('cache-update/{id}', [SettingController::class, 'cacheUpdate'])->name('cache-update');
    Route::get('storage-link', [SettingController::class, 'storageLink'])->name('storage.link');
    Route::get('security-settings', [SettingController::class, 'securitySettings'])->name('security.settings');

    Route::group(['prefix' => 'gateway', 'as' => 'gateway.', 'middleware' => ['can:Manage Application Setting']], function () {
        Route::get('/', [GatewayController::class, 'index'])->name('index');
        Route::post('store', [GatewayController::class, 'store'])->name('store')->middleware('isDemo');
        Route::get('get-info', [GatewayController::class, 'getInfo'])->name('get.info');
        Route::get('get-currency-by-gateway', [GatewayController::class, 'getCurrencyByGateway'])->name('get.currency');
        Route::get('syncs', [GatewayController::class, 'syncs'])->name('syncs');
    });

    //Features Settings
    Route::get('cookie-settings', [SettingController::class, 'cookieSetting'])->name('cookie-settings');
    Route::post('cookie-settings-update', [SettingController::class, 'cookieSettingUpdated'])->name('cookie.settings.update');
    Route::get('live-chat-settings', [SettingController::class, 'liveChatSettings'])->name('live.chat.settings');

    //common setting update
    Route::post('common-settings-update', [SettingController::class, 'commonSettingUpdate'])->name('common.settings.update')->middleware('isDemo');

    Route::group(['prefix' => 'language', 'as' => 'languages.', 'middleware' => ['can:Manage Application Setting']], function () {
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
