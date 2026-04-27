<?php

namespace App\Http\Services;

use App\Models\ServiceAssignee;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionService
{
    use ResponseTrait;

    public function getAll()
    {
        return Role::where('tenant_id', auth()->user()->tenant_id)->get();
    }

    public function getRoleList()
    {
        $data = Role::where('tenant_id', auth()->user()->tenant_id)->get();

        return datatables($data)
            ->addIndexColumn()
            ->editColumn('role_name', function ($data) {
                return "<p>$data->name</p>";
            })
            ->editColumn('status', function ($data) {
                if ($data->status == STATUS_ACTIVE) {
                    return "<p class='zBadge zBadge-active'>" . __('Active') . "</p>";
                } else if ($data->status == STATUS_DEACTIVATE) {
                    return "<p class='zBadge zBadge-deactivate'>" . __('Deactivate') . "</p>";
                }
            })
            ->addColumn('action', function ($data) {
                $currentRouteName = request()->route() ? request()->route()->getName() : '';
                $routeNamePrefix = str_contains($currentRouteName, 'super-admin') ? 'super-admin.roles.' : 'admin.roles.';
                if (str_contains($currentRouteName, 'super-admin')) {
                    $systemRoles = [USER_ROLE_SUPER_ADMIN, USER_ROLE_SUPER_ADMIN_STAFF];
                } else {
                    $systemRoles = [USER_ROLE_ADMIN, USER_ROLE_ADMIN_STAFF];
                }
                if (in_array($data->id, $systemRoles)) {
                    return '<div class="dropdown dropdown-one">
                           <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                           <ul class="dropdown-menu dropdownItem-one">
                              <li>
                                 <button  data-bs-toggle="modal" data-bs-target="#addPermissionModal" class="d-flex align-items-center cg-8 border-0 bg-transparent" onclick="getEditModal(\'' . route($routeNamePrefix . 'permission', encrypt($data->id)) . '\'' . ', \'#addPermissionModal\')">
                                    <div class="d-flex">
                                       <svg width="14" height="14" viewBox="0 0 14 14" fill="currentcolor" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M2.37405 12.3634L2.66794 11.9589L2.37405 12.3634ZM1.63661 11.626L2.04112 11.3321L1.63661 11.626ZM12.3634 11.626L11.9589 11.3321L12.3634 11.626ZM11.626 12.3634L11.3321 11.9589L11.626 12.3634ZM11.626 1.63661L11.3321 2.04112L11.626 1.63661ZM12.3634 2.37405L11.9589 2.66794L12.3634 2.37405ZM2.37405 1.63661L2.66794 2.04112L2.37405 1.63661ZM1.63661 2.37405L2.04112 2.66794L1.63661 2.37405ZM5 6.5C4.72386 6.5 4.5 6.72386 4.5 7C4.5 7.27614 4.72386 7.5 5 7.5V6.5ZM9 7.5C9.27614 7.5 9.5 7.27614 9.5 7C9.5 6.72386 9.27614 6.5 9 6.5V7.5ZM6.5 9C6.5 9.27614 6.72386 9.5 7 9.5C7.27614 9.5 7.5 9.27614 7.5 9H6.5ZM7.5 5C7.5 4.72386 7.27614 4.5 7 4.5C6.72386 4.5 6.5 4.72386 6.5 5H7.5ZM7 12.5C5.73895 12.5 4.83333 12.4993 4.13203 12.4233C3.44009 12.3484 3.00661 12.2049 2.66794 11.9589L2.08016 12.7679C2.61771 13.1585 3.24729 13.3333 4.02432 13.4175C4.79198 13.5007 5.76123 13.5 7 13.5V12.5ZM0.5 7C0.5 8.23877 0.499314 9.20802 0.582485 9.97568C0.666671 10.7527 0.841549 11.3823 1.2321 11.9198L2.04112 11.3321C1.79506 10.9934 1.65163 10.5599 1.57667 9.86797C1.50069 9.16667 1.5 8.26105 1.5 7H0.5ZM2.66794 11.9589C2.42741 11.7841 2.21588 11.5726 2.04112 11.3321L1.2321 11.9198C1.46854 12.2453 1.75473 12.5315 2.08016 12.7679L2.66794 11.9589ZM12.5 7C12.5 8.26105 12.4993 9.16667 12.4233 9.86797C12.3484 10.5599 12.2049 10.9934 11.9589 11.3321L12.7679 11.9198C13.1585 11.3823 13.3333 10.7527 13.4175 9.97568C13.5007 9.20802 13.5 8.23877 13.5 7H12.5ZM7 13.5C8.23877 13.5 9.20802 13.5007 9.97568 13.4175C10.7527 13.3333 11.3823 13.1585 11.9198 12.7679L11.3321 11.9589C10.9934 12.2049 10.5599 12.3484 9.86797 12.4233C9.16667 12.4993 8.26105 12.5 7 12.5V13.5ZM11.9589 11.3321C11.7841 11.5726 11.5726 11.7841 11.3321 11.9589L11.9198 12.7679C12.2453 12.5315 12.5315 12.2453 12.7679 11.9198L11.9589 11.3321ZM7 1.5C8.26105 1.5 9.16667 1.50069 9.86797 1.57667C10.5599 1.65163 10.9934 1.79506 11.3321 2.04112L11.9198 1.2321C11.3823 0.841549 10.7527 0.666671 9.97568 0.582485C9.20802 0.499314 8.23877 0.5 7 0.5V1.5ZM13.5 7C13.5 5.76123 13.5007 4.79198 13.4175 4.02432C13.3333 3.24729 13.1585 2.61771 12.7679 2.08016L11.9589 2.66794C12.2049 3.00661 12.3484 3.44009 12.4233 4.13203C12.4993 4.83333 12.5 5.73895 12.5 7H13.5ZM11.3321 2.04112C11.5726 2.21588 11.7841 2.42741 11.9589 2.66794L12.7679 2.08016C12.5315 1.75473 12.2453 1.46854 11.9198 1.2321L11.3321 2.04112ZM7 0.5C5.76123 0.5 4.79198 0.499314 4.02432 0.582485C3.24729 0.666671 2.61771 0.841549 2.08016 1.2321L2.66794 2.04112C3.00661 1.79506 3.44009 1.65163 4.13203 1.57667C4.83333 1.50069 5.73895 1.5 7 1.5V0.5ZM1.5 7C1.5 5.73895 1.50069 4.83333 1.57667 4.13203C1.65163 3.44009 1.79506 3.00661 2.04112 2.66794L1.2321 2.08016C0.841549 2.61771 0.666671 3.24729 0.582485 4.02432C0.499314 4.79198 0.5 5.76123 0.5 7H1.5ZM2.08016 1.2321C1.75473 1.46854 1.46854 1.75473 1.2321 2.08016L2.04112 2.66794C2.21588 2.42741 2.42741 2.21588 2.66794 2.04112L2.08016 1.2321ZM5 7.5H9V6.5H5V7.5ZM7.5 9V5H6.5V9H7.5Z"/>
                                       </svg>
                                    </div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Add Permission") . '</p>
                                 </button>
                              </li>
                           </ul>
                        </div>';
                } else {
                    return '<div class="dropdown dropdown-one">
                           <button class="dropdown-toggle p-0 bg-transparent w-22 h-22 ms-auto bd-one bd-c-light-border rounded-circle fs-13 text-textBlack d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                           <ul class="dropdown-menu dropdownItem-one">
                              <li>
                                 <button  data-bs-toggle="modal" data-bs-target="#addPermissionModal" class="d-flex align-items-center cg-8 border-0 bg-transparent" onclick="getEditModal(\'' . route($routeNamePrefix . 'permission', encrypt($data->id)) . '\'' . ', \'#addPermissionModal\')">
                                    <div class="d-flex">
                                       <svg width="14" height="14" viewBox="0 0 14 14" fill="currentcolor" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M2.37405 12.3634L2.66794 11.9589L2.37405 12.3634ZM1.63661 11.626L2.04112 11.3321L1.63661 11.626ZM12.3634 11.626L11.9589 11.3321L12.3634 11.626ZM11.626 12.3634L11.3321 11.9589L11.626 12.3634ZM11.626 1.63661L11.3321 2.04112L11.626 1.63661ZM12.3634 2.37405L11.9589 2.66794L12.3634 2.37405ZM2.37405 1.63661L2.66794 2.04112L2.37405 1.63661ZM1.63661 2.37405L2.04112 2.66794L1.63661 2.37405ZM5 6.5C4.72386 6.5 4.5 6.72386 4.5 7C4.5 7.27614 4.72386 7.5 5 7.5V6.5ZM9 7.5C9.27614 7.5 9.5 7.27614 9.5 7C9.5 6.72386 9.27614 6.5 9 6.5V7.5ZM6.5 9C6.5 9.27614 6.72386 9.5 7 9.5C7.27614 9.5 7.5 9.27614 7.5 9H6.5ZM7.5 5C7.5 4.72386 7.27614 4.5 7 4.5C6.72386 4.5 6.5 4.72386 6.5 5H7.5ZM7 12.5C5.73895 12.5 4.83333 12.4993 4.13203 12.4233C3.44009 12.3484 3.00661 12.2049 2.66794 11.9589L2.08016 12.7679C2.61771 13.1585 3.24729 13.3333 4.02432 13.4175C4.79198 13.5007 5.76123 13.5 7 13.5V12.5ZM0.5 7C0.5 8.23877 0.499314 9.20802 0.582485 9.97568C0.666671 10.7527 0.841549 11.3823 1.2321 11.9198L2.04112 11.3321C1.79506 10.9934 1.65163 10.5599 1.57667 9.86797C1.50069 9.16667 1.5 8.26105 1.5 7H0.5ZM2.66794 11.9589C2.42741 11.7841 2.21588 11.5726 2.04112 11.3321L1.2321 11.9198C1.46854 12.2453 1.75473 12.5315 2.08016 12.7679L2.66794 11.9589ZM12.5 7C12.5 8.26105 12.4993 9.16667 12.4233 9.86797C12.3484 10.5599 12.2049 10.9934 11.9589 11.3321L12.7679 11.9198C13.1585 11.3823 13.3333 10.7527 13.4175 9.97568C13.5007 9.20802 13.5 8.23877 13.5 7H12.5ZM7 13.5C8.23877 13.5 9.20802 13.5007 9.97568 13.4175C10.7527 13.3333 11.3823 13.1585 11.9198 12.7679L11.3321 11.9589C10.9934 12.2049 10.5599 12.3484 9.86797 12.4233C9.16667 12.4993 8.26105 12.5 7 12.5V13.5ZM11.9589 11.3321C11.7841 11.5726 11.5726 11.7841 11.3321 11.9589L11.9198 12.7679C12.2453 12.5315 12.5315 12.2453 12.7679 11.9198L11.9589 11.3321ZM7 1.5C8.26105 1.5 9.16667 1.50069 9.86797 1.57667C10.5599 1.65163 10.9934 1.79506 11.3321 2.04112L11.9198 1.2321C11.3823 0.841549 10.7527 0.666671 9.97568 0.582485C9.20802 0.499314 8.23877 0.5 7 0.5V1.5ZM13.5 7C13.5 5.76123 13.5007 4.79198 13.4175 4.02432C13.3333 3.24729 13.1585 2.61771 12.7679 2.08016L11.9589 2.66794C12.2049 3.00661 12.3484 3.44009 12.4233 4.13203C12.4993 4.83333 12.5 5.73895 12.5 7H13.5ZM11.3321 2.04112C11.5726 2.21588 11.7841 2.42741 11.9589 2.66794L12.7679 2.08016C12.5315 1.75473 12.2453 1.46854 11.9198 1.2321L11.3321 2.04112ZM7 0.5C5.76123 0.5 4.79198 0.499314 4.02432 0.582485C3.24729 0.666671 2.61771 0.841549 2.08016 1.2321L2.66794 2.04112C3.00661 1.79506 3.44009 1.65163 4.13203 1.57667C4.83333 1.50069 5.73895 1.5 7 1.5V0.5ZM1.5 7C1.5 5.73895 1.50069 4.83333 1.57667 4.13203C1.65163 3.44009 1.79506 3.00661 2.04112 2.66794L1.2321 2.08016C0.841549 2.61771 0.666671 3.24729 0.582485 4.02432C0.499314 4.79198 0.5 5.76123 0.5 7H1.5ZM2.08016 1.2321C1.75473 1.46854 1.46854 1.75473 1.2321 2.08016L2.04112 2.66794C2.21588 2.42741 2.42741 2.21588 2.66794 2.04112L2.08016 1.2321ZM5 7.5H9V6.5H5V7.5ZM7.5 9V5H6.5V9H7.5Z"/>
                                       </svg>
                                    </div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Add Permission") . '</p>
                                 </button>
                              </li>
                              <li>
                                 <button class="d-flex align-items-center cg-8 border-0 bg-transparent" onclick="getEditModal(\'' . route($routeNamePrefix . 'edit', encrypt($data->id)) . '\'' . ', \'#editRoleModal\')">
                                    <div class="d-flex">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentcolor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1.23525 4.96695C1.66958 3.11534 3.11534 1.66958 4.96696 1.23525C6.30417 0.921583 7.69583 0.921583 9.03305 1.23525C10.8847 1.66958 12.3304 3.11534 12.7648 4.96696C13.0784 6.30417 13.0784 7.69583 12.7648 9.03305C12.3304 10.8847 10.8847 12.3304 9.03304 12.7648C7.69583 13.0784 6.30417 13.0784 4.96696 12.7648C3.11534 12.3304 1.66958 10.8847 1.23525 9.03305C0.921583 7.69583 0.921583 6.30417 1.23525 4.96695Z"></path><path d="M8.99992 6.61603C8.1919 6.88537 7.11454 5.80801 7.38388 4.99999M7.71585 4.66802L5.97504 6.40883C5.21824 7.16563 4.68135 8.11388 4.42177 9.15221L4.33766 9.48867C4.31145 9.5935 4.40641 9.68847 4.51125 9.66226L4.84771 9.57814C5.88603 9.31856 6.83428 8.78167 7.59108 8.02487L9.33189 6.28406C9.54619 6.06976 9.66658 5.7791 9.66658 5.47604C9.66658 4.84494 9.15498 4.33333 8.52387 4.33333C8.22081 4.33333 7.93015 4.45372 7.71585 4.66802Z"></path>
                                        </svg>
                                    </div>
                                    <p class="fs-14 fw-500 lh-19 text-textBlack text-nowrap">' . __("Edit") . '</p>
                                 </button>
                              </li>
                              <li>
                                 <button class="d-flex align-items-center cg-8 border-0 bg-transparent deleteItem" onclick="deleteItem(\'' . route($routeNamePrefix . 'destroy', encrypt($data->id)) . '\', \'rolePremissionListTable\')">
                                    <div class="d-flex"><img src="' . asset('assets/images/icon/delete.svg') . '" alt=""></div>
                                    <p class="fs-14 fw-500 lh-19 text-red text-nowrap">' . __("Delete") . '</p>
                                 </button>
                              </li>
                           </ul>
                        </div>';
                }

            })
            ->rawColumns(['role_name', 'action', 'status'])
            ->make(true);

    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            if ($request->id) {
                if (str_contains(request()->route()->getName(), 'super-admin')) {
                    $systemRoles = [USER_ROLE_SUPER_ADMIN, USER_ROLE_SUPER_ADMIN_STAFF];
                } else {
                    $systemRoles = [USER_ROLE_ADMIN, USER_ROLE_ADMIN_STAFF];
                }
                if (in_array($request->id, $systemRoles)) {
                    return $this->error([], __("System roles cannot be edited."));
                }
                $dataObj = Role::find($request->id);
                $msg = getMessage(UPDATED_SUCCESSFULLY);
            } else {
                $dataObj = new Role();
                $msg = getMessage(CREATED_SUCCESSFULLY);
            }
            $dataObj->name = $request->name;
            $dataObj->guard_name = 'web';
            $dataObj->user_id = auth()->id();
            $dataObj->tenant_id = auth()->user()->tenant_id;
            $dataObj->status = $request->status;
            $dataObj->save();

            DB::commit();

            return $this->success([], $msg);

        } catch (Exception $exception) {
            DB::rollBack();
            return $this->error([], $exception->getMessage());
        }
    }

    public function permissionUpdate($request)
    {
        try {
            $role = Role::find(decrypt($request->role));
            if ($role->id == USER_ROLE_SUPER_ADMIN) {
                 return $this->error([], __("Super Admin permissions are fixed."));
            }
            $role->syncPermissions($request->permission);
            return $this->success([], UPDATED_SUCCESSFULLY);
        } catch (Exception $exception) {
            return $this->error([], SOMETHING_WENT_WRONG);
        }
    }

}
