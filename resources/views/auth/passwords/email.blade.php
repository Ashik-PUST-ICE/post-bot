@extends('auth.layouts.app')

@push('title')
{{ __('Forget Password') }}
@endpush

@section('content')
<div class="signLog-section">
    <div class="signLog-section-wrap">
        <div class="left" data-background="{{ asset('assets/images/auth-img-bg.png') }}" data-aos="fade-left" data-aos-duration="1000">
            <div class="wrap">
                <div class="zMain-signLog-content">
                    <!-- Logo -->
                    <a href="{{ route('frontend') }}" class="d-flex mb-30">
                        <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" class="auth-logo" />
                    </a>
                    <!--  -->
                    <h4 class="fs-32 fw-600 lh-48 text-textBlack pb-24">{{__("Forgot Password")}}?</h4>
                    <!--  -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="pb-30">
                            <label for="inputEmailAddress" class="zForm-label">{{ __('Email Address') }}</label>
                            <input type="email" class="form-control zForm-control" id="inputEmailAddress" name="email"
                                value="{{ old(' email') }}" placeholder="{{ __(' Enter email address') }}" />
                            @error('email')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--  -->
                        <button type="submit"
                            class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-10 bg-main-color fs-14 fw-500 lh-20 text-white">{{
                            __('Submit') }}</button>
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
