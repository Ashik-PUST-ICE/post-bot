@extends('auth.layouts.app')

@push('title')
{{ __('Registration') }}
@endpush

@section('content')
<div class="signLog-section">
    <div class="signLog-section-wrap">
        <div class="left" data-background="{{ asset('assets/images/auth-img-bg.png') }}" data-aos="fade-left"
            data-aos-duration="1000">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <!-- Logo -->
                    <a href="{{ route('frontend') }}" class="d-flex mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" class="auth-logo" />
                    </a>
                    <!--  -->
                    <div class="pb-30">
                        <h4 class="fs-32 fw-600 lh-48 text-textBlack pb-5">{{__("Sign Up")}}</h4>
                        <p class="fs-14 fw-400 lh-22 text-para-text">{{__("Already have an account")}}? <a
                                href="{{ route('login') }}" class="text-main-color text-decoration-underline">{{__("Sign
                                In")}}</a></p>
                    </div>
                    <!--  -->
                    <form action="{{ route('register') }}" enctype="multipart/form-data" method="post">
                        @csrf
                        <input type="hidden" name="package" value="{{ request('package') }}" />
                        <div class="pb-20">
                            <label for="inputFullName" class="zForm-label">{{__("Full Name")}}</label>
                            <input type="text" class="form-control zForm-control" id="inputFullName" name="name"
                                placeholder="{{__(" Enter full name")}}" value="{{ old('name') }}" />
                            @error('name')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-20">
                            <label for="inputEmailAddress" class="zForm-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control zForm-control" id="inputEmailAddress"
                                value="{{ old('email') }}" name="email" placeholder="{{ __('Enter email address') }}" />
                            @error('email')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-30">
                            <label for="inputPassword" class="zForm-label">{{ __('Password') }}</label>
                            <div class="passShowHide">
                                <input type="password" class="form-control zForm-control passShowHideInput"
                                    id="inputPassword" placeholder="{{ __('Enter your password') }}" name="password" />
                                <button type="button" toggle=".passShowHideInput"
                                    class="toggle-password fa-solid fa-eye"></button>
                                @error('password')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!--  -->
                        <button type="submit"
                            class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-4 bg-main-color fs-14 fw-700 lh-20 text-white">{{__("Sign
                            Up")}}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_left_image') }}" data-aos="fade-right"
            data-aos-duration="1000">
            <div class="content">
                <h4 class="title">
                    {{getOption('auth_page_title')}}<br />
{{--                    <span>{{ getOption('app_name') }}</span>.--}}
                </h4>
                <p class="info">{{getOption('auth_page_description')}}</p>
            </div>
        </div>
    </div>
</div>
@endsection
