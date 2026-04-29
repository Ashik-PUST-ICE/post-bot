<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function registered(\Illuminate\Http\Request $request, $user)
    {
        if ($request->filled('package')) {
            return redirect()->route('admin.subscription.index', ['id' => $request->package]);
        }
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
            "password" => ['required', 'string', 'min:6'],
        ];

        if (getOption('register_file_required', 0)) {
            $rules['file'] = ['bail', 'required', 'mimetypes:application/pdf'];
        }

        if (!empty(getOption('google_recaptcha_status')) && getOption('google_recaptcha_status') == STATUS_ACTIVE) {
            $rules['g-recaptcha-response'] = 'required|recaptchav3:register';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $remember_token = Str::random(64);

        $google2fa = app('pragmarx.google2fa');

        $adminUser = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => USER_ROLE_ADMIN,
            'tenant_id' => makeTenantId(),
            'remember_token' => $remember_token,
            'status' => USER_STATUS_ACTIVE,
            'verify_token' => str_replace('-', '', Str::uuid()->toString()),
            'google2fa_secret' => $google2fa->generateSecretKey(),
        ]);

        // set admin gateway
        setUserGateway($adminUser->tenant_id, $adminUser->id);

        // set admin email template
        setUserEmailTemplate($adminUser->tenant_id, $adminUser->id);

        setDefaultPermission($adminUser->tenant_id, $adminUser->id);

        // set admin notify template
        setUserNotifyTemplate($adminUser->tenant_id, $adminUser->id);

        // set default package
        $duration = (int)getOption('trail_duration', 1);
        $defaultPackage = Package::where(['is_trail' => ACTIVE])->first();
        if ($defaultPackage) {
            setUserPackage($adminUser->id, $defaultPackage, $duration);
        }

        $role = Role::where('name', 'Admin')->first();
        $role->syncPermissions(permissionArray());
        $adminUser->syncRoles($role->id);
        return $adminUser;
    }
}
