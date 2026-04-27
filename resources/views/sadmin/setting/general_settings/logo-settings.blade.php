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
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack bd-b-one bd-c-stroke mb-20 pb-20">{{ $title }}
                    </h4>
                    <form class="ajax" action="{{ route('super-admin.setting.application-settings.update') }}"
                        method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                        @csrf
                        <div class="row rg-15">
                            <div class="col-xl-3 col-md-4 col-sm-6">

                                <div class="zImage-upload-details">
                                    <div class="zImage-inside">
                                        <div class="d-flex pb-12">
                                            <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                        </div>
                                        <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                            {{ __('Drag & drop files here') }}
                                        </p>
                                    </div>
                                    <label for="zImageUpload" class="zForm-label">{{ __('App Preloader') }}</label>
                                    <div class="upload-img-box">
                                        @if (getOption('app_preloader'))
                                        <img src="{{ getSettingImage('app_preloader') }}" />
                                        @else
                                        <img src="" />
                                        @endif
                                        <input type="file" name="app_preloader" id="zImageUpload"
                                            accept="image/*,video/*" onchange="previewFile(this)" />
                                    </div>
                                </div>

                                @if ($errors->has('app_preloader'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                    {{ $errors->first('app_preloader') }}</span>
                                @endif
                                <p class="fs-14 fw-500 text-para-text pt-10">
                                    <span>
                                        <span>{{ __('Recommend Size') }}:</span>
                                        140 x 40
                                    </span>
                                </p>
                            </div>

                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="zImage-upload-details">
                                    <div class="zImage-inside">
                                        <div class="d-flex pb-12">
                                            <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                        </div>
                                        <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                            {{ __('Drag & drop file shere') }}
                                        </p>
                                    </div>
                                    <label for="zImageUpload" class="zForm-label">{{ __('App Logo') }}</label>
                                    <div class="upload-img-box">
                                        @if (getOption('app_logo'))
                                        <img src="{{ getSettingImage('app_logo') }}" />
                                        @else
                                        <img src="" />
                                        @endif
                                        <input type="file" name="app_logo" id="zImageUpload" accept="image/*,video/*"
                                            onchange="previewFile(this)" />
                                    </div>
                                </div>
                                @if ($errors->has('app_logo'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                    {{ $errors->first('app_logo') }}</span>
                                @endif
                                <p class="fs-14 fw-500 text-para-text pt-10">
                                    <span>
                                        <span>{{ __('Recommend Size') }}:</span>
                                        140 x 40
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-3">
                                <div class="primary-form-group">
                                    <div class="primary-form-group-wrap zImage-upload-details">
                                        <div class="zImage-inside">
                                            <div class="d-flex pb-12">
                                                <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                            </div>
                                            <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                                {{ __('Drag & drop file shere') }}
                                            </p>
                                        </div>
                                        <label for="zImageUpload" class="form-label">{{ __('App Logo White') }}</label>
                                        <div class="upload-img-box">
                                            @if (getOption('app_logo_white'))
                                            <img src="{{ getSettingImage('app_logo_white') }}" />
                                            @else
                                            <img src="" />
                                            @endif
                                            <input type="file" name="app_logo_white" id="zImageUpload"
                                                accept="image/*,video/*" onchange="previewFile(this)" />
                                        </div>
                                    </div>
                                </div>
                                @if ($errors->has('app_logo_white'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                    {{ $errors->first('app_logo_white') }}</span>
                                @endif
                                <p>
                                    <span class="text-black">
                                        <span class="text-black">{{ __('Recommend Size') }}:</span>
                                        140 x 40
                                    </span>
                                </p>
                            </div>


                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="zImage-upload-details">
                                    <div class="zImage-inside">
                                        <div class="d-flex pb-12">
                                            <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                        </div>
                                        <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                            {{ __('Drag & drop files here') }}
                                        </p>
                                    </div>
                                    <label for="zImageUpload" class="zForm-label">{{ __('App Fav Icon') }}</label>
                                    <div class="upload-img-box">
                                        @if (getOption('app_fav_icon'))
                                        <img src="{{ getSettingImage('app_fav_icon') }}" />
                                        @else
                                        <img src="" />
                                        @endif
                                        <input type="file" name="app_fav_icon" id="zImageUpload"
                                            accept="image/*,video/*" onchange="previewFile(this)" />
                                    </div>
                                </div>
                                @if ($errors->has('app_fav_icon'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                    {{ $errors->first('app_fav_icon') }}</span>
                                @endif
                                <p class="fs-14 fw-500 text-para-text pt-10">
                                    <span>
                                        <span>{{ __('Recommend Size') }}:</span>
                                        16 x 16
                                    </span>
                                </p>
                            </div>
                            <div class="col-xl-3 col-md-4 col-sm-6">
                                <div class="zImage-upload-details">
                                    <div class="zImage-inside">
                                        <div class="d-flex pb-12">
                                            <img src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt="" />
                                        </div>
                                        <p class="fs-15 fw-500 lh-16 text-1b1c17">
                                            {{ __('Drag & drop files here') }}
                                        </p>
                                    </div>
                                    <label for="zImageUpload" class="zForm-label">{{ __('Auth Page Image') }}</label>
                                    <div class="upload-img-box">
                                        @if (getOption('login_left_image'))
                                        <img src="{{ getSettingImage('login_left_image') }}" />
                                        @else
                                        <img src="" />
                                        @endif
                                        <input type="file" name="login_left_image" id="zImageUpload"
                                            accept="image/*,video/*" onchange="previewFile(this)" />
                                    </div>
                                </div>
                                @if ($errors->has('login_left_image'))
                                <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                    {{ $errors->first('login_left_image') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-none">
                            <div class="item-top mb-30">
                                <h4>{{ __('Color Settings') }}</h4>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-lg-3">
                                    <label>{{ __('Design') }} <span class="text-danger">*</span></label>
                                </div>
                                <div class="col-lg-9 mb-15">
                                    <input type="radio" id="default" name="app_color_design_type" value="1" {{
                                        empty(getOption('app_color_design_type')) || getOption('app_color_design_type')
                                        ? 'checked' : '' }} required>
                                    <label for="default">{{ __('Default') }}</label>
                                    <br>
                                    <input type="radio" id="custom" name="app_color_design_type" value="2" {{
                                        getOption('app_color_design_type')==2 ? 'checked' : '' }}>
                                    <label for="custom">{{ __('Custom') }}</label>
                                    <br>
                                </div>
                            </div>
                            <div class="customDiv">
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Primary Color') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker">
                                            <label for="colorPicker1" class="mb-0">
                                                <input type="color" name="app_primary_color"
                                                    value="{{ empty(getOption('app_primary_color')) ? '#FF671B' : getOption('app_primary_color') }}"
                                                    id="colorPicker1">
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Secondary Color') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker">
                                            <label for="colorPicker2" class="mb-0">
                                                <input type="color" name="app_secondary_color"
                                                    value="{{ empty(getOption('app_secondary_color')) ? '#111111' : getOption('app_secondary_color') }}"
                                                    id="colorPicker2">
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Text Color') }} <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker">
                                            <label for="colorPicker3" class="mb-0">
                                                <input type="color" name="app_text_color"
                                                    value="{{ empty(getOption('app_text_color')) ? '#585858' : getOption('app_text_color') }}"
                                                    id="colorPicker3">
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Section Background Color') }}
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker">
                                            <label for="colorPicker4" class="mb-0">
                                                <input type="color" name="app_section_bg_color"
                                                    value="{{ empty(getOption('app_section_bg_color')) ? '#FFFAF7' : getOption('app_section_bg_color') }}"
                                                    id="colorPicker4">
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label>{{ __('Hero Background Color') }}<span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-lg-9 mb-15">
                                        <span class="color-picker d-flex flex-wrap">
                                            <label for="colorPicker8" class="mb-0 me-3">
                                                <input class="color1" type="color" name="app_hero_bg_color1"
                                                    value="{{ getOption('app_hero_bg_color1') }}" id="colorPicker8">
                                            </label>
                                            <label for="colorPicker9" class="mb-0 me-3">
                                                <input class="color2" type="color" name="app_hero_bg_color2"
                                                    value="{{ getOption('app_hero_bg_color2') }}" id="colorPicker9">
                                            </label>
                                        </span>
                                        <div id="gradient" class="p-5">
                                            <input class="app_hero_bg_color" type="hidden" name="app_hero_bg_color"
                                                value="{{ getOption('app_hero_bg_color') }}">
                                            <h5 class="text-white">{{ __('Current CSS Background') }}</h5>
                                            <h6 id="textContent" class="text-white"></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex g-12 flex-wrap mt-25">
                            <button type="submit"
                                class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                                __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
