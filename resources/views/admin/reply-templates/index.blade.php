@extends('admin.layouts.app')
@push('title') {{ $title }} @endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-20">
        <div>
            <h4 class="fs-18 fw-600 text-textBlack">{{ __($title) }}</h4>
            <p class="fs-13 text-para-text mt-4">
                {{ __('Use {customer_name}, {business_name}, {platform} as variables.') }}
            </p>
        </div>
        <button type="button" id="btnAddTemplate"
            class="py-10 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
            <i class="fa-solid fa-plus me-5"></i> {{ __('Add Template') }}
        </button>
    </div>

    <div class="bd-one bd-c-stroke bd-ra-10 bg-white overflow-hidden">
        <table class="table zTable zTable-last-item-right" id="replyTemplateTable">
            <thead>
                <tr>
                    <th><div>{{ __('#') }}</div></th>
                    <th><div>{{ __('Title') }}</div></th>
                    <th><div>{{ __('Content Preview') }}</div></th>
                    <th><div>{{ __('Platform') }}</div></th>
                    <th><div>{{ __('Used') }}</div></th>
                    <th><div>{{ __('Status') }}</div></th>
                    <th><div>{{ __('Action') }}</div></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- Add / Edit Modal --}}
<div class="modal fade" id="templateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-8 p-24">
            <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-stroke pb-18 mb-20">
                <h5 class="fs-16 fw-600 text-textBlack" id="modalTitle">{{ __('Add Template') }}</h5>
                <button type="button" class="border-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            <form class="ajax reset" action="{{ route('admin.reply-templates.store') }}" method="POST"
                data-handler="commonResponseForModal">
                @csrf
                <input type="hidden" name="id" id="templateId">

                <div class="d-flex flex-column rg-16">
                    <div>
                        <label class="zForm-label">{{ __('Title') }} <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="templateTitle"
                            class="form-control zForm-control" placeholder="{{ __('e.g. Business Hours') }}">
                    </div>

                    <div>
                        <label class="zForm-label">{{ __('Platform') }}</label>
                        <select name="platform" id="templatePlatform" class="form-control zForm-control">
                            @foreach(replyTemplatePlatforms() as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="zForm-label">{{ __('Content') }} <span class="text-danger">*</span></label>
                        <textarea name="content" id="templateContent" rows="5"
                            class="form-control zForm-control"
                            placeholder="{{ __('Hello {customer_name}, thank you for reaching {business_name}!') }}"></textarea>
                        <p class="fs-11 text-para-text mt-6">
                            {{ __('Variables:') }}
                            <code class="fs-11">{customer_name}</code>
                            <code class="fs-11">{business_name}</code>
                            <code class="fs-11">{platform}</code>
                        </p>
                    </div>

                    <div class="d-flex form-check ps-0">
                        <div class="zCheck form-check form-switch">
                            <input class="form-check-input mt-0" type="checkbox" name="status"
                                id="templateStatus" value="1" checked>
                        </div>
                        <label class="form-check-label ps-3 fs-13" for="templateStatus">
                            {{ __('Active') }}
                        </label>
                    </div>
                </div>

                <div class="d-flex g-10 mt-20">
                    <button type="button" class="py-10 px-18 bd-one bd-ra-4 bd-c-stroke bg-white text-textBlack fs-13 fw-500"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit"
                        class="py-10 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                        {{ __('Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="rtGetDataRoute" value="{{ route('admin.reply-templates.get.data') }}">
<input type="hidden" id="rtGetInfoRoute" value="{{ route('admin.reply-templates.get.info') }}">
<input type="hidden" id="rtLabelAdd"     value="{{ __('Add Template') }}">
<input type="hidden" id="rtLabelEdit"    value="{{ __('Edit Template') }}">
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/reply-templates.js') }}"></script>
@endpush
