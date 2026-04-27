<!-- Top -->
<div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{__("Edit Role")}}</h4>
    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<!--  -->
@php($routePrefix = auth()->user()->role == USER_ROLE_SUPER_ADMIN ? 'super-admin.' : 'admin.')
<form class="ajax reset" action="{{route($routePrefix . 'roles.store')}}" method="POST"
      enctype="multipart/form-data" data-handler="commonResponse">
    @csrf
    <div class="row rg-20 pb-25">
        <input type="hidden" name="id" value="{{$roleData->id}}">
        <div class="col-lg-6">
            <label for="addRoleName" class="zForm-label">{{__("Role Name")}} <span class="text-red">*</span></label>
            <input type="text" class="form-control zForm-control" id="addRoleName" placeholder="{{__("Enter Role Name")}}" name="name" value="{{$roleData->name}}"/>
        </div>
        <div class="col-lg-6">
            <label for="addRoleStatus" class="zForm-label">Status <span class="text-red">*</span></label>
            <div class="dropdown dropdown-selectType">
                <button class="dropdown-toggle selected-badge" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    @if($roleData->status == STATUS_ACTIVE)
                        <span class="zBadge zBadge-active">{{__("Active")}}</span>
                    @else
                        <span class="zBadge zBadge-deactivate">{{__("Deactivate")}}</span>
                    @endif
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <div class="dropdown-item">
                            <div class="zForm-wrap-tableCheckbox align-items-center">
                                <input type="radio" class="form-check-input radio-action" id="addRoleStatusActive" {{$roleData->status == STATUS_ACTIVE?'checked':''}} name="status" value="{{STATUS_ACTIVE}}"/>
                                <label for="addRoleStatusActive">
                                    <span class="zBadge zBadge-active">{{__("Active")}}</span>
                                </label>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="dropdown-item">
                            <div class="zForm-wrap-tableCheckbox align-items-center">
                                <input type="radio" class="form-check-input radio-action" id="addRoleStatusDeactivate" {{$roleData->status == STATUS_DEACTIVATE?'checked':''}} name="status" value="{{STATUS_DEACTIVATE}}"/>
                                <label for="addRoleStatusDeactivate">
                                    <span class="zBadge zBadge-deactivate">{{__("Deactivate")}}</span>
                                </label>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="bd-t-one bd-c-light-border pt-17 d-flex g-10">
        <button type="button" class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14" data-bs-dismiss="modal" aria-label="Close">{{__("Cancel")}}</button>
        <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{__("Save")}}</button>
    </div>
</form>
