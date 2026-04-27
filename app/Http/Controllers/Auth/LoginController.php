<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        Session::put('2fa_status', false);

        $field = 'email';

        $request->merge([$field => $request->input('email')]);

        $credentials = $request->only($field, 'password');

        $remember = request('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return redirect("login")->withInput()->with('error', __('Email or password is incorrect'));
        }

        $user = User::where('email', $request->email)->first();

        if ($user->email_verification_status == STATUS_ACTIVE) {

            if ($user->status == STATUS_SUSPENDED) {
                Auth::logout();
                return redirect("login")->withInput()->with('error', __('Your account is suspended Please contact our support center'));
            } elseif ($user->deleted_at != null) {
                Auth::logout();
                return redirect("login")->withInput()->with('error', __('Your account has been deleted'));
            }

            if (isset($user) && ($user->status == STATUS_PENDING)) {
                Auth::logout();
                return redirect("login")->with('error', __('Your account is under approval. Please wait for approval'));
            } elseif(isset($user) && ($user->status == STATUS_REJECT)) {
                Auth::logout();
                return redirect("login")->withInput()->with('error', __('Your account is inactive. Please contact with admin'));
            } 

        }

        if (getOption('email_verification_status', 0) == 1) {
            if (is_null($user->verify_token)) {
                $user->verify_token = str_replace('-', '', Str::uuid()->toString());
                $user->save();
            }
            $otpStillValid = $user->otp_expiry && $user->otp_expiry >= now();
            if (!$otpStillValid) {
                $user->otp = rand(1000, 9999);
                $user->otp_expiry = now()->addMinutes(5);
                $user->save();
                $customData = (object)['otp' => $user->otp];
                sendCommonEmailNotification('email-verify', [$user->id], $customData, '');

                return redirect()->route('email.verify', $user->verify_token)
                    ->with('success', __('We have sent a verification code to your email.'));
            }

            return redirect()->route('email.verify', $user->verify_token)
                ->with('success', __('Please enter the verification code sent to your email.'));
        }

        // Role-based redirect after successful login
        if ($user->role == USER_ROLE_SUPER_ADMIN) {
            return redirect()->route('super-admin.dashboard');
        } elseif ($user->role == USER_ROLE_ADMIN) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Validate the user login request.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $rules = [
            $this->username() => 'required|string',
            'password'        => 'required|string',
        ];

        if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == 1) {
            $rules['g-recaptcha-response'] = ['required', 'recaptchav3:register,0.5'];
        }

        $request->validate($rules);
    }
}
