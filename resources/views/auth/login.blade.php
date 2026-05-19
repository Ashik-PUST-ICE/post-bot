@extends('auth.layouts.app')

@push('title')
{{ __('Login') }}
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
                    @if (getOption('registration_status', 0) == ACTIVE)
                    <div class="pb-30">
                        <h4 class="fs-32 fw-600 lh-48 text-textBlack pb-5">{{ __('Sign In') }}</h4>
                        <p class="fs-14 fw-400 lh-22 text-para-text">{{ __("Don't have an account?") }} <a
                                href="{{ route('register') }}" class="text-main-color text-decoration-underline">{{
                                __('Sign Up') }}</a></p>
                    </div>
        
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="pb-20">
                            <label for="inputPhoneNumberOrEmail" class="zForm-label">{{ __('Email') }}</label>
                            <input type="text" name="email" class="form-control zForm-control"
                                id="inputPhoneNumberOrEmail" placeholder="{{ __('Enter email address') }}" />
                            @error('email')
                            <span class="fs-12 text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="pb-14">
                            <label for="inputPassword" class="zForm-label">{{ __('Password') }}</label>
                            <div class="passShowHide">
                                <input type="password" name="password"
                                    class="form-control zForm-control passShowHideInput" id="inputPassword"
                                    placeholder="{{ __(' Enter your password') }}" />
                                <button type="button" toggle=".passShowHideInput"
                                    class="toggle-password fa-solid fa-eye"></button>
                                @error('password')
                                <span class="fs-12 text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="pb-30 d-flex justify-content-between align-items-center flex-wrap g-10">
                            <div class="zForm-wrap-checkbox">
                                <input type="checkbox" class="form-check-input" id="authRemember" name="remember"
                                    value="1" />
                                <label for="authRemember">{{ __('Remember Me') }}</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="fs-14 fw-600 lh-22 text-main-color">{{
                                __('Forgot Password?') }}</a>
                        </div>
                        <button type="submit"
                            class="border-0 d-flex justify-content-center align-items-center w-100 p-15 bd-ra-4 bg-main-color fs-14 fw-700 lh-20 text-white">{{
                            __('Sign In') }}</button>
                    </form>
                    @if (config('app.login_help') == 'active')
                    <div class="row pt-12 fs-14">
                        <div class="col-md-12 mb-25">
                            <div class="table-responsive login-info-table mt-3">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td colspan="2" id="sadminCredentialShow" class="login-info">
                                                <b>{{ __('Super Admin') }} :</b> {{ __('sadmin@gmail.com') }} | 123456
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" id="adminCredentialShow" class="login-info">
                                                <b>{{ __('Admin ') }}:</b> {{ __('admin@gmail.com') }} | 123456
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="right" data-background="{{ getSettingImage('login_left_image') }}" data-aos="fade-right"
            data-aos-duration="1000">
            <div class="content">
                <h4 class="title">
                    {{ getOption('auth_page_title') }}
                    <br />
{{--                    <span>{{ getOption('app_name') }}</span>--}}
                </h4>
                <p class="info">{{ getOption('auth_page_description') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    "use strict"
        $('#sadminCredentialShow').on('click', function() {
            $('#inputPhoneNumberOrEmail').val('sadmin@gmail.com');
            $('#inputPassword').val('123456');
        });
        $('#adminCredentialShow').on('click', function() {
            $('#inputPhoneNumberOrEmail').val('admin@gmail.com');
            $('#inputPassword').val('123456');
        });
</script>
@endpush
