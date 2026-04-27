@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div>
        <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('sadmin.setting.partials.general-sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $title }}</h4>
                    <div class="email-inbox__area bg-style form-horizontal__item bg-style admin-general-settings-page">
                        <div class="p-sm-25 p-15 bg-body-bg bd-ra-8 mb-16">
                            <h5 class="fs-16 fw-600 lh-22 text-textBlack pb-10">{{ __('Instructions') }}: </h5>
                            <p class="fs-14 fw-400 text-para-text pb-6">
                                {{ __('You need to follow some instruction after maintenance mode changes. Instruction
                                list given below-') }}
                            </p>
                            <ul class="zList-pb-12">
                                <li class="fs-14 fw-400 text-para-text">{{ __('1. If you select maintenance mode') }}
                                    <b>{{ __('Maintenance O') }}n</b>,
                                    {{ __("you need to input secret key for maintenance work. Otherwise you can't work
                                    this website. And your created secret key helps you to work under maintenance.") }}
                                </li>
                                <li class="fs-14 fw-400 text-para-text">
                                    {{ __('2. After created maintenance key, you can use this website secretly through
                                    this ur') }}
                                    l <span class="iconify" data-icon="arcticons:url-forwarder"></span> <span
                                        class="text-primary">{{ url('/') }}/(Your created secret key)</span></li>
                                <li class="fs-14 fw-400 text-para-text">
                                    {{ __('3. Only one time url is browsing with secret key, and you can browse your
                                    site in maintenance mode. When maintenance mode on, any user can see maintenance
                                    mode error message.') }}
                                </li>
                                <li class="fs-14 fw-400 text-para-text">
                                    {{ __('4. Unfortunately you forget your secret key and try to connect with your
                                    website.') }}
                                    <br><br> {{ __('Then you go to your project folder location') }}
                                    <b>{{ __('Main Files') }}</b>{{ __('(where your file in cpanel or your hosting)') }}
                                    <span class="iconify" data-icon="arcticons:url-forwarder"></span><b>{{ __('storage')
                                        }}</b>
                                    <span class="iconify" data-icon="arcticons:url-forwarder"></span><b>{{
                                        __('framework') }}</b>.
                                    {{ __('You can see 2 files and need to delete 2 files. Files are:') }}
                                    <br>
                                    {{ __('1. down') }} <br>
                                    {{ __('2. maintenance.php') }}
                                </li>
                        </div>
                    </div>
                    <form class="ajax" action="{{ route('super-admin.setting.maintenance.change') }}" method="POST"
                        enctype="multipart/form-data" data-handler="settingCommonHandler">
                        @csrf
                        <div class="row rg-20">
                            <div class="col-xxl-4 col-md-6">
                                <label class="zForm-label">{{ __('Maintenance Mode') }} <span
                                        class="text-danger">*</span></label>
                                <select name="maintenance_mode" id="" class="sf-select-without-search">
                                    <option value="">--{{ __('Select Option') }}--</option>
                                    <option value="1" @if (getOption('maintenance_mode')==1) selected @endif>
                                        {{ __('Maintenance On') }}</option>
                                    <option value="2" @if (getOption('maintenance_mode') !=1) selected @endif>
                                        {{ __('Live') }}</option>
                                </select>
                            </div>
                            <div class="col-xxl-4 col-md-6">
                                <label class="zForm-label">{{ __('Maintenance Mode Secret Key') }}</label>
                                <input type="text" name="maintenance_secret_key"
                                    value="{{ getOption('maintenance_secret_key') }}" minlength="6"
                                    class="form-control zForm-control maintenance_secret_key">
                            </div>
                            <div class="col-xxl-4 col-md-6">
                                <label class="zForm-label">{{ __('Maintenance Mode Url') }} </label>
                                <input type="text" value="{{getOption('maintenance_secret_key')}}"
                                    class="form-control zForm-control maintenance_mode_url" disabled>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end g-12 flex-wrap mt-25">
                            <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{
                                __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('script')
<script>
    'use strict'
        let getUrl = "{{ url('') }}";
        const maintenanceSecretKey = "{{ getOption('maintenance_secret_key') }}";
        const maintenanceModeConst = "{{ getOption('maintenance_mode') }}";
</script>
<script src="{{ asset('sadmin/js/maintenance-mode.js') }}"></script>
@endpush
