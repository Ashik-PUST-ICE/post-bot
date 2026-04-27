<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SMSConfigRequest;
use App\Http\Services\SettingsService;
use App\Mail\CustomEmailNotify;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Setting;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class SettingController extends Controller
{
    use ResponseTrait;

    public $settingsService;

    public function __construct()
    {
        $this->settingsService = new SettingsService();
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role == USER_ROLE_ADMIN) {
            
                    abort(404);
        
            }
            return $next($request);
        });
    }

    public function applicationSetting()
    {
        $data['title'] = __("Application Setting");
        $data['pageTitle'] = __("Application Setting");
        $data['showManageApplicationSetting'] = 'show';
        $data['activeSetting'] = 'active';
        $data['activeApplicationSetting'] = 'active';
        $data['subApplicationSettingActiveClass'] = 'active';
        $data['timezones'] = getTimeZone();

        return view('sadmin.setting.general_settings.application-settings')->with($data);
    }

    public function configurationSetting()
    {
        $data['title'] = __("Configuration Setting");
        $data['pageTitle'] = __('Configuration Setting');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeConfigurationSetting'] = 'active';

        return view('sadmin.setting.general_settings.configuration')->with($data);
    }

    public function configurationSettingConfigure(Request $request)
    {
        if ($request->key == 'email_verification_status' || $request->key == 'app_mail_status') {
            return view('sadmin.setting.general_settings.configuration.form.email_configuration');
        } else if ($request->key == 'app_sms_status') {
            return view('sadmin.setting.general_settings.configuration.form.sms_configuration');
        } else if ($request->key == 'pusher_status') {
            return view('sadmin.setting.general_settings.configuration.form.pusher_configuration');
        } else if ($request->key == 'google_login_status') {
            return view('sadmin.setting.general_settings.configuration.form.social_login_google_configuration');
        } else if ($request->key == 'facebook_login_status') {
            return view('sadmin.setting.general_settings.configuration.form.social_login_facebook_configuration');
        } else if ($request->key == 'google_recaptcha_status') {
            return view('sadmin.setting.general_settings.configuration.form.google_recaptcha_configuration');
        } else if ($request->key == 'google_analytics_status') {
            return view('sadmin.setting.general_settings.configuration.form.google_analytics_configuration');
        } else if ($request->key == 'cookie_status') {
            return view('sadmin.setting.general_settings.configuration.form.cookie_configuration');
        }
    }

    public function configurationSettingHelp(Request $request)
    {
        if ($request->key == 'email_verification_status' || $request->key == 'app_mail_status') {
            return view('sadmin.setting.general_settings.configuration.help.email_help');
        } else if ($request->key == 'app_sms_status') {
            return view('admin.setting.general_settings.configuration.help.sms_help');
        } else if ($request->key == 'pusher_status') {
            return view('admin.setting.general_settings.configuration.help.pusher_help');
        } else if ($request->key == 'google_login_status') {
            return view('admin.setting.general_settings.configuration.help.social_login_google_help');
        } else if ($request->key == 'facebook_login_status') {
            return view('admin.setting.general_settings.configuration.help.social_login_facebook_help');
        } else if ($request->key == 'google_recaptcha_status') {
            return view('admin.setting.general_settings.configuration.help.google_recaptcha_credentials_help');
        } else if ($request->key == 'google_analytics_status') {
            return view('sadmin.setting.general_settings.configuration.help.google_analytics_help');
        } else if ($request->key == 'cookie_status') {
            return view('sadmin.setting.general_settings.configuration.help.cookie_consent_help');
        } else if ($request->key == 'referral_status') {
            return view('admin.setting.general_settings.configuration.help.referral_help');
        } else if ($request->key == 'two_factor_googleauth_status') {
            return view('admin.setting.general_settings.configuration.help.google_2fa_help');
        } else if ($request->key == 'app_preloader_status') {
            return view('sadmin.setting.general_settings.configuration.help.preloader_help');
        } else if ($request->key == 'disable_registration') {
            return view('admin.setting.general_settings.configuration.help.disable_registration_help');
        } else if ($request->key == 'registration_approval') {
            return view('admin.setting.general_settings.configuration.help.registration_approval_help');
        } else if ($request->key == 'force_secure_password') {
            return view('admin.setting.general_settings.configuration.help.force_secure_password_help');
        } else if ($request->key == 'show_agree_policy') {
            return view('admin.setting.general_settings.configuration.help.agree_policy_help');
        } else if ($request->key == 'enable_force_ssl') {
            return view('admin.setting.general_settings.configuration.help.enable_force_SSL_help');
        } else if ($request->key == 'enable_dark_mode') {
            return view('admin.setting.general_settings.configuration.help.enable_dark_mode_help');
        } else if ($request->key == 'show_language_switcher') {
            return view('sadmin.setting.general_settings.configuration.help.show_language_switcher_help');
        } else if ($request->key == 'register_file_required') {
            return view('admin.setting.general_settings.configuration.help.register_file_required_help');
        } else if ($request->key == 'app_debug') {
            return view('sadmin.setting.general_settings.configuration.help.app_debug_help');
        }
    }

    public function applicationSettingUpdate(Request $request)
    {
        $inputs = Arr::except($request->all(), ['_token']);

        foreach ($inputs as $key => $value) {

            $option = Setting::firstOrCreate(['option_key' => $key]);

            if ($request->hasFile('app_preloader') && $key == 'app_preloader') {
                $upload = settingImageStoreUpdate($value, $request->app_preloader);
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('app_logo') && $key == 'app_logo') {
                $upload = settingImageStoreUpdate($value, $request->app_logo);
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('app_fav_icon') && $key == 'app_fav_icon') {
                $upload = settingImageStoreUpdate($value, $request->app_fav_icon);
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('app_logo_white') && $key == 'app_logo_white') {
                $upload = settingImageStoreUpdate($value, $request->app_logo_white);
                $option->option_value = $upload;
                $option->save();
            } elseif ($request->hasFile('login_left_image') && $key == 'login_left_image') {
                $upload = settingImageStoreUpdate($value, $request->login_left_image);
                $option->option_value = $upload;
                $option->save();
            } else {
                $option->option_value = $value;
                $option->save();
            }
        }
        /**  ====== Set Currency ====== */
        if ($request->currency_id) {
            Currency::where('id', $request->currency_id)->update(['current_currency' => 1]);
            Currency::where('id', '!=', $request->currency_id)->update(['current_currency' => 0]);
        }
        /**  ====== Set Language ====== */
        if ($request->language_id) {
            Language::where('id', $request->language_id)->update(['default' => STATUS_ACTIVE]);
            Language::where('id', '!=', $request->language_id)->update(['default' => STATUS_DEACTIVATE]);
            $language = Language::where('default', STATUS_ACTIVE)->first();
            if ($language) {
                $ln = $language->iso_code;
                session(['local' => $ln]);
                App::setLocale(session()->get('local'));
            }
        }
        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function configurationSettingUpdate(Request $request)
    {
        try {
            $option = Setting::firstOrCreate(['option_key' => $request->key]);
            $option->option_value = $request->value;
            $option->save();
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        } catch (Exception $e) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        }
    }

    public function storageSetting()
    {
        $data['title'] = __("Storage Setting");
        $data['pageTitle'] = __("Storage Setting");
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subStorageSettingActiveClass'] = 'active';
        $data['timezones'] = getTimeZone();

        return view('sadmin.setting.general_settings.storage-setting')->with($data);
    }

    public function storageSettingsUpdate(Request $request)
    {
        if ($request->STORAGE_DRIVER == STORAGE_DRIVER_AWS) {
            $values = $request->validate([
                'AWS_ACCESS_KEY_ID' => 'bail|required',
                'AWS_SECRET_ACCESS_KEY' => 'bail|required',
                'AWS_DEFAULT_REGION' => 'bail|required',
                'AWS_BUCKET' => 'bail|required',
            ]);
        } elseif ($request->STORAGE_DRIVER == STORAGE_DRIVER_WASABI) {
            $values = $request->validate([
                'WASABI_ACCESS_KEY_ID' => 'bail|required',
                'WASABI_SECRET_ACCESS_KEY' => 'bail|required',
                'WASABI_DEFAULT_REGION' => 'bail|required',
                'WASABI_BUCKET' => 'bail|required',
            ]);
        } elseif ($request->STORAGE_DRIVER == STORAGE_DRIVER_VULTR) {
            $values = $request->validate([
                'VULTR_ACCESS_KEY_ID' => 'bail|required',
                'VULTR_SECRET_ACCESS_KEY' => 'bail|required',
                'VULTR_DEFAULT_REGION' => 'bail|required',
                'VULTR_BUCKET' => 'bail|required',
            ]);
        } elseif ($request->STORAGE_DRIVER == STORAGE_DRIVER_DO) {
            $values = $request->validate([
                'DO_ACCESS_KEY_ID' => 'bail|required',
                'DO_SECRET_ACCESS_KEY' => 'bail|required',
                'DO_DEFAULT_REGION' => 'bail|required',
                'DO_BUCKET' => 'bail|required',
                'DO_FOLDER' => 'bail|required',
                'DO_CDN_ID' => 'bail|required',
            ]);
        }
        $values['STORAGE_DRIVER'] = $request->STORAGE_DRIVER;
        if (!updateEnv($values)) {
            return $this->error([], getMessage(SOMETHING_WENT_WRONG));
        } else {
            Artisan::call('optimize:clear');
            $this->updateSettings($values);
            return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
        }
    }

    private function updateSettings($inputs)
    {
        $keys = [];
        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }
        foreach ($inputs as $key => $value) {

            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
            setEnvironmentValue($key, $value);
        }
    }

    public function socialLoginSetting()
    {
        $data['title'] = __("Social Login Setting");
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subSocialLoginSettingActiveClass'] = 'active';

        return view('sadmin.setting.general_settings.social-login-settings')->with($data);
    }

    public function logoSettings()
    {
        $data['title'] = __("Logo Setting");
        $data['pageTitle'] = __("Logo Setting");
        $data['showManageApplicationSetting'] = 'show';
        $data['activeSetting'] = 'active';
        $data['activeApplicationSetting'] = 'active';
        $data['subLogoSettingActiveClass'] = 'active';

        return view('sadmin.setting.general_settings.logo-settings')->with($data);
    }

    public function googleRecaptchaSetting()
    {
        $data['title'] = __("Google Recaptcha Setting");
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subGoogleRecaptchaSettingActiveClass'] = 'active';
        return view('sadmin.setting.general_settings.google-recaptcha-settings')->with($data);
    }

    public function mailConfiguration()
    {
        $data['title'] = __('Mail Configuration');
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subMailConfigurationActiveClass'] = 'active';
        return view('sadmin.setting.general_settings.mail-configuration', $data);
    }

    public function mailTest(Request $request)
    {
        try {
            Mail::to($request->to)->send(new CustomEmailNotify($request->subject, $request->message, ''));
            return redirect()->back()->with('success', __(SENT_SUCCESSFULLY));
        } catch (\PharIo\Manifest\Exception $exception) {
            return redirect()->back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }

    public function maintenanceMode()
    {
        $data['title'] = __("Maintenance Mode Settings");
        $data['pageTitle'] = __("Maintenance Mode Settings");
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subMaintenanceModeActiveClass'] = 'active';

        return view('sadmin.setting.general_settings.maintenance-mode', $data);
    }

    public function maintenanceModeChange(Request $request)
    {
        if ($request->maintenance_mode == 1) {
            $request->validate(
                [
                    'maintenance_mode' => 'required',
                    'maintenance_secret_key' => 'required|min:6'
                ],
                [
                    'maintenance_secret_key.required' => 'The maintenance mode secret key is required.',
                ]
            );
        } else {
            $request->validate([
                'maintenance_mode' => 'required',
            ]);
        }

        $inputs = Arr::except($request->all(), ['_token']);
        $keys = [];

        foreach ($inputs as $k => $v) {
            $keys[$k] = $k;
        }

        foreach ($inputs as $key => $value) {
            $option = Setting::firstOrCreate(['option_key' => $key]);
            $option->option_value = $value;
            $option->save();
        }

        if ($request->maintenance_mode == 1) {
            Artisan::call('up');
            $secret_key = 'down --secret="' . $request->maintenance_secret_key . '"';
            Artisan::call($secret_key);
        } else {
            $option = Setting::firstOrCreate(['option_key' => 'maintenance_secret_key']);
            $option->option_value = null;
            $option->save();
            Artisan::call('up');
        }
        return $this->success([], __("'Maintenance Mode Has Been Changed'"));
    }

    public function saveSetting(Request $request)
    {
        $inputs = Arr::except($request->all(), ['_token']);
        $this->updateSettings($inputs);
        return $this->success([], getMessage(UPDATED_SUCCESSFULLY));
    }

    public function contactUsCMS()
    {
        $data['title'] = 'Contact Us CMS';
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subContactUsCMSSettingActiveClass'] = 'mm-active';
        $data['subContactUsCMSActiveClass'] = 'active';
        return view('sadmin.setting.contact-us', $data);
    }

    public function homeSettings()
    {
        $data['title'] = 'Home Setting';
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subHomeSettingActiveClass'] = 'mm-active';
        $data['subHomeActiveClass'] = 'active';
        return view('sadmin.setting.home.home-settings', $data);
    }

    public function beAContributor()
    {
        $data['title'] = 'Be A Contributor CMS';
        $data['navSettingParentActiveClass'] = 'mm-active';
        $data['subNavBeAContributorActiveClass'] = 'active';
        return view('sadmin.setting.be-a-contributor')->with($data);
    }

    public function cacheSettings()
    {
        $data['title'] = __('Cache Settings');
        $data['pageTitle'] = __('Cache Settings');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subCacheActiveClass'] = 'active';

        return view('sadmin.setting.cache-settings', $data);
    }

    public function cacheUpdate($id)
    {
        if ($id == 1) {
            Artisan::call('view:clear');
            return redirect()->back()->with('success', 'Views cache cleared successfully');
        } elseif ($id == 2) {
            Artisan::call('route:clear');
            return redirect()->back()->with('success', 'Route cache cleared successfully');
        } elseif ($id == 3) {
            Artisan::call('config:clear');
            return redirect()->back()->with('success', 'Configuration cache cleared successfully');
        } elseif ($id == 4) {
            Artisan::call('cache:clear');
            return redirect()->back()->with('success', 'Application cache cleared successfully');
        } elseif ($id == 5) {
            try {
                $dirname = public_path("storage");
                if (is_dir($dirname)) {
                    rmdir($dirname);
                }

                Artisan::call('storage:link');
                return redirect()->back()->with('success', 'Application Storage Linked successfully');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        }
        return redirect()->back();
    }

    public function storageLink()
    {
        try {
            if (file_exists(public_path('storage'))) {
                Artisan::call('storage:link');
                return redirect()->back()->with('success', 'Created Storage Link Updated Successfully');
            } else {
                Artisan::call('storage:link');
            }
            return redirect()->back()->with('success', 'Created Storage Link Updated Successfully');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function cookieSetting()
    {
        $data['title'] = __('Features Settings');
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subCookieActiveClass'] = 'active';
        return view('sadmin.setting.general_settings.cookie-settings', $data);
    }

    public function commonSettingUpdate(Request $request)
    {
        return $this->settingsService->commonSettingUpdate($request);
    }

    public function cookieSettingUpdated(Request $request)
    {
        return $this->settingsService->cookieSettingUpdated($request);
    }

    public function googleAnalyticsSetting()
    {
        $data['title'] = 'Api Settings';
        $data['navAPIParentActiveClass'] = 'mm-active';
        $data['subCoogleAnalyticsCompareApiActiveClass'] = 'active';

        return view('sadmin.setting.general_settings.google_analytics_settings', $data);
    }

    public function securitySettings()
    {
        $data['title'] = 'Security Settings';
        $data['subNavGeneralSettingActiveClass'] = 'mm-active';
        $data['subSecurityGatewayActiveClass'] = 'active';
        return view('sadmin.setting.general_settings.security-settings', $data);
    }

    public function customCSS()
    {
        $data['title'] = __('Custom CSS');
        $data['showManageApplicationSetting'] = 'show';
        $data['activeApplicationSetting'] = 'active';
        $data['subCustomCssActiveClass'] = 'active-color-one';
        $data['custom_css'] = getOption('custom_css');
        return view('sadmin.setting.general_settings.custom-css', $data);
    }
    public function termsAndCondition()
    {
        $data['title'] = __('Terms and Condition');
        $data['activeFrontendList'] = 'active';
        $data['tcActiveClass'] = 'active';
        $data['t_and_c'] = Setting::where('option_key', 'LIKE', 't_and_c%')->get();
        return view('sadmin.setting.general_settings.terms_and_condition', $data);
    }
    public function privacyPolicy()
    {
        $data['title'] = __('Privacy Policy');
        $data['activeFrontendList'] = 'active';
        $data['ppActiveClass'] = 'active';
        $data['privacy_policy'] = Setting::where('option_key', 'LIKE', 'privacy_policy%')->get();
        return view('sadmin.setting.general_settings.privacy_policy', $data);
    }
    public function returnPolicy()
    {
        $data['title'] = __('Return Policy');
        $data['activeFrontendList'] = 'active';
        $data['rpActiveClass'] = 'active';
        $data['return_policy'] = Setting::where('option_key', 'LIKE', 'return_policy%')->get();
        return view('sadmin.setting.general_settings.return_policy', $data);
    }
}
