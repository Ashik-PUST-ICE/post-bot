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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::where('tenant_id', auth()->user()->tenant_id)->where('role', USER_ROLE_ADMIN_STAFF)->with('roles')->orderBy('id', 'DESC')->get();
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
                                <button onclick="getEditModal(\'' . route('admin.users.edit', $row->id) . '\', \'#editModeratorModal\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke-color bg-white" title="' . __('Edit') . '">
                                    <i class="fa-solid fa-pen-to-square text-para-text"></i>
                                </button>
                            </li>
                            <li class="d-flex">
                                <button onclick="deleteItem(\'' . route('admin.users.destroy', $row->id) . '\', \'moderatorTable\')" class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke-color bg-white" title="' . __('Delete') . '">
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
        return view('admin.moderators.index', $data);
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
            $user->role                     = USER_ROLE_ADMIN_STAFF;
            $user->tenant_id                = auth()->user()->tenant_id;
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
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $id,
            'mobile'   => 'required|string|unique:users,mobile,' . $id,
            'status'   => 'required',
            'roles'    => 'required|array',
            'roles.*'  => 'exists:roles,name',
        ]);
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
