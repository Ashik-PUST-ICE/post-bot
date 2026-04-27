<!-- Top -->
<div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack">Add Permission</h4>
    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
</div>
<!--  -->
@php($routePrefix = auth()->user()->role == USER_ROLE_SUPER_ADMIN ? 'super-admin.' : 'admin.')
<form class="ajax " action="{{route($routePrefix . 'roles.permission-update')}}" method="POST"
      enctype="multipart/form-data" data-handler="commonResponse">
    @csrf
    <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-25">
        <!--  -->
        <input type="hidden" name="role" value="{{encrypt($roleData->id)}}">
        <div class="d-flex cg-5">
            <h4 class="fs-14 fw-600 lh-20 text-title-black pb-25"> {{__("Role Name: ")}}</h4>
            <h4 class="fs-14 fw-500 lh-20 text-title-black pb-25 al"> {{$roleData->name}}</h4>
        </div>

        <ul class="zList-pb-20">
            @foreach($permissionList as $key=>$item)
                @if(count($rolePermissions) > 0)
                    @php($flag=0)
                    @foreach($rolePermissions as $rolePermisonItem)
                        @if($rolePermisonItem->name == $item->name)
                            <li>
                                <div class="zForm-wrap-checkbox-2">
                                    <input type="checkbox" class="form-check-input" id="projectManager{{$key}}" value="{{$item->name}}" name="permission[]" checked {{$roleData->id == 1?'disabled':''}} />
                                    <label for="projectManager{{$key}}">{{$item->name}}</label>
                                </div>
                            </li>
                            @php($flag=0)
                            @break
                        @else

                            @php($flag=1)
                        @endif
                    @endforeach
                    @if($flag == 1)
                        <li>
                            <div class="zForm-wrap-checkbox-2">
                                <input type="checkbox" class="form-check-input" id="projectManager{{$key}}" value="{{$item->name}}" name="permission[]" {{$roleData->id == 1?'disabled':''}}/>
                                <label for="projectManager{{$key}}">{{$item->name}}</label>
                            </div>
                        </li>
                        @php($flag == 1)
                    @endif
                @else
                    <li>
                        <div class="zForm-wrap-checkbox-2">
                            <input type="checkbox" class="form-check-input" id="projectManager{{$key}}" value="{{$item->name}}" name="permission[]" />
                            <label for="projectManager{{$key}}">{{$item->name}}</label>
                        </div>
                    </li>
                @endif

            @endforeach
        </ul>
        <!--  -->
    </div>
    <div class="d-flex g-12 flex-wrap">
        <button type="submit" class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white" {{$roleData->id == 1?'disabled':''}}>{{__("Save Changes")}}</button>
        <button type="button" data-bs-dismiss="modal" aria-label="Close" class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</button>
    </div>
</form>
