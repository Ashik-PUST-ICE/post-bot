@extends('sadmin.layouts.app')
@push('title')
    {{ $pageTitle ?? __('Roles & Permission') }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
        <div class="table-wrap-one">
            <div class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <div class="search-one flex-grow-1 max-w-207">
                        <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt="" /></button>
                        <input type="text" placeholder="{{ __('Search here...') }}" id="rolePremissionListTableSearch" />
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <button class="py-13 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white" data-bs-toggle="modal" data-bs-target="#addRoleModal">{{ __('+ Add Role') }}</button>
                </div>
            </div>
            <table class="table zTable zTable-last-item-right" id="rolePremissionListTable">
                <thead>
                <tr>
                    <th><div>{{__("SL")}}</div></th>
                    <th><div class="text-nowrap">{{__("Role Name")}}</div></th>
                    <th><div>{{__("Status")}}</div></th>
                    <th><div>{{__("Action")}}</div></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-labelledby="addRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <!-- Top -->
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{__("Add Role")}}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <!--  -->
                @php($routePrefix = auth()->user()->role == USER_ROLE_SUPER_ADMIN ? 'super-admin.' : 'admin.')
                <form class="ajax reset" action="{{route($routePrefix . 'roles.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-lg-6">
                            <label for="addRoleName" class="zForm-label">{{__("Role Name")}} <span class="text-red">*</span></label>
                            <input type="text" class="form-control zForm-control" id="addRoleName" placeholder="{{__("Enter Role Name")}}" name="name"/>
                        </div>
                        <div class="col-lg-6">
                            <label for="addRoleStatus" class="zForm-label">{{ __('Status') }} <span class="text-red">*</span></label>
                            <div class="dropdown dropdown-selectType">
                                <button class="dropdown-toggle selected-badge" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <span class="zBadge zBadge-active">{{__("Active")}}</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="zForm-wrap-tableCheckbox align-items-center">
                                                <input type="radio" class="form-check-input radio-action" id="addRoleStatusActive" checked name="status" value="{{STATUS_ACTIVE}}"/>
                                                <label for="addRoleStatusActive">
                                                    <span class="zBadge zBadge-active">{{__("Active")}}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="zForm-wrap-tableCheckbox align-items-center">
                                                <input type="radio" class="form-check-input radio-action" id="addRoleStatusDeactivate" name="status" value="{{STATUS_DEACTIVATE}}"/>
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
            </div>
        </div>
    </div>

    <!-- edit Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">

            </div>
        </div>
    </div>


    <!-- permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-labelledby="addPermissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">

            </div>
        </div>
    </div>

    <input type="hidden" id="roleListRoute" value="{{route($routePrefix . 'roles.index')}}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/role_permission.js') }}"></script>
@endpush

