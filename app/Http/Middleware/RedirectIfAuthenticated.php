<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response|RedirectResponse
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if (Auth::guard($guard)->check()) {
                if (Auth::user()->role == USER_ROLE_ADMIN) {
                    return redirect(route('admin.dashboard'));
                } else if (Auth::user()->role == USER_ROLE_USER) {
                    return redirect(route('home'));
                } else {
                    Auth::logout();
                    return redirect("login")->with('error', __('Invalid user'));
                }
            }
        }

        return $next($request);
    }
}
