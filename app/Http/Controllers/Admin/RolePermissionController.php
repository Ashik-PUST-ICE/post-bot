<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        $data['title'] = __('Roles');
        $data['showManageModerator'] = 'show';
        $data['activeRole'] = 'active';
        $data['roles'] = Role::orderBy('id', 'DESC')->get();
        $data['permissions'] = Permission::all();
        return view('admin.moderators.roles.index', $data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $role = new Role();
            $role->display_name = $request->name;
            $role->name = getSlug($request->name).'-'.date('Ymdhis');
            $role->guard_name = 'web';
            $role->status = USER_ROLE_ADMIN; // Default active
            $role->save();

            // No permissions here, will be set separately

            DB::commit();
            $message = __(CREATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        $data['role'] = Role::findOrFail($id);
        $data['permissions'] = Permission::all();
        $data['oldPermissions'] = $data['role']->permissions->pluck('name')->toArray();
        return view('admin.moderators.roles.edit')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        $request->validate([
            'name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $role = Role::where('id', $id)->first();
            if($role->display_name !== $request->name){
                $role->display_name = $request->name;
                $role->name = getSlug($request->name).'-'.date('Ymdhis');
            }
            $role->save();

            // No permissions update here

            DB::commit();
            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    /**
     * Show permissions for a role.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function permissions($id)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        $data['title'] = __('Permissions for Role');
        $data['showManageModerator'] = 'show';
        $data['activeRole'] = 'active';
        $data['role'] = Role::findOrFail($id);
        $permissions = Permission::all();
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $module = $this->extractModule($permission->name);
            $permission->module = $module;
            if (!isset($groupedPermissions[$module])) {
                $groupedPermissions[$module] = [];
            }
            $groupedPermissions[$module][] = $permission;
        }
        $data['permissions'] = $groupedPermissions;
        $data['oldPermissions'] = $data['role']->permissions->pluck('name')->toArray();
        return view('admin.moderators.roles.permissions', $data);
    }

    /**
     * Update permissions for a role.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePermissions(Request $request, $id)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        try {
            DB::beginTransaction();
            $role = Role::where('id', $id)->first();
            $role->syncPermissions($request->permissions);

            DB::commit();

            // Create notification for role permissions update
            setCommonNotification(
                'Role Permissions Updated',
                'Permissions for role "' . $role->display_name . '" have been updated by ' . auth()->user()->name,
                route('admin.roles.permissions', $role->id)
            );

            $message = __(UPDATED_SUCCESSFULLY);
            return $this->success([], $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return $this->error([], $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (auth()->user()->role != USER_ROLE_ADMIN) {
            abort(403, 'Unauthorized');
        }
        try {
            DB::beginTransaction();
            Role::where('id', $id)->delete();

            DB::commit();
            $message = __(DELETED_SUCCESSFULLY);
            return redirect()->back()->with('success',$message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = getErrorMessage($e, $e->getMessage());
            return redirect()->back()->with('error',$message);
        }
    }

    private function extractModule($name)
    {
        $parts = explode(': ', $name);
        return $parts[0] ?? $name;
    }
}

