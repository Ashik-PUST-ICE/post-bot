@extends('sadmin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <!-- Page content area start -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

        <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $title }}</h4>

        <form method="POST" class="" enctype="multipart/form-data"
              action="{{ route('super-admin.user.update', $user->id) }}">
            @csrf
            <!-- Personal Info -->
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-sm-30 p-15 mb-20">
                <div class="row rg-20">
                    <div class="col-12">
                        <div class="upload-img-box profileImage-upload">
                            <div class="icon"><img src="{{ asset('assets/images/icon/camera.svg') }}" alt=""/></div>
                            <img src="{{ getFileUrl($user->image) }}"/>
                            <input type="file" name="image" id="zImageUpload" accept="image/*"
                                   onchange="previewFile(this)"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="epFullName" class="zForm-label">{{ __('Full Name') }}
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="epFullName" name="name"
                               value="{{$user->name}}" placeholder="{{ __('Your Name') }}"/>
                    </div>
                    <div class="col-md-6">
                        <label for="epFullName" class="zForm-label">{{ __('Email') }} <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control zForm-control" id="epFullName" name="email"
                               value="{{$user->email}}" placeholder="{{ __('Email') }}"/>
                    </div>

                    <div class="col-md-6">
                        <label for="epFullName" class="zForm-label">{{ __('Country') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="country" name="country"
                               value="{{$user->country}}" placeholder="{{ __('Your Country') }}"/>
                    </div>
                    <div class="col-md-6">
                        <label for="epFullName" class="zForm-label">{{ __('Address') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="address" name="address"
                               value="{{$user->address}}" placeholder="{{ __('Your Address') }}"/>
                    </div>

                    <div class="col-md-6">
                        <label for="app_timezone" class="zForm-label">{{ __('Mobile No
                                Verification') }} <span class="text-danger">*</span></label>
                        <select name="phone_verification_status" class="form-control sf-select-without-search">
                            <option>{{__('Select')}}</option>
                            <option value="{{ ACTIVE }}" {{$user->phone_verification_status ==
                                    ACTIVE ? 'selected' : ''}} >{{__('Yes')}}</option>
                            <option value="{{ DEACTIVATE }}" {{$user->phone_verification_status
                                    == DEACTIVATE ? 'selected' : ''}} >{{__('No')}}</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="app_timezone" class="zForm-label">{{ __('Email Verification')
                                }} <span class="text-danger">*</span></label>
                        <select name="email_verification_status" class="form-control sf-select-without-search">
                            <option>{{__('Select')}}</option>
                            <option value="{{ ACTIVE }}" {{$user->email_verification_status ==
                                    ACTIVE ? 'selected' : ''}} >{{__('Yes')}}</option>
                            <option value="{{ DEACTIVATE }}" {{$user->email_verification_status
                                    == DEACTIVATE ? 'selected' : ''}} >{{__('No')}}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="epFullName" class="zForm-label">{{ __('Mobile Number') }}
                            <span class="text-danger">*</span></label>
                        <input type="text" class="form-control zForm-control" id="mobile" name="mobile"
                               value="{{$user->mobile}}" placeholder="{{ __('Your Phone Number') }}"/>
                    </div>

                </div>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit"
                        class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white">{{
            __('Update') }}</button>
            </div>
        </form>

        <!-- Page content area end -->
@endsection
