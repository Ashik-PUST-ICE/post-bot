<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\SettingsService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ResponseTrait;
    public $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
    }

    public function applicationSetting()
    {
        $data['title'] = __('Application Setting');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['activeApplicationSetting'] = 'active';
        $data['subApplicationSettingActiveClass'] = 'active';
        $data['timezones'] = getTimeZone();

        return view('admin.setting.general_settings.application-settings')->with($data);
    }

    public function configurationSetting()
    {
        $data['title'] = __('Configuration Setting');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['showManageApplicationSetting'] = 'show';
        $data['activeConfigurationSetting'] = 'active';

        return view('admin.setting.general_settings.configuration')->with($data);
    }

    public function configurationSettingConfigure(Request $request)
    {
        if ($request->key == 'email_verification_status' || $request->key == 'app_mail_status') {
            return view('admin.setting.general_settings.configuration.form.email_configuration');
        } else if ($request->key == 'app_sms_status') {
            return view('admin.setting.general_settings.configuration.form.sms_configuration');
        } else if ($request->key == 'pusher_status') {
            return view('admin.setting.general_settings.configuration.form.pusher_configuration');
        } else if ($request->key == 'google_login_status') {
            return view('admin.setting.general_settings.configuration.form.social_login_google_configuration');
        } else if ($request->key == 'facebook_login_status') {
            return view('admin.setting.general_settings.configuration.form.social_login_facebook_configuration');
        } else if ($request->key == 'google_recaptcha_status') {
            return view('admin.setting.general_settings.configuration.form.google_recaptcha_configuration');
        } else if ($request->key == 'google_analytics_status') {
            return view('admin.setting.general_settings.configuration.form.google_analytics_configuration');
        } else if ($request->key == 'cookie_status') {
            return view('admin.setting.general_settings.configuration.form.cookie_configuration');
        }
    }

    public function applicationSettingUpdate(Request $request)
    {
        return $this->settingsService->applicationSettingUpdate($request);
    }

    public function configurationSettingUpdate(Request $request)
    {
        return $this->settingsService->configurationSettingUpdate($request);
    }

    public function saveSetting(Request $request)
    {
        return $this->settingsService->saveSetting($request);
    }

    public function logoSettings()
    {
        $data['title'] = __('Logo Setting');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['activeApplicationSetting'] = 'active';
        $data['subLogoSettingActiveClass'] = 'active';

        return view('admin.setting.general_settings.logo-settings')->with($data);
    }

    public function storageSetting()
    {
        $data['title'] = __('Storage Setting');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['activeApplicationSetting'] = 'active';
        $data['subStorageSettingActiveClass'] = 'active';
        $data['timezones'] = getTimeZone();

        return view('admin.setting.general_settings.storage-setting')->with($data);
    }

    public function storageSettingsUpdate(Request $request)
    {
        return $this->settingsService->storageSettingsUpdate($request);
    }

    public function mailConfiguration()
    {
        $data['title'] = __('Mail Configuration');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subMailConfigurationActiveClass'] = 'active';
        return view('admin.setting.general_settings.mail-configuration', $data);
    }

    public function mailTest(Request $request)
    {
        return $this->settingsService->mailTest($request);
    }

    public function maintenanceMode()
    {
        $data['title'] = __('Maintenance Mode');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['activeApplicationSetting'] = 'active';
        $data['subMaintenanceModeActiveClass'] = 'active';

        return view('admin.setting.general_settings.maintenance-mode', $data);
    }

    public function maintenanceModeChange(Request $request)
    {
        return $this->settingsService->maintenanceModeChange($request);
    }

    public function cacheSettings()
    {
        $data['title'] = __('Cache Settings');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['activeApplicationSetting'] = 'active';
        $data['subCacheActiveClass'] = 'active';

        return view('admin.setting.cache-settings', $data);
    }

    public function cacheUpdate($id)
    {
        return $this->settingsService->cacheUpdate($id);
    }

    public function cookieSetting()
    {
        $data['title'] = __('Features Settings');
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subCookieActiveClass'] = 'active';
        return view('admin.setting.general_settings.cookie-settings', $data);
    }

    public function cookieSettingUpdated(Request $request)
    {
        return $this->settingsService->cookieSettingUpdated($request);
    }

    public function commonSettingUpdate(Request $request)
    {
        return $this->settingsService->commonSettingUpdate($request);
    }

    public function googleAnalyticsSetting()
    {
        $data['title'] = __('Google Analytics Setting');
        $data['navAPIParentActiveClass'] = 'mm-active';
        $data['subCoogleAnalyticsCompareApiActiveClass'] = 'active';

        return view('admin.setting.general_settings.google_analytics_settings', $data);
    }

    public function securitySettings()
    {
        $data['title'] = 'Security Settings';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subSecurityGatewayActiveClass'] = 'active';
        return view('admin.setting.general_settings.security-settings', $data);
    }
}
