@extends('admin.layouts.app')
@push('title')
    {{ $title }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-12">
                <div class="table-wrap-one h-100">
                    <div class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                        <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                            <div class="search-one flex-grow-1 max-w-207">
                                <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt="" /></button>
                                <input type="text" placeholder="{{ __('Search here...') }}" id="moderatorTableSearch" />
                            </div>
                        </div>
                        <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                            <button class="py-13 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white" data-bs-toggle="modal" data-bs-target="#addModeratorModal">
                                <i class="fa fa-plus"></i> {{ __('Add Team Member') }}
                            </button>
                        </div>
                    </div>
                    <table class="table zTable zTable-last-item-right" id="moderatorTable">
                        <thead>
                            <tr>
                                <th><div>{{__("SL")}}</div></th>
                                <th><div>{{__("Name")}}</div></th>
                                <th><div>{{__("Email")}}</div></th>
                                <th><div>{{__("Roles")}}</div></th>
                                <th><div>{{__("Status")}}</div></th>
                                <th><div>{{__("Action")}}</div></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModeratorModal" tabindex="-1" aria-labelledby="addModeratorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{__("Add Team Member")}}</h4>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{route('admin.users.store')}}" method="POST" enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <div class="row rg-20 pb-25">
                        <div class="col-md-6">
                            <label class="zForm-label">{{__("Name")}} <span class="text-red">*</span></label>
                            <input type="text" name="name" class="form-control zForm-control" placeholder="{{__("Enter Name")}}">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label">{{__("Email")}} <span class="text-red">*</span></label>
                            <input type="email" name="email" class="form-control zForm-control" placeholder="{{__("Enter Email")}}">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label">{{__("Mobile")}} <span class="text-red">*</span></label>
                            <input type="text" name="mobile" class="form-control zForm-control" placeholder="{{__("Enter Mobile")}}">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label">{{__("Password")}} <span class="text-red">*</span></label>
                            <input type="password" name="password" class="form-control zForm-control" placeholder="{{__("Enter Password")}}">
                        </div>

                            <div class="col-md-12">
                            <label class="zForm-label">{{ __('Status') }} <span class="text-red">*</span></label>
                            <select name="status" class="form-control zForm-control">
                                <option value="{{STATUS_ACTIVE}}">{{__("Active")}}</option>
                                <option value="{{STATUS_DEACTIVATE}}">{{__("Inactive")}}</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="zForm-label">{{__("Roles")}} <span class="text-red">*</span></label>
                            <div class="row">
                                @foreach($roles as $role)
                                <div class="col-md-4">
                                    <div class="d-flex form-check ps-0 mb-2">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input" type="checkbox" value="{{$role->name}}" name="roles[]" role="switch" id="role_{{$role->id}}"/>
                                        </div>
                                        <label class="form-check-label ps-3" for="role_{{$role->id}}">
                                            {{$role->name}}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    
                    </div>
                    <div class="d-flex g-10 justify-content-end">
                        <button type="button" class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14" data-bs-dismiss="modal">{{__("Cancel")}}</button>
                        <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{__("Save")}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModeratorModal" tabindex="-1" aria-labelledby="editModeratorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
            </div>
        </div>
    </div>

    <input type="hidden" id="moderatorTableRoute" value="{{route('admin.users.index')}}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/moderators.js') }}"></script>
@endpush
