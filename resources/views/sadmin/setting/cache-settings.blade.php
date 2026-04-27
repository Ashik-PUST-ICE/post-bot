@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
    <div class="">
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
                    <div class="item-top mb-30">
                        <h2>{{ __(@$pageTitle) }}</h2>
                    </div>
                    <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                        <p class="fs-16 fw-400 lh-22 text-textBlack">{{ __('Clear View Cache') }}</p>
                        <a href="{{ route('super-admin.setting.cache-update', 1) }}"
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white d-inline-block">{{
                            __('Click Here') }}</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                        <p class="fs-16 fw-400 lh-22 text-textBlack">{{ __('Clear Route Cache') }}</p>
                        <a href="{{ route('super-admin.setting.cache-update', 2) }}"
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white d-inline-block">{{
                            __('Click Here') }}</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                        <p class="fs-16 fw-400 lh-22 text-textBlack">{{ __('Clear Config Cache') }}</p>
                        <a href="{{ route('super-admin.setting.cache-update', 3) }}"
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white d-inline-block">{{
                            __('Click Here') }}</a>
                    </div>
                    <div class="d-flex justify-content-between align-items-center g-10 mb-10 flex-column flex-sm-row">
                        <p class="fs-16 fw-400 lh-22 text-textBlack">{{ __('Application Clear Cache') }}</p>
                        <a href="{{ route('super-admin.setting.cache-update', 4) }}"
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white d-inline-block">{{
                            __('Click Here') }}</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection




