<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\Batch;
use App\Models\Department;
use App\Models\FileManager;
use App\Models\PassingYear;
use App\Models\RegisterForm;
use App\Models\Tenant;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Traits\ResponseTrait;
use Config;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default, this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, ResponseTrait;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        event(new Registered($user = $this->create($request->all())));

        if (is_null($user) || is_null($user->id)) {
            return back()->with('error', __(SOMETHING_WENT_WRONG));
        }
        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect(route('login'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            "email" => ['required', 'email', 'max:255', 'unique:users'],
            "name" => ['required', 'string', 'max:255'],
            "mobile" => 'bail|required|min:6|unique:users',
            "password" => ['required', 'string', 'min:6', 'confirmed'],
        ];

        if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == STATUS_ACTIVE) {
            $rules['g-recaptcha-response'] = 'required|recaptchav3:register';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data)
    {
        $remember_token = Str::random(64);

        $google2fa = app('pragmarx.google2fa');

        $file = NULL;
        if (isset($data['file']) && !is_null($data['file'])) {
            $new_file = new FileManager();
            $uploaded = $new_file->upload('users', $data['file']);
            $file = $uploaded->id;
        }
        if (getOption('registration_approval') == 1) {
            $status = USER_STATUS_INACTIVE;
        } else {
            $status = USER_STATUS_ACTIVE;
        }

        $password = (!empty($data['password']) && $data['password'] != 0)
            ? $data['password']
            : Str::random(8);

        $newUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'password' => Hash::make($password),
            'remember_token' => $remember_token,
            'status' => $status,
            'verify_token' => str_replace('-', '', Str::uuid()->toString()),
            'google2fa_secret' => $google2fa->generateSecretKey(),
            'email_verification_status' => (!empty(getOption('email_verification_status')) && getOption('email_verification_status') == STATUS_ACTIVE) ? STATUS_PENDING : STATUS_ACTIVE,
            'phone_verification_status' => 0
        ]);

        return $newUser;

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function createCentral(array $data)
    {
        DB::beginTransaction();
        try {

            $remember_token = Str::random(64);

            $google2fa = app('pragmarx.google2fa');

            $newUser = User::create([
                'name' => $data['name'],
                'role' => USER_ROLE_ADMIN,
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
                'remember_token' => $remember_token,
                'status' => STATUS_ACTIVE,
                'verify_token' => str_replace('-', '', Str::uuid()->toString()),
                'google2fa_secret' => $google2fa->generateSecretKey(),
                'email_verification_status' => (!empty(getOption('email_verification_status')) && getOption('email_verification_status') == STATUS_ACTIVE) ? STATUS_PENDING : STATUS_ACTIVE,
                'phone_verification_status' => 0
            ]);

            DB::commit();
            return $newUser;
        } catch (Exception $e) {
            DB::rollBack();
            return new User();
        }

    }
}
