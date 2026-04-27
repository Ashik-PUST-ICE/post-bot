<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\RolePermissionService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermisionController extends Controller
{
    use ResponseTrait;

    public $rolePermissionServices;

    public function __construct()
    {
        $this->rolePermissionServices = new RolePermissionService();
    }

    public function list(Request $request)
    {
        if ($request->ajax()) {
            return $this->rolePermissionServices->getRoleList();
        } else {
            $data['pageTitle'] = __('Role & Permission');
            $data['showManageModerator'] = 'show';
            $data['activeRole'] = 'active';
            $data['roleList'] = Role::where('tenant_id', auth()->user()->tenant_id)->orderBy('id', 'DESC')->get();
            return view('admin.role_permission.rolelist', $data);
        }
    }

    public function addNew()
    {
        $data['pageTitle'] = __('Add New Role');
        $data['showManageModerator'] = 'show';
        $data['activeRole'] = 'active';
        return view('admin.role_permission.add-new', $data);
    }

    public function edit($id)
    {
        $roleId = decrypt($id);
        $systemRoles = [USER_ROLE_ADMIN, USER_ROLE_ADMIN_STAFF];
        if (in_array($roleId, $systemRoles)) {
            return $this->error([], __("System roles cannot be edited."));
        }
        $data['pageTitle'] = __('Edit Role');
        $data['showManageModerator'] = 'show';
        $data['activeRole'] = 'active';
        $data['roleData'] = Role::where('tenant_id', auth()->user()->tenant_id)->find($roleId);
        return view('admin.role_permission.edit', $data);
    }

    public function permission($id)
    {
        $data['roleData'] = Role::where('tenant_id', auth()->user()->tenant_id)->find(decrypt($id));
        $data['permissionList'] = Permission::all();
        $data['rolePermissions'] = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",decrypt($id))
            ->get();
        return view('admin.role_permission.permission', $data)->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:20',
        ]);

        return $this->rolePermissionServices->store($request);
    }

    public function delete($id){
        try {
            DB::beginTransaction();
            $roleId = decrypt($id);
            $systemRoles = [USER_ROLE_ADMIN, USER_ROLE_ADMIN_STAFF];
            if (in_array($roleId, $systemRoles)) {
                return $this->error([], __("System roles cannot be deleted."));
            }
            $data = Role::where('tenant_id', auth()->user()->tenant_id)->find($roleId);
            $data->delete();
            DB::commit();
            $message = getMessage(DELETED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    public function permissionUpdate(Request $request){
        $request->validate([
            'role' => 'required',
            'permission' => 'required',
        ]);
        return $this->rolePermissionServices->permissionUpdate($request);
    }
}
