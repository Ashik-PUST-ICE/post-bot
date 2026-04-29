@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h4 class="fs-20 fw-700 text-textBlack">{{ __('Email Templates') }}</h4>
            <p class="fs-13 text-para-text mt-3">{{ __('Manage email templates for order confirmations, shipping updates, and custom messages.') }}</p>
        </div>
        <a href="{{ route('admin.mail.config') }}"
            class="py-10 px-16 bd-one bd-ra-6 bd-c-stroke bg-white fs-13 fw-600 text-para-text d-flex align-items-center cg-8">
            <i class="fa-solid fa-gear"></i> {{ __('Mail Settings') }}
        </a>
    </div>

    {{-- Template Cards --}}
    <div class="row rg-20">
        @foreach($templates as $template)
        <div class="col-xl-4 col-md-6">
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-20 h-100 d-flex flex-column">
                <div class="d-flex align-items-start cg-12 mb-15">
                    <div class="wh-42 bd-ra-8 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="background:#6366f11a;">
                        @php
                            $icons = [
                                'order_confirmation' => 'fa-circle-check',
                                'order_shipped'      => 'fa-truck',
                                'payment_received'   => 'fa-credit-card',
                                'custom_message'     => 'fa-pen',
                            ];
                            $icon = $icons[$template->category] ?? 'fa-envelope';
                        @endphp
                        <i class="fa-solid {{ $icon }} fs-17" style="color:#6366f1;"></i>
                    </div>
                    <div>
                        <h5 class="fs-15 fw-700 text-textBlack">{{ $template->title }}</h5>
                        <p class="fs-12 text-para-text mt-2">{{ \Illuminate\Support\Str::limit($template->subject, 50) }}</p>
                    </div>
                </div>

                <div class="flex-fill">
                    <p class="fs-12 text-para-text"
                        style="white-space:pre-wrap;max-height:80px;overflow:hidden;mask-image:linear-gradient(to bottom,#000 60%,transparent);">{{ strip_tags($template->body) }}</p>
                </div>

                <div class="d-flex cg-8 mt-15 pt-15 bd-t-one bd-c-stroke">
                    <button type="button"
                        class="flex-fill py-9 bd-one bd-ra-6 fs-13 fw-600 editTemplateBtn"
                        style="border-color:#6366f1;background:#6366f11a;color:#6366f1;"
                        data-id="{{ $template->id }}">
                        <i class="fa-solid fa-pen me-5"></i>{{ __('Edit') }}
                    </button>
                    <button type="button"
                        class="flex-fill py-9 bd-one bd-ra-6 fs-13 fw-600 sendTemplateBtn"
                        style="border-color:#10b981;background:#10b9811a;color:#10b981;"
                        data-id="{{ $template->id }}"
                        data-subject="{{ $template->subject }}"
                        data-body="{{ e(strip_tags($template->body)) }}">
                        <i class="fa-solid fa-paper-plane me-5"></i>{{ __('Send') }}
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- ── Edit Template Modal ──────────────────────────────────────────────────── --}}
<div class="modal fade" id="editTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bd-ra-10 p-10">
            <div class="d-flex justify-content-between align-items-center pb-15 mb-15 bd-b-one bd-c-stroke">
                <h5 class="fs-17 fw-700 text-textBlack">{{ __('Edit Email Template') }}</h5>
                <button type="button" class="border-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times fs-16"></i>
                </button>
            </div>
            <form id="editTemplateForm">
                @csrf
                <input type="hidden" name="id" id="editTemplateId">

                <div class="row rg-15">
                    <div class="col-12">
                        <div class="bd-one bd-c-stroke bd-ra-6 p-12" style="background:#f0f9ff;">
                            <p class="fs-12 fw-600 text-textBlack mb-5">{{ __('Available Placeholders') }}</p>
                            <p class="fs-11 text-para-text">
                                {customer_name} &nbsp;·&nbsp; {business_name} &nbsp;·&nbsp; {order_id} &nbsp;·&nbsp;
                                {amount} &nbsp;·&nbsp; {payment_method} &nbsp;·&nbsp; {tracking_id} &nbsp;·&nbsp;
                                {courier_name} &nbsp;·&nbsp; {delivery_date} &nbsp;·&nbsp; {transaction_id} &nbsp;·&nbsp; {message}
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="editSubject" class="form-control zForm-control" required>
                    </div>
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Body') }} <span class="text-danger">*</span></label>
                        <textarea name="body" id="editBody" class="form-control zForm-control"
                            rows="10" style="font-family:monospace;font-size:13px;" required></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end cg-10 mt-20 pt-15 bd-t-one bd-c-stroke">
                    <button type="button" class="py-10 px-20 bd-one bd-ra-6 bd-c-stroke bg-white fs-13 fw-500 text-para-text"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="saveTemplateBtn"
                        class="py-10 px-24 bd-one bd-ra-6 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                        <i class="fa-solid fa-save me-6"></i>{{ __('Save Template') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── Send Email Modal ─────────────────────────────────────────────────────── --}}
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:780px;">
        <div class="modal-content border-0 bd-ra-10 p-10">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center pb-15 mb-15 bd-b-one bd-c-stroke">
                <div>
                    <h5 class="fs-17 fw-700 text-textBlack">
                        <i class="fa-solid fa-paper-plane me-8" style="color:#6366f1;"></i>{{ __('Send Email to Customer') }}
                    </h5>
                    <p class="fs-12 text-para-text mt-2">{{ __('Fill in the customer details and the email will be composed automatically.') }}</p>
                </div>
                <button type="button" class="border-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times fs-16"></i>
                </button>
            </div>

            <form id="sendEmailForm">
                @csrf
                <div class="row rg-0" style="gap:0;">

                    {{-- ── Left: Inputs ─────────────────────────────────────── --}}
                    <div class="col-md-6 pe-md-12">

                        {{-- Step 1: Recipient --}}
                        <div class="bd-one bd-c-stroke bd-ra-8 p-15 mb-12">
                            <p class="fs-12 fw-700 text-textBlack mb-10">
                                <span class="d-inline-flex wh-20 bd-ra-50 text-white fw-700 fs-10 align-items-center justify-content-center me-6"
                                    style="background:#6366f1;">1</span>
                                {{ __('Recipient') }}
                            </p>
                            <div class="mb-10">
                                <label class="zForm-label">{{ __('Customer Name') }}</label>
                                <input type="text" id="varCustomerName" class="form-control zForm-control var-input"
                                    data-var="customer_name" placeholder="{{ __('e.g. Rahim Uddin') }}">
                            </div>
                            <div>
                                <label class="zForm-label">{{ __('Customer Email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="to_email" id="sendToEmail"
                                    class="form-control zForm-control" placeholder="customer@gmail.com" required>
                                <p class="fs-11 text-para-text mt-4">{{ __('Enter the customer\'s email address to send the mail to.') }}</p>
                            </div>
                        </div>

                        {{-- Step 2: Template --}}
                        <div class="bd-one bd-c-stroke bd-ra-8 p-15 mb-12">
                            <p class="fs-12 fw-700 text-textBlack mb-10">
                                <span class="d-inline-flex wh-20 bd-ra-50 text-white fw-700 fs-10 align-items-center justify-content-center me-6"
                                    style="background:#6366f1;">2</span>
                                {{ __('Choose Template') }}
                            </p>
                            <select id="useTemplateSelect" class="form-control zForm-control mb-10">
                                <option value="">— {{ __('Select a template') }} —</option>
                                @foreach($templates as $t)
                                <option value="{{ $t->id }}"
                                    data-subject="{{ $t->subject }}"
                                    data-body="{{ e(strip_tags($t->body)) }}">{{ $t->title }}</option>
                                @endforeach
                            </select>
                            <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="sendSubject" class="form-control zForm-control"
                                placeholder="{{ __('Email subject line') }}" required>
                        </div>

                        {{-- Step 3: Placeholder fields (dynamic) --}}
                        <div class="bd-one bd-c-stroke bd-ra-8 p-15" id="placeholderSection" style="display:none;">
                            <p class="fs-12 fw-700 text-textBlack mb-10">
                                <span class="d-inline-flex wh-20 bd-ra-50 text-white fw-700 fs-10 align-items-center justify-content-center me-6"
                                    style="background:#6366f1;">3</span>
                                {{ __('Fill In Details') }}
                            </p>
                            <p class="fs-11 text-para-text mb-10">{{ __('These values will replace the placeholders in your email automatically.') }}</p>
                            <div id="placeholderFields" class="row rg-10"></div>
                        </div>

                    </div>

                    {{-- ── Right: Live Preview ───────────────────────────────── --}}
                    <div class="col-md-6 ps-md-12">
                        <div class="bd-one bd-c-stroke bd-ra-8 overflow-hidden h-100 d-flex flex-column" style="min-height:360px;">
                            <div class="px-15 py-10 bd-b-one bd-c-stroke d-flex align-items-center cg-8"
                                style="background:#f8fafc;">
                                <i class="fa-solid fa-eye fs-12 text-para-text"></i>
                                <p class="fs-12 fw-600 text-para-text">{{ __('Email Preview') }}</p>
                            </div>

                            {{-- Fake email client header --}}
                            <div class="px-15 py-10 bd-b-one bd-c-stroke" style="background:#fff;">
                                <p class="fs-11 text-para-text">
                                    <strong class="text-textBlack">{{ __('To:') }}</strong>
                                    <span id="previewTo" class="ms-5">—</span>
                                </p>
                                <p class="fs-11 text-para-text mt-4">
                                    <strong class="text-textBlack">{{ __('Subject:') }}</strong>
                                    <span id="previewSubject" class="ms-5">—</span>
                                </p>
                            </div>

                            {{-- Body preview --}}
                            <div class="flex-fill p-15 overflow-auto" style="background:#fff;">
                                <p id="previewBody"
                                    class="fs-13 text-para-text"
                                    style="white-space:pre-wrap;line-height:1.7;">
                                    {{ __('Select a template to see preview here.') }}
                                </p>
                            </div>

                            {{-- Hidden final body sent to server --}}
                            <textarea name="body" id="sendBody" style="display:none;" required></textarea>
                        </div>
                    </div>

                </div>{{-- /row --}}

                <div class="d-flex justify-content-end cg-10 mt-15 pt-15 bd-t-one bd-c-stroke">
                    <button type="button" class="py-10 px-20 bd-one bd-ra-6 bd-c-stroke bg-white fs-13 fw-500 text-para-text"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" id="sendEmailBtn"
                        class="py-10 px-24 bd-one bd-ra-6 fs-13 fw-600 text-white"
                        style="background:#6366f1;border-color:#6366f1;">
                        <i class="fa-solid fa-paper-plane me-6"></i>{{ __('Send Email') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Routes --}}
<input type="hidden" id="getTemplateRoute"    value="{{ route('admin.mail.templates.get') }}">
<input type="hidden" id="updateTemplateRoute" value="{{ route('admin.mail.templates.update') }}">
<input type="hidden" id="sendMailRoute"       value="{{ route('admin.mail.send') }}">
{{-- Labels for mail-templates.js (no Blade inside static JS) --}}
<input type="hidden" id="lblSaving"           value="{{ __('Saving...') }}">
<input type="hidden" id="lblSaveTemplate"     value="{{ __('Save Template') }}">
<input type="hidden" id="lblSending"          value="{{ __('Sending...') }}">
<input type="hidden" id="lblSendEmail"        value="{{ __('Send Email') }}">
<input type="hidden" id="lblServerError"      value="{{ __('Server error. Please try again.') }}">
<input type="hidden" id="lblLoadFailed"       value="{{ __('Failed to load template.') }}">
<input type="hidden" id="lblPreviewDefault"   value="{{ __('Select a template to see preview here.') }}">
<input type="hidden" id="lblBusinessName"     value="{{ getOption('app_name', config('app.name')) }}">
{{-- Human-readable names for each placeholder key --}}
<input type="hidden" id="phLabels" value='{
    "customer_name":  "{{ __('Customer Name') }}",
    "business_name":  "{{ __('Business Name') }}",
    "order_id":       "{{ __('Order ID') }}",
    "amount":         "{{ __('Amount / Price') }}",
    "payment_method": "{{ __('Payment Method (e.g. bKash, Nagad, COD)') }}",
    "tracking_id":    "{{ __('Tracking ID') }}",
    "courier_name":   "{{ __('Courier Name') }}",
    "delivery_date":  "{{ __('Delivery Date') }}",
    "transaction_id": "{{ __('Transaction ID') }}",
    "date":           "{{ __('Date') }}",
    "message":        "{{ __('Custom Message') }}"
}'>
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/mail-templates.js') }}"></script>
@endpush
