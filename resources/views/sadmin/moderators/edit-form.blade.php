<div class="modal-header d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{__("Edit Team Member")}}</h4>
    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<form class="ajax reset" action="{{route('super-admin.staff.update', $user->id)}}" method="POST" enctype="multipart/form-data" data-handler="commonResponse">
    @csrf
    <div class="modal-body">
        <div class="row rg-20 pb-25">
            <div class="col-md-6">
                <label class="zForm-label">{{__("Name")}} <span class="text-red">*</span></label>
                <input type="text" name="name" value="{{$user->name}}" class="form-control zForm-control" placeholder="{{__("Enter Name")}}">
            </div>
            <div class="col-md-6">
                <label class="zForm-label">{{__("Email")}} <span class="text-red">*</span></label>
                <input type="email" name="email" value="{{$user->email}}" class="form-control zForm-control" placeholder="{{__("Enter Email")}}">
            </div>
            <div class="col-md-6">
                <label class="zForm-label">{{__("Mobile")}} <span class="text-red">*</span></label>
                <input type="text" name="mobile" value="{{$user->mobile}}" class="form-control zForm-control" placeholder="{{__("Enter Mobile")}}">
            </div>

            <div class="col-md-12">
                <label class="zForm-label">{{ __('Status') }} <span class="text-red">*</span></label>
                <select name="status" class="form-control zForm-control">
                    <option value="{{STATUS_ACTIVE}}" {{ $user->status == STATUS_ACTIVE ? 'selected' : '' }}>{{__("Active")}}</option>
                    <option value="{{STATUS_DEACTIVATE}}" {{ $user->status == STATUS_DEACTIVATE ? 'selected' : '' }}>{{__("Inactive")}}</option>
                </select>
            </div>
            <div class="col-md-12">
                <label class="zForm-label">{{__("Roles")}} <span class="text-red">*</span></label>
                <div class="row">
                    @foreach($roles as $role)
                    <div class="col-md-4">
                        <div class="d-flex form-check ps-0 mb-2">
                            <div class="zCheck form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="{{$role->name}}" name="roles[]" role="switch" id="role_edit_{{$role->id}}" {{ $user->hasRole($role->name) ? 'checked' : '' }}/>
                            </div>
                            <label class="form-check-label ps-3" for="role_edit_{{$role->id}}">
                                {{$role->name}}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
    <div class="modal-footer d-flex g-10 justify-content-end">
        <button type="button" class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14" data-bs-dismiss="modal">{{__("Cancel")}}</button>
        <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{__("Update")}}</button>
    </div>
</form>

<script>
    $(".multiple-basic-single").select2({
        placeholder: "Select Option",
        dropdownParent: $('#editModeratorModal')
    });
</script>
