<?php

namespace App\Providers;

use  Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            $this->allApiRoutes();
            $this->allWebRoutes();
        });
    }

    protected function allWebRoutes(): void
    {
        Route::middleware([/*'installed'*/])->group(function () {
            Route::middleware(['web', 'version.update'])
                ->group(base_path('routes/web.php'));

            Route::middleware(['web', 'auth', 'user', 'version.update', 'addon', 'is_email_verify'/*, '2fa_verify'*/, 'common'])
                ->group(base_path('routes/user.php'));

            Route::middleware(['web', 'auth', 'admin',  'version.update', 'addon', 'is_email_verify'/*, '2fa_verify'*/])
                ->prefix('admin')
                ->as('admin.')
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'version.update', 'addon'])
                ->group(base_path('routes/frontend.php'));
        });
    }

    protected function allApiRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
