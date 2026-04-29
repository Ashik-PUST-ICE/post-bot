@extends('admin.layouts.app')
@push('title')
    {{ $title }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-20">
            <div class="d-flex align-items-center cg-10">
                <a href="{{ route('admin.inbox.index') }}" class="text-para-text fs-20">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h4 class="fs-18 fw-600 text-textBlack">
                        {{ $conversation->contact_name ?? __('Unknown Contact') }}
                    </h4>
                    <p class="fs-13 text-para-text">
                        <i class="{{ platformIcons($conversation->platform_type) }}"
                           style="color:{{ platformColors($conversation->platform_type) }}"></i>
                        {{ platformTypes($conversation->platform_type) }}
                        @if($conversation->platformConnection)
                            · {{ $conversation->platformConnection->platform_name }}
                        @endif
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center cg-10">
                <button type="button" id="openSendEmailBtn"
                    class="py-9 px-16 bd-one bd-ra-6 fs-13 fw-600 d-flex align-items-center cg-6"
                    style="border-color:#6366f1;background:#6366f11a;color:#6366f1;"
                    title="{{ __('Send Email to this Customer') }}">
                    <i class="fa-solid fa-envelope"></i>
                    <span class="d-none d-sm-inline">{{ __('Send Email') }}</span>
                </button>
                <select class="form-control zForm-control" id="conversationStatusSelect"
                    data-id="{{ $conversation->id }}"
                    data-route="{{ route('admin.inbox.update.status', $conversation->id) }}">
                    @foreach(conversationStatuses() as $val => $label)
                        <option value="{{ $val }}" {{ $conversation->status == $val ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="row rg-20">
            {{-- Chat Thread --}}
            <div class="col-xl-9">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white overflow-hidden d-flex flex-column"
                    style="min-height:500px;">

                    {{-- Messages --}}
                    <div class="flex-grow-1 p-20 d-flex flex-column rg-12" id="messageThread"
                        style="overflow-y:auto; max-height:500px;">
                        @include('admin.inbox._messages', ['messages' => $messages, 'conversation' => $conversation])
                    </div>

                    {{-- Reply Box --}}
                    <div class="bd-t-one bd-c-stroke p-15">
                        <form id="replyForm" method="POST">
                            @csrf
                            {{-- Template Picker --}}
                            <div class="mb-10 d-flex align-items-center cg-8" id="templatePickerWrap" style="display:none!important;">
                                <button type="button" id="btnPickTemplate"
                                    class="py-5 px-12 bd-one bd-c-stroke bd-ra-4 fs-12 fw-500 text-para-text bg-white">
                                    <i class="fa-solid fa-bolt me-4"></i>{{ __('Quick Reply') }}
                                </button>
                                <div class="position-relative" id="templateDropdown" style="display:none;">
                                    <div class="border bd-ra-8 bg-white shadow-sm overflow-auto"
                                        style="max-height:220px; min-width:280px; position:absolute; bottom:110%; left:0; z-index:999;">
                                        <div class="px-12 py-8 bd-b-one bd-c-stroke">
                                            <input type="text" id="templateSearch" class="form-control zForm-control fs-12 py-5"
                                                placeholder="{{ __('Search templates…') }}">
                                        </div>
                                        <ul class="list-unstyled mb-0" id="templateList">
                                            <li class="px-14 py-8 fs-13 text-para-text">{{ __('Loading…') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-end cg-10">
                                <textarea name="body" id="replyBody" rows="3"
                                    class="form-control zForm-control flex-grow-1"
                                    placeholder="{{ __('Type your reply…') }}" style="resize:none;"></textarea>
                                <div class="d-flex flex-column rg-6">
                                    <button type="button" id="btnPickTemplate2" title="{{ __('Quick Reply') }}"
                                        class="py-10 px-14 bd-one bd-ra-4 bd-c-stroke bg-white text-para-text fs-14 flex-shrink-0">
                                        <i class="fa-solid fa-bolt"></i>
                                    </button>
                                    <button type="submit"
                                        class="py-10 px-14 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 flex-shrink-0">
                                        <i class="fa-solid fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Sidebar Info --}}
            <div class="col-xl-3">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-20 d-flex flex-column rg-15">
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('Contact Info') }}</h5>
                    <div class="d-flex flex-column rg-10">
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Name') }}</p>
                            <p class="fs-14 fw-600 text-textBlack">{{ $conversation->contact_name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Platform ID') }}</p>
                            <p class="fs-13 fw-400 text-textBlack">{{ $conversation->contact_id ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('AI Replied') }}</p>
                            <p class="fs-13 fw-400">
                                @if($conversation->ai_replied)
                                    <span class="text-success"><i class="fa-solid fa-check me-4"></i> {{ __('Yes') }}</span>
                                @else
                                    <span class="text-para-text">{{ __('No') }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Human Taken Over') }}</p>
                            <p class="fs-13 fw-400">
                                @if($conversation->human_taken_over)
                                    <span class="text-warning"><i class="fa-solid fa-user me-4"></i> {{ __('Yes') }}</span>
                                @else
                                    <span class="text-para-text">{{ __('No') }}</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="fs-12 fw-500 text-para-text text-uppercase">{{ __('Total Messages') }}</p>
                            <p class="fs-14 fw-600 text-textBlack" id="totalMsgCount">{{ $messages->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="convId"           value="{{ $conversation->id }}">
    <input type="hidden" id="statusRoute"      value="{{ route('admin.inbox.update.status', $conversation->id) }}">
    <input type="hidden" id="replyRoute"       value="{{ route('admin.inbox.reply', $conversation->id) }}">
    <input type="hidden" id="getMessagesRoute" value="{{ route('admin.inbox.messages', $conversation->id) }}">
    <input type="hidden" id="forInboxRoute"    value="{{ route('admin.reply-templates.for.inbox') }}">
    <input type="hidden" id="noTemplatesText"  value="{{ __('No templates found.') }}">
    <input type="hidden" id="sendMailRoute"        value="{{ route('admin.mail.send') }}">
    <input type="hidden" id="convContactName"      value="{{ $conversation->contact_name ?? '' }}">
    <input type="hidden" id="lblBusinessName"      value="{{ getOption('app_name', config('app.name')) }}">

{{-- ── Send Email to Customer Modal ──────────────────────────────────────────── --}}
<div class="modal fade" id="sendCustomerEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:760px;">
        <div class="modal-content border-0 bd-ra-10 p-10">

            <div class="d-flex justify-content-between align-items-center pb-15 mb-15 bd-b-one bd-c-stroke">
                <div>
                    <h5 class="fs-17 fw-700 text-textBlack">
                        <i class="fa-solid fa-envelope me-8" style="color:#6366f1;"></i>{{ __('Send Email to Customer') }}
                    </h5>
                    <p class="fs-12 text-para-text mt-2">{{ __('Fill in the details — the email will be composed automatically from the template.') }}</p>
                </div>
                <button type="button" class="border-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times fs-16"></i>
                </button>
            </div>

            <form id="customerEmailForm">
                @csrf
                <div class="row rg-0">

                    {{-- ── Left: inputs ─────────────────────────────────────── --}}
                    <div class="col-md-6 pe-md-12">

                        {{-- Recipient --}}
                        <div class="bd-one bd-c-stroke bd-ra-8 p-15 mb-12">
                            <p class="fs-12 fw-700 text-textBlack mb-10">
                                <span class="d-inline-flex wh-20 bd-ra-50 text-white fw-700 fs-10 align-items-center justify-content-center me-6"
                                    style="background:#6366f1;">1</span>
                                {{ __('Customer Details') }}
                            </p>
                            <div class="mb-10">
                                <label class="zForm-label">{{ __('Customer Name') }}</label>
                                <input type="text" id="custNameField" class="form-control zForm-control"
                                    value="{{ $conversation->contact_name ?? '' }}"
                                    placeholder="{{ __('Customer name') }}">
                                <p class="fs-11 text-para-text mt-3">{{ __('Pre-filled from the conversation.') }}</p>
                            </div>
                            <div>
                                <label class="zForm-label">{{ __('Customer Email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="to_email" id="custEmailTo"
                                    class="form-control zForm-control"
                                    placeholder="{{ __('customer@gmail.com') }}" required>
                                <p class="fs-11 text-para-text mt-3">{{ __('Enter the email address to send the mail to.') }}</p>
                            </div>
                        </div>

                        {{-- Template + Subject --}}
                        <div class="bd-one bd-c-stroke bd-ra-8 p-15 mb-12">
                            <p class="fs-12 fw-700 text-textBlack mb-10">
                                <span class="d-inline-flex wh-20 bd-ra-50 text-white fw-700 fs-10 align-items-center justify-content-center me-6"
                                    style="background:#6366f1;">2</span>
                                {{ __('Template & Subject') }}
                            </p>
                            <div class="mb-10">
                                <label class="zForm-label">{{ __('Use Template') }}</label>
                                <select id="custEmailTemplate" class="form-control zForm-control">
                                    <option value="">— {{ __('Pick a template') }} —</option>
                                </select>
                            </div>
                            <div>
                                <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                                <input type="text" name="subject" id="custEmailSubject" class="form-control zForm-control" required>
                            </div>
                        </div>

                        {{-- Dynamic order details --}}
                        <div class="bd-one bd-c-stroke bd-ra-8 p-15" id="custPlaceholderSection" style="display:none;">
                            <p class="fs-12 fw-700 text-textBlack mb-8">
                                <span class="d-inline-flex wh-20 bd-ra-50 text-white fw-700 fs-10 align-items-center justify-content-center me-6"
                                    style="background:#6366f1;">3</span>
                                {{ __('Order Details') }}
                            </p>
                            <p class="fs-11 text-para-text mb-10">{{ __('These values replace the {placeholders} in the template automatically.') }}</p>
                            <div id="custPlaceholderFields" class="row rg-10"></div>
                        </div>

                    </div>

                    {{-- ── Right: live preview ──────────────────────────────── --}}
                    <div class="col-md-6 ps-md-12">
                        <div class="bd-one bd-c-stroke bd-ra-8 overflow-hidden h-100 d-flex flex-column" style="min-height:340px;">
                            <div class="px-15 py-10 bd-b-one bd-c-stroke d-flex align-items-center cg-8" style="background:#f8fafc;">
                                <i class="fa-solid fa-eye fs-12 text-para-text"></i>
                                <p class="fs-12 fw-600 text-para-text">{{ __('Email Preview') }}</p>
                            </div>
                            <div class="px-15 py-10 bd-b-one bd-c-stroke" style="background:#fff;">
                                <p class="fs-11 text-para-text">
                                    <strong class="text-textBlack">{{ __('To:') }}</strong>
                                    <span id="custPreviewTo" class="ms-5">—</span>
                                </p>
                                <p class="fs-11 text-para-text mt-4">
                                    <strong class="text-textBlack">{{ __('Subject:') }}</strong>
                                    <span id="custPreviewSubject" class="ms-5">—</span>
                                </p>
                            </div>
                            <div class="flex-fill p-15 overflow-auto" style="background:#fff;">
                                <p id="custPreviewBody" class="fs-13 text-para-text" style="white-space:pre-wrap;line-height:1.7;">
                                    {{ __('Select a template to see the preview here.') }}
                                </p>
                            </div>
                            <textarea name="body" id="custEmailBody" style="display:none;" required></textarea>
                        </div>
                    </div>

                </div>

                <div class="d-flex justify-content-end cg-10 mt-15 pt-15 bd-t-one bd-c-stroke">
                    <button type="button" class="py-10 px-20 bd-one bd-ra-6 bd-c-stroke bg-white fs-13 fw-500 text-para-text"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="sendCustEmailBtn"
                        class="py-10 px-24 bd-one bd-ra-6 text-white fs-13 fw-600"
                        style="background:#6366f1;border-color:#6366f1;">
                        <i class="fa-solid fa-paper-plane me-6"></i>{{ __('Send Email') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/inbox-show.js') }}"></script>
@endpush
