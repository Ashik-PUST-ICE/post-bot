<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        try {
            $res = sendForgotMail($request->email);
            if ($res['status'] == true) {
                return back()->with('success', $res['msg']);
            } else {
                return back()->with('error', $res['msg']);
            }
        } catch (Exception $e) {
            return back()->with('error', __(SOMETHING_WENT_WRONG));
        }
    }

    public function forgetVerifyForm($token, $email)
    {
        $resetPassword = DB::table('password_resets')->where('token', $token)->where('email', $email)->first();
        return view('auth.passwords.reset', compact('token', 'resetPassword'));
    }

    public function forgetVerify(Request $request, $token)
    {
        $user = DB::table('password_resets')
            ->join('users', 'users.email', '=', 'password_resets.email')
            ->where('token', $token)
            ->select('users.email', 'users.name', 'password_resets.otp', 'password_resets.otp_expiry')
            ->first();
        if (!is_null($user)) {
            return view('auth.passwords.reset')->with(['token' => $token, 'email' => $user->email]);
        } else {
            return back()->with('error', __('Email Not Found'));
        }
    }


    public function forgetVerifyResend(Request $request, $token)
    {
        $user = DB::table('password_resets')->join('users', 'users.email', '=', 'password_resets.email')->where('token', $token)->select('users.email', 'users.name', 'password_resets.otp', 'password_resets.otp_expiry')->first();
        if (getOption('email_verification_status', 0) == 1) {
            try {
                if (!($user->otp_expiry >= now())) {
                    $otp = rand(1000, 9999);
                    $otp_expiry = now()->addMinutes(5);
                    DB::table('password_resets')->where('token', $token)->update([
                        'otp' => $otp,
                        'otp_expiry' => $otp_expiry,
                    ]);

                    $user->otp = $otp;
                    $user->otp_expiry = $otp_expiry;


                    return redirect()->route('password.reset.verify_form', $token, $user->email)->with('success', __('We have sent a fresh verify email.'));
                } else {
                    return back()->with('success', __('Already send an email. Please wait a minutes to try another'));
                }
                return back()->with('error', __('Verify Your Rest Password'));
            } catch (Exception $e) {
                return back()->with('error', __(SOMETHING_WENT_WRONG));
            }

            return back();
        } else {
            return back();
        }
    }

    public function updatePassword(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        try {
            $user = DB::table('password_resets')
                ->join('users', 'users.email', '=', 'password_resets.email')
                ->where('token', $token)
                ->select('users.email', 'users.name')
                ->first();

            if (!is_null($user) && $user->email == $request->email) {
                User::where('email', $user->email)
                    ->update([
                        'password' => bcrypt($request->password),
                    ]);

                return redirect()->route('login')->with('success', __('Reset Successfully. Please login with new password'));
            } else {
                throw new Exception(__('Email doesn\'t match'));
            }
        } catch (Exception $e) {
            return back()->with('error', $e);
        }
    }
}
