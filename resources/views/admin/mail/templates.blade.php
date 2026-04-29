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
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bd-ra-10 p-10">
            <div class="d-flex justify-content-between align-items-center pb-15 mb-15 bd-b-one bd-c-stroke">
                <h5 class="fs-17 fw-700 text-textBlack">
                    <i class="fa-solid fa-paper-plane me-8" style="color:#6366f1;"></i>{{ __('Send Email to Customer') }}
                </h5>
                <button type="button" class="border-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times fs-16"></i>
                </button>
            </div>
            <form id="sendEmailForm">
                @csrf
                <div class="row rg-15">
                    <div class="col-md-8">
                        <label class="zForm-label">{{ __('To Email') }} <span class="text-danger">*</span></label>
                        <input type="email" name="to_email" id="sendToEmail"
                            class="form-control zForm-control" placeholder="customer@example.com" required>
                    </div>
                    <div class="col-md-4">
                        <label class="zForm-label">{{ __('Use Template') }}</label>
                        <select id="useTemplateSelect" class="form-control zForm-control">
                            <option value="">— {{ __('Select a template') }} —</option>
                            @foreach($templates as $t)
                            <option value="{{ $t->id }}"
                                data-subject="{{ $t->subject }}"
                                data-body="{{ e(strip_tags($t->body)) }}">{{ $t->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Subject') }} <span class="text-danger">*</span></label>
                        <input type="text" name="subject" id="sendSubject" class="form-control zForm-control" required>
                    </div>
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                        <textarea name="body" id="sendBody" class="form-control zForm-control"
                            rows="8" placeholder="{{ __('Write your message here...') }}" required></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-end cg-10 mt-20 pt-15 bd-t-one bd-c-stroke">
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
<input type="hidden" id="lblSaving"       value="{{ __('Saving...') }}">
<input type="hidden" id="lblSaveTemplate" value="{{ __('Save Template') }}">
<input type="hidden" id="lblSending"      value="{{ __('Sending...') }}">
<input type="hidden" id="lblSendEmail"    value="{{ __('Send Email') }}">
<input type="hidden" id="lblServerError"  value="{{ __('Server error. Please try again.') }}">
<input type="hidden" id="lblLoadFailed"   value="{{ __('Failed to load template.') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/mail-templates.js') }}"></script>
@endpush
