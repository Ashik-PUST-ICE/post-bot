@extends('admin.layouts.app')
@push('title')
    {{$pageTitle}}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include("admin.setting.sidebar")
                </div>
            </div>
            <div class="col-xl-9">
                <form class="ajax reset" action="{{route('admin.setting.role-permission.store')}}" method="POST"
                      enctype="multipart/form-data" data-handler="commonResponse">
                    @csrf
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-25">
                        <div class="row rg-20">
                            <div class="col-12">
                                <label for="addRoleName" class="zForm-label">{{__("Name")}}</label>
                                <input type="text" class="form-control zForm-control" id="addRoleName" placeholder="{{__("Enter Name")}}"  name="name"/>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex g-12 flex-wrap">
                        <button type="submit" class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{__("Save Changes")}}</button>
                        <a href="{{ URL::previous() }}" class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{__("Cancel")}}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/role_permission.js') }}"></script>
@endpush

