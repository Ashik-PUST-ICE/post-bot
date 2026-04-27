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

    // ─── Admin Moderator Management ────────────────────────────────────────────

    public function index()
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $data['title'] = __('Users');
        $data['showManageModerator'] = 'show';
        $data['activeUsers'] = 'active';
        $data['users'] = User::where('role', USER_ROLE_USER)->with('roles')->orderBy('id', 'DESC')->get();
        $data['roles'] = Role::where('status', STATUS_ACTIVE)->get();
        return view('admin.moderators.index', $data);
    }

    public function store(Request $request)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }

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
        $user = User::findOrFail($id);

        // Super admin editing a user
        if (auth()->user()->role == USER_ROLE_SUPER_ADMIN) {
            return view('sadmin.user.edit-user', [
                'user'  => $user,
                'title' => __('Edit User'),
            ]);
        }

        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $roles = Role::where('status', STATUS_ACTIVE)->get();
        return view('admin.moderators.edit-form', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        // Super admin update — simpler validation (no roles field in sadmin form)
        if (auth()->user()->role == USER_ROLE_SUPER_ADMIN) {
            $request->validate([
                'name'   => 'required|string|max:255',
                'email'  => 'required|email|unique:users,email,' . $id,
                'mobile' => 'required|string|unique:users,mobile,' . $id,
            ]);

            try {
                $user         = User::findOrFail($id);
                $user->name   = $request->name;
                $user->email  = $request->email;
                $user->mobile = $request->mobile;

                if ($request->filled('country'))                  $user->country = $request->country;
                if ($request->filled('address'))                  $user->address = $request->address;
                if ($request->has('email_verification_status'))   $user->email_verification_status = $request->email_verification_status;
                if ($request->has('phone_verification_status'))   $user->phone_verification_status = $request->phone_verification_status;

                $user->save();
                return redirect()->route('super-admin.user.list')->with('success', __(UPDATED_SUCCESSFULLY));
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->with('error', __('Something went wrong'));
            }
        }

        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'mobile'  => 'required|string|unique:users,mobile,' . $id,
            'status'  => 'required|in:0,1',
            'roles'   => 'required|array',
            'roles.*' => 'exists:roles,name',
        ]);

        try {
            DB::beginTransaction();

            $user         = User::findOrFail($id);
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
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);
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

    // ─── Super Admin User Management ───────────────────────────────────────────

    /**
     * List users (admins) — returns view or DataTables JSON on AJAX.
     */
    public function userList(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('role', USER_ROLE_ADMIN)->orderBy('id', 'DESC')->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    return $row->status == STATUS_ACTIVE
                        ? '<span class="zBadge zBadge-active">' . __('Active') . '</span>'
                        : '<span class="zBadge zBadge-inactive">' . __('Suspended') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $detailsUrl = route('super-admin.user.details', $row->id);
                    $editUrl    = route('super-admin.user.edit', $row->id);
                    $suspendUrl = route('super-admin.user.suspend', $row->id);
                    return '
                        <a href="' . $detailsUrl . '" class="zBtn-green-sm">' . __('Details') . '</a>
                        <a href="' . $editUrl . '" class="zBtn-warning-sm">' . __('Edit') . '</a>
                        <a href="' . $suspendUrl . '" class="zBtn-danger-sm">' . __('Suspend') . '</a>';
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('sadmin.user.index', [
            'title'          => __('User List'),
            'activeUserList' => 'active',
        ]);
    }

    /**
     * Show add user form.
     */
    public function userAdd()
    {
        return view('sadmin.user.add-user', [
            'title' => __('Add User'),
        ]);
    }

    /**
     * Show user details.
     */
    public function userDetails($id)
    {
        $user = User::findOrFail($id);

        return view('sadmin.user.details-user', [
            'user'       => $user,
            'pageTitle'  => __('User Details'),
        ]);
    }

    /**
     * Toggle user suspend/active status.
     */
    public function userSuspend($id)
    {
        try {
            $user         = User::findOrFail($id);
            $user->status = ($user->status == STATUS_ACTIVE) ? STATUS_SUSPENDED : STATUS_ACTIVE;
            $user->save();

            return redirect()->back()->with('success', __('User status updated successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Delete a user.
     */
    public function userDelete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => __(DELETED_SUCCESSFULLY), 'status' => true]);
        } catch (\Exception $e) {
            return response()->json(['message' => __('Something went wrong'), 'status' => false]);
        }
    }

    /**
     * Return user activity log as DataTables JSON.
     */
    public function userActivity($id)
    {
        // No activity model present — return empty DataTables response
        return DataTables::of(collect([]))->make(true);
    }
}
