<?php

namespace App\Http\Controllers\SuperAdmin;

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

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('role', USER_ROLE_SUPER_ADMIN_STAFF)->with('roles')->orderBy('id', 'DESC')->get();
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    $roles = '';
                    foreach ($row->roles as $role) {
                        $roles .= '<span class="badge bg-primary me-1">' . $role->name . '</span>';
                    }
                    return $roles;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == STATUS_ACTIVE) {
                        return '<span class="zBadge zBadge-active">' . __('Active') . '</span>';
                    } else {
                        return '<span class="zBadge zBadge-inactive">' . __('Inactive') . '</span>';
                    }
                })
                ->addColumn('action', function ($row) {
                    return '<ul class="d-flex align-items-center cg-5 justify-content-end">
                            <li class="d-flex">
                                <button onclick="getEditModal(\'' . route('super-admin.staff.edit', $row->id) . '\', \'#editModeratorModal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke-color bg-white" title="' . __('Edit') . '">
                                    <i class="fa-solid fa-pen-to-square text-para-text"></i>
                                </button>
                            </li>
                            <li class="d-flex">
                                <button onclick="deleteItem(\'' . route('super-admin.staff.delete', $row->id) . '\', \'moderatorTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke-color bg-white" title="' . __('Delete') . '">
                                    <i class="fa-solid fa-trash text-para-text"></i>
                                </button>
                            </li>
                        </ul>';
                })
                ->rawColumns(['roles', 'status', 'action'])
                ->make(true);
        }
        $data['title'] = __('Team Members');
        $data['showManageModerator'] = 'show';
        $data['activeUsers'] = 'active';
        $data['roles'] = Role::where('status', STATUS_ACTIVE)->get();
        return view('sadmin.moderators.index', $data);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'mobile'   => 'required|string|unique:users,mobile',
            'password' => 'required|string|min:6',
            'status'   => 'required',
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
            $user->role                     = USER_ROLE_SUPER_ADMIN_STAFF;
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
        $roles = Role::where('status', STATUS_ACTIVE)->get();
        if (request()->ajax()) {
            if ($user->role == USER_ROLE_SUPER_ADMIN_STAFF) {
                return view('sadmin.moderators.edit-form', compact('user', 'roles'));
            }
        }
        return view('sadmin.user.edit-user', [
            'user'  => $user,
            'roles' => $roles,
            'title' => __('Edit User'),
        ]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|unique:users,email,' . $id,
            'mobile'  => 'required|string|unique:users,mobile,' . $id,
            'status'  => 'required',
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
            $users = User::where('role', USER_ROLE_ADMIN)->with('package')->orderBy('id', 'DESC')->get();

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('package', function ($row) {
                    return $row->package ? $row->package->name : '<span class="text-danger">' . __('No Package') . '</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == STATUS_ACTIVE
                        ? '<span class="zBadge zBadge-active">' . __('Active') . '</span>'
                        : '<span class="zBadge zBadge-inactive">' . __('Suspended') . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $detailsUrl = route('super-admin.user.details', $row->id);
                    $editUrl    = route('super-admin.user.edit', $row->id);
                    $suspendUrl = route('super-admin.user.suspend', $row->id);
                    $suspendText = ($row->status == STATUS_ACTIVE) ? __("Suspend") : __("Activate");
                    $suspendIcon = ($row->status == STATUS_ACTIVE) ? 'fa-ban' : 'fa-check-circle';
                    
                    return '<div class="dropdown dropdown-one">
                           <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                           <ul class="dropdown-menu dropdownItem-one">
                              <li>
                                 <a href="' . $detailsUrl . '" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                    <div class="d-flex"><i class="fa-solid fa-eye text-para-text fs-14"></i></div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Details") . '</p>
                                 </a>
                              </li>
                              <li>
                                 <a href="' . $editUrl . '" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                    <div class="d-flex"><i class="fa-solid fa-pen-to-square text-para-text fs-14"></i></div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                 </a>
                              </li>
                              <li>
                                 <a href="' . $suspendUrl . '" class="d-flex align-items-center cg-8 border-0 bg-transparent px-15 py-10">
                                    <div class="d-flex"><i class="fa-solid ' . $suspendIcon . ' text-para-text fs-14"></i></div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . $suspendText . '</p>
                                 </a>
                              </li>
                           </ul>
                        </div>';
                })
                ->rawColumns(['package', 'status', 'action'])
                ->make(true);
        }

        return view('sadmin.user.index', [
            'title'          => __('Customer List'),
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
