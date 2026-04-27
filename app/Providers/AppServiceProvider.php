<?php

namespace App\Providers;

use App\Models\CommitteeCategory;
use App\Models\Language;
use App\Models\Setting;
use Illuminate\Database\Schema\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::defaultStringLength(191);
        Paginator::defaultView('frontend.pagination.custom');
        try {
            $connection = DB::connection()->getPdo();
            if ($connection) {
                $allOptions = [];

                $allOptions['settings'] = Setting::all()->pluck('option_value', 'option_key')->toArray();

                config($allOptions);

                $language = Language::where('iso_code', session()->get('local'))->first();

                if (!$language) {
                    $language = Language::find(1);
                    if ($language) {
                        $ln = $language->iso_code;
                        session(['local' => $ln]);
                        App::setLocale(session()->get('local'));
                    }
                } else {
                    $language = Language::where('default', ACTIVE)->first();
                    if ($language) {
                        $ln = $language->iso_code;
                        session(['local' => $ln]);
                        App::setLocale(session()->get('local'));
                    }
                }
                config(['app.defaultLanguage' => getDefaultLanguage()]);
                config(['app.currencySymbol' => getCurrencySymbol()]);
                config(['app.isoCode' => getIsoCode()]);
                config(['app.currencyPlacement' => getCurrencyPlacement()]);
                //config(['app.debug' => getOption('app_debug', false)]);
                config(['app.timezone' => getOption('app_timezone', 'UTC')]);

                config(['services.google.client_id' => getOption('google_client_id')]);
                config(['services.google.client_secret' => getOption('google_client_secret')]);
                config(['services.google.redirect' => url('auth/google/callback')]);

                config(['services.facebook.client_id' => getOption('facebook_client_id')]);
                config(['services.facebook.client_secret' => getOption('facebook_client_secret')]);
                config(['services.facebook.redirect' => url('auth/facebook/callback')]);
                if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1) {
                    config(['recaptchav3.sitekey' => getOption('google_recaptcha_site_key')]);
                    config(['recaptchav3.secret' => getOption('google_recaptcha_secret_key')]);
                }

                if (getOption('pusher_status', 0)) {
                    config(['broadcasting.connections.pusher.key' => getOption('pusher_app_key', 'null')]);
                    config(['broadcasting.connections.pusher.secret' => getOption('pusher_app_secret', 'null')]);
                    config(['broadcasting.connections.pusher.app_id' => getOption('pusher_app_id', 'null')]);
                    config(['broadcasting.connections.pusher.options.cluster' => getOption('pusher_cluster', 'null')]);
                    config(['broadcasting.default' => 'pusher']);
                } else {
                    config(['broadcasting.default' => 'null']);
                }

                date_default_timezone_set(getOption('app_timezone', 'UTC'));

                Gate::before(function ($user, $ability) {
                    return (($user->is_alumni && $user->role == USER_ROLE_USER) | (is_null($user->created_by) && $user->role == USER_ROLE_ADMIN) ) ? true : null;
                });
                if (getOption('force_ssl', 0)){
                    URL::forceScheme('https');
                }
            }
        } catch (\Exception $e) {
            Log::info('Service Provider - ' . $e->getMessage());
        }
    }
}
