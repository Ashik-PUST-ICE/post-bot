@extends('sadmin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>
            <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                <input type="hidden" id="language-route" value="{{ route('super-admin.setting.languages.index') }}">

                <div class="d-flex flex-wrap gap-2 item-title justify-content-end mb-20">
                    <a class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                       href="{{route('super-admin.setting.languages.download', $language->id)}}"
                       title="{{ __('Download File') }}">
                        {{ __('Download File') }}
                    </a>
                    <button type="button" class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                            data-bs-toggle="modal"
                            data-bs-target="#importFile"
                            title="{{ __('Import File') }}">
                        {{ __('Import File') }}
                    </button>
                    <button type="button"
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                            data-bs-toggle="modal" data-bs-target="#importModal" title="{{ __('Import Keywords') }}">
                        {{ __('Import Keywords') }}
                    </button>
                    <button type="button"
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white addmore">
                        {{ __('+ Add More') }}
                    </button>
                </div>
                <div class="justify-content-center mb-20 row">
                    <div class="col-md-6">
                        <form id="search-form">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{__('Search Key or Value')}}">
                                <button class="y-13 px-20 bd-one rounded-end-2 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14" type="submit">{{__('Search')}}</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="translations-container" class="dataTables_wrapper">
                    @include('admin.setting.languages.partials.translations_table')
                </div>
            </div>
        </div>
    </div>
    <!-- Add Modal section start -->
    <div class="modal fade" id="importModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-10">
                <form class="ajax" action="{{ route('super-admin.setting.languages.import') }}" method="POST"
                      data-handler="languageHandler">
                    @csrf
                    <input type="hidden" name="current" value="{{ $language->iso_code }}">

                    <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                        <h5 class="fs-18 fw-600 lh-22 text-textBlack">{{ __('Import Language') }}</h5>
                        <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
                                aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>
                    <div class="row rg-20 pb-20">
                        <div class="">
                                <span class="text-danger text-center">{{ __('Note: If you import keywords, your current keywords will be deleted and replaced by the imported keywords.') }}</span>
                        </div>
                        <div class="col-md-12">
                            <label for="status" class="zForm-label">
                                {{ __('Language') }} </label>
                            <select name="import" class="sf-select flex-shrink-0 export" id="inputGroupSelect02">
                                <option value=""> {{ __('Select Option') }} </option>
                                @foreach ($languages as $lang)
                                    <option value="{{ $lang->iso_code }}">{{ __($lang->language) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="bd-t-one bd-c-light-border pt-17 d-flex g-10">
                        <button type="button"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14"
                                data-bs-dismiss="modal" title="Back">{{ __('Back') }}</button>
                        <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                                title="Submit">{{ __('Import') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="importFile" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-10">
                <form class="ajax" action="{{ route('super-admin.setting.languages.upload', $language->id) }}" method="POST"
                      enctype="multipart/form-data" data-handler="languageHandler">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Upload Translated File') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span class="iconify" data-icon="akar-icons:cross"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-30 text-center">
                        <span class="text-warning">
                            {{ __('Upload a valid JSON translation file. Existing translations will be merged with the uploaded file. Keys in the uploaded file will overwrite existing keys.') }}
                        </span>
                        </div>
                        <div class="col-md-12 mb-25">
                            <label for="file" class="label-text-title color-heading font-medium mb-2">
                                {{ __('Select JSON File') }}
                            </label>
                            <input type="file" name="file" class="form-control" accept=".json" required>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14"
                                data-bs-dismiss="modal" title="Back">{{ __('Back') }}</button>
                        <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                                title="Submit">{{ __('Upload') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="updateLangItemRoute"
           value="{{ route('super-admin.setting.languages.update.translate', [$language->id]) }}">
    <input type="hidden" id="language-translate-route"
           value="{{ route('super-admin.setting.languages.translate', [$language->id]) }}">
@endsection

@push('script')
    <script src="{{asset('assets/js/languages.js')}}"></script>
@endpush
