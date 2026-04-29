@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Page Header --}}
    <div class="d-flex align-items-center justify-content-between mb-20">
        <div>
            <h4 class="fs-20 fw-700 text-textBlack">{{ __('Mail Configuration') }}</h4>
            <p class="fs-13 text-para-text mt-3">{{ __('Configure your SMTP settings for sending emails to customers.') }}</p>
        </div>
        <div class="d-flex cg-10">
            <button type="button" id="testMailBtn"
                class="py-10 px-16 bd-one bd-ra-6 bd-c-stroke bg-white fs-13 fw-600 text-para-text d-flex align-items-center cg-8">
                <i class="fa-solid fa-paper-plane"></i> {{ __('Send Test Email') }}
            </button>
        </div>
    </div>

    <div class="row rg-20">

        {{-- ── SMTP Settings Card ───────────────────────────────────────────── --}}
        <div class="col-xl-8">
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-25">
                <h5 class="fs-16 fw-700 text-textBlack mb-20 pb-15 bd-b-one bd-c-stroke">
                    <i class="fa-solid fa-server me-8" style="color:#6366f1;"></i>{{ __('SMTP Settings') }}
                </h5>

                <form id="mailConfigForm">
                    @csrf
                    <div class="row rg-18">

                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Mail Host') }} <span class="text-danger">*</span></label>
                            <input type="text" name="mail_host" class="form-control zForm-control"
                                value="{{ $mail['host'] }}" placeholder="smtp.gmail.com" required>
                        </div>

                        <div class="col-md-3">
                            <label class="zForm-label">{{ __('Port') }} <span class="text-danger">*</span></label>
                            <input type="number" name="mail_port" class="form-control zForm-control"
                                value="{{ $mail['port'] }}" placeholder="587" required>
                        </div>

                        <div class="col-md-3">
                            <label class="zForm-label">{{ __('Encryption') }} <span class="text-danger">*</span></label>
                            <select name="mail_encryption" class="form-control zForm-control">
                                @foreach(['tls' => 'TLS (587)', 'ssl' => 'SSL (465)', 'starttls' => 'STARTTLS', 'none' => 'None'] as $val => $label)
                                    <option value="{{ $val }}" {{ $mail['encryption'] === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('SMTP Username') }} <span class="text-danger">*</span></label>
                            <input type="text" name="mail_username" class="form-control zForm-control"
                                value="{{ $mail['username'] }}" placeholder="your@email.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('SMTP Password') }}</label>
                            <div class="position-relative">
                                <input type="password" name="mail_password" id="mailPassword"
                                    class="form-control zForm-control pe-40"
                                    value="{{ $mail['password'] }}" placeholder="••••••••">
                                <button type="button" id="togglePw"
                                    class="position-absolute end-0 top-50 translate-middle-y border-0 bg-transparent pe-12 text-para-text">
                                    <i class="fa-solid fa-eye fs-14" id="pwIcon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('From Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email" name="mail_from_address" class="form-control zForm-control"
                                value="{{ $mail['from_address'] }}" placeholder="noreply@yourdomain.com" required>
                        </div>

                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('From Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="mail_from_name" class="form-control zForm-control"
                                value="{{ $mail['from_name'] }}" placeholder="{{ config('app.name') }}" required>
                        </div>

                    </div>

                    <div class="d-flex justify-content-end mt-25 pt-20 bd-t-one bd-c-stroke">
                        <button type="submit" id="saveMailBtn"
                            class="py-11 px-28 bd-one bd-ra-6 bd-c-main-color bg-main-color text-white fs-14 fw-600">
                            <i class="fa-solid fa-save me-6"></i>{{ __('Save Configuration') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Quick-Help Card ──────────────────────────────────────────────── --}}
        <div class="col-xl-4">
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-25">
                <h5 class="fs-15 fw-700 text-textBlack mb-15">
                    <i class="fa-solid fa-circle-info me-8" style="color:#6366f1;"></i>{{ __('Common SMTP Providers') }}
                </h5>
                @foreach([
                    ['Gmail',     'smtp.gmail.com',        587, 'tls',  'https://support.google.com/accounts/answer/185833'],
                    ['Outlook',   'smtp-mail.outlook.com', 587, 'tls',  'https://support.microsoft.com/en-us/office/pop-imap-and-smtp-settings'],
                    ['Mailgun',   'smtp.mailgun.org',      587, 'tls',  'https://documentation.mailgun.com'],
                    ['SendGrid',  'smtp.sendgrid.net',     587, 'tls',  'https://docs.sendgrid.com/for-developers/sending-email/integrating-with-the-smtp-api'],
                ] as [$name, $host, $port, $enc, $link])
                <div class="d-flex align-items-start cg-12 mb-14 pb-14 bd-b-one bd-c-stroke">
                    <div class="wh-30 bd-ra-6 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="background:#6366f11a;">
                        <i class="fa-solid fa-envelope fs-12" style="color:#6366f1;"></i>
                    </div>
                    <div>
                        <p class="fs-13 fw-600 text-textBlack">{{ $name }}</p>
                        <p class="fs-11 text-para-text font-monospace">{{ $host }}:{{ $port }} / {{ strtoupper($enc) }}</p>
                        <a href="{{ $link }}" target="_blank" class="fs-11" style="color:#6366f1;">{{ __('View docs') }} →</a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Send Test Email Card --}}
            <div class="bg-white bd-one bd-c-stroke bd-ra-10 p-25 mt-20">
                <h5 class="fs-15 fw-700 text-textBlack mb-15">
                    <i class="fa-solid fa-flask me-8" style="color:#10b981;"></i>{{ __('Test Your Config') }}
                </h5>
                <p class="fs-12 text-para-text mb-12">{{ __('Send a test email to verify your SMTP settings are working correctly.') }}</p>
                <div class="d-flex cg-8">
                    <input type="email" id="testEmailInput" class="form-control zForm-control"
                        placeholder="{{ auth()->user()->email }}">
                    <button type="button" id="sendTestBtn"
                        class="py-10 px-16 bd-one bd-ra-6 fs-13 fw-600 flex-shrink-0"
                        style="border-color:#10b981;background:#10b9811a;color:#10b981;white-space:nowrap;">
                        <i class="fa-solid fa-paper-plane me-5"></i>{{ __('Send') }}
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Routes --}}
<input type="hidden" id="mailSaveRoute" value="{{ route('admin.mail.config.save') }}">
<input type="hidden" id="mailTestRoute" value="{{ route('admin.mail.config.test') }}">
{{-- Labels for mail-config.js (no Blade inside static JS) --}}
<input type="hidden" id="lblSaving"         value="{{ __('Saving...') }}">
<input type="hidden" id="lblSaveConfig"     value="{{ __('Save Configuration') }}">
<input type="hidden" id="lblSending"        value="{{ __('Sending...') }}">
<input type="hidden" id="lblSend"           value="{{ __('Send') }}">
<input type="hidden" id="lblServerError"    value="{{ __('Server error. Please try again.') }}">
<input type="hidden" id="lblEnterTestEmail" value="{{ __('Please enter a test email address.') }}">
@endsection

@push('script')
    <script src="{{ asset('admin/custom/js/mail-config.js') }}"></script>
@endpush
