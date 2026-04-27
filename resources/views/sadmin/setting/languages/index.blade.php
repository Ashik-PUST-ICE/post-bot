@extends('sadmin.layouts.app')
@section('content')
    @push('title')
        {{ $title }}
    @endpush
    <!-- Page content area start -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

        <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>

        <input type="hidden" id="language-route" value="{{ route('super-admin.setting.languages.index') }}">

        <div class="table-wrap-one">
            <div
                class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <div class="search-one flex-grow-1 max-w-207">
                        <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt=""/>
                        </button>
                        <input type="text" id="superAdminMultiLanguageSearch" placeholder="{{ __('Search here...') }}"/>
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <button
                        class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                        type="button" data-bs-toggle="modal" data-bs-target="#add-modal">
                        {{ __('+ Add Language') }}
                    </button>
                </div>
            </div>
            <table class="table zTable zTable-last-item-right" id="commonDataTable">
                <thead>
                <tr>
                    <th scope="col">
                        <div>{{ __('Flag') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('Language') }}</div>
                    </th>
                    <th scope="col">
                        <div class="text-nowrap">{{ __('ISO code') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('RTL') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('Font') }}</div>
                    </th>
                    <th scope="col">
                        <div>{{ __('Action') }}</div>
                    </th>
                </tr>
                </thead>
            </table>
        </div>

    </div>
    <!-- Page content area end -->

    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Add Language') }}</h4>
                    <button type="button"
                            class="border-0 p-0 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{ route('super-admin.setting.languages.store') }}" method="post"
                      data-handler="languageHandler" enctype="multipart/form-data">
                    @csrf

                    <div class="row rg-20 pb-20">
                        <div class="">
                            <label for="currentPassword" class="zForm-label">{{ __('Language') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" name="language"
                                   placeholder="{{ __('Language') }}">
                        </div>
                        <div class="">
                            <label for="iso_code" class="zForm-label">{{ __('ISO Code') }} <span
                                    class="text-danger">*</span></label>
                            <select name="iso_code" class="primary-form-control" id="sf-select-modal-add">
                                <option value="">--{{ __('Select ISO Code') }}--</option>
                                @foreach (languageIsoCode() as $code => $isoCountryName)
                                    <option value="{{ $code }}">
                                        {{ $isoCountryName . '(' . $code . ')' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">

                            <div class="zImage-upload-details mw-100">
                                <div class="zImage-inside">
                                    <div class="d-flex pb-12"><img
                                            src="{{ asset('assets/images/icon/upload-img-1.svg') }}" alt=""/>
                                    </div>
                                    <p class="fs-15 fw-500 lh-16 text-1b1c17">{{ __('Drag & drop files here') }}
                                    </p>
                                </div>
                                <label for="zImageUpload" class="zForm-label">{{ __('Flag') }} <span
                                        class="text-mime-type">(jpeg,png,jpg,svg,webp)</span> <span
                                        class="text-danger">*</span></label>
                                <div class="upload-img-box">
                                    <img src=""/>
                                    <input type="file" name="flag" id="flag" accept="image/*"
                                           onchange="previewFile(this)"/>
                                </div>
                            </div>

                        </div>

                        <div class="col-12">

                                <label for="attachmentFile" class="zForm-label">{{ __('Font File') }}</label>
                                <input type="file" class="form-control zForm-control" id="attachmentFile"
                                       accept="application/pdf" name="font">
                                @if ($errors->has('font'))
                                    <span class="text-danger"><i class="fas fa-exclamation-triangle"></i>
                                {{ $errors->first('font') }}</span>
                                @endif

                        </div>
                        <div class="col-12">
                            <label class="zForm-label" for="rtl">{{ __('RTL Supported') }} <span
                                    class="text-danger">*</span></label>
                            <select name="rtl" class="sf-select-without-search">
                                <option value="0">{{ __('No') }}</option>
                                <option value="1">{{ __('Yes') }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="d-flex form-check ps-0">
                                <div class="zCheck form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="1" name="default"
                                           role="switch"
                                           id="flexCheckChecked"/>
                                </div>
                                <label class="form-check-label ps-3" for="flexCheckChecked">
                                    {{ __('Default Language') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex g-12 flex-wrap pt-20 bd-t-one bd-c-light-border">
                        <button
                            class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                            type="submit">{{
                        __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->

    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">

            </div>
        </div>
    </div>
    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{ asset('assets/js/languages.js') }}"></script>
@endpush
