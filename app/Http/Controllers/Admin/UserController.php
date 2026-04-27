<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $data['title'] = __('Users');
        $data['showManageModerator'] = 'show';
        $data['activeUsers'] = 'active';
        $data['users'] = User::where('tenant_id', auth()->user()->tenant_id)->where('role', USER_ROLE_USER)->with('roles')->orderBy('id', 'DESC')->get();
        $data['roles'] = Role::where('status', STATUS_ACTIVE)->get();
        return view('admin.moderators.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'mobile'   => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:6',
            'status'   => 'required|in:0,1',
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,name',
        ]);

        try {
            DB::beginTransaction();

            $user = new User();
            $user->name                     = $request->name;
            $user->email                    = $request->email;
            $user->mobile                   = $request->mobile;
            $user->password                 = Hash::make($request->password);
            $user->role                     = USER_ROLE_USER;
            $user->status                   = $request->status;
            $user->email_verification_status  = STATUS_ACTIVE;
            $user->phone_verification_status  = STATUS_ACTIVE;
            $user->save();

            $user->assignRole($request->roles);

            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            return response()->json(['message' => $message, 'status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return response()->json(['message' => $message, 'status' => 'error']);
        }
    }

    public function edit($id)
    {
        $user = User::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
        $roles = Role::where('status', STATUS_ACTIVE)->get();
        return view('admin.moderators.edit-form', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $user = User::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
            $user->name   = $request->name;
            $user->email  = $request->email;
            $user->mobile = $request->mobile;
            $user->status = $request->status;
            $user->save();

            $user->syncRoles($request->roles);

            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return response()->json(['message' => $message, 'status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return response()->json(['message' => $message, 'status' => 'error']);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::where('tenant_id', auth()->user()->tenant_id)->findOrFail($id);
            $user->delete();

            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return response()->json(['message' => $message, 'status' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return response()->json(['message' => $message, 'status' => 'error']);
        }
    }
}
