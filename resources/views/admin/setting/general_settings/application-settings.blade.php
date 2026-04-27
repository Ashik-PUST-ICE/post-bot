@extends('admin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('admin.setting.sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $title }}</h4>
                    <form class="ajax" action="{{ route('admin.setting.application-settings.update') }}" method="POST"
                          enctype="multipart/form-data" data-handler="settingCommonHandler">
                        @csrf
                        <div class="row rg-20">
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('App Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="app_name" value="{{ getOption('app_name') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('App Email') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="app_email" value="{{ getOption('app_email') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('App Contact Number') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="app_contact_number"
                                       value="{{ getOption('app_contact_number') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('App Location') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="app_location" value="{{ getOption('app_location') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('App Copyright') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="app_copyright" value="{{ getOption('app_copyright') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('Footer Text') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="app_footer_text" value="{{ getOption('app_footer_text') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label class="zForm-label">{{ __('Develop By') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="develop_by" value="{{ getOption('develop_by') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div class="col-xxl-4 col-lg-6">
                                <label for="app_timezone" class="zForm-label">{{ __('Timezone') }} <span
                                        class="text-danger">*</span></label>
                                <select name="app_timezone" class="form-control sf-select">
                                    @foreach ($timezones as $timezone)
                                        <option value="{{ $timezone }}" {{ $timezone==getOption('app_timezone') ? 'selected'
                                    : '' }}>
                                            {{ $timezone }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end g-12 flex-wrap mt-20">
                            <button type="submit"
                                    class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{
                            __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
