@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Hidden route inputs (read by meta-app.js) --}}
    <input type="hidden" id="metaCheckConnectionRoute" value="{{ route('admin.meta-app.check.connection') }}">

        {{-- Page Header --}}
        <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
            <div>
                <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Meta App Configuration') }}</h4>
                <p class="fs-14 fw-400 text-para-text mt-5">
                    {{ __('Enter your Meta Developer App credentials to connect Facebook, Instagram, and WhatsApp.') }}
                </p>
            </div>
            <a href="https://developers.facebook.com/apps" target="_blank"
                class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack d-flex align-items-center cg-8">
                <i class="fa-brands fa-facebook" style="color:#1877F2"></i>
                {{ __('Open Meta Developer Console') }}
                <i class="fa-solid fa-arrow-up-right-from-square fs-11 text-para-text"></i>
            </a>
        </div>

        {{-- Connection Status Cards --}}
        <div class="row rg-20 pb-26">
            @php
                $statuses = [
                    ['label' => 'Facebook',  'icon' => 'fa-brands fa-facebook',  'color' => '#1877F2', 'ok' => $config->hasFacebook(),   'badge' => 'fb-conn-badge'],
                    ['label' => 'WhatsApp',  'icon' => 'fa-brands fa-whatsapp',  'color' => '#25D366', 'ok' => $config->hasWhatsApp(),   'badge' => 'wa-conn-badge'],
                    ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram', 'color' => '#E1306C', 'ok' => $config->hasInstagram(),  'badge' => 'ig-conn-badge'],
                    ['label' => 'Webhook',   'icon' => 'fa-solid fa-plug',       'color' => '#6366f1', 'ok' => !empty($config->webhook_verify_token), 'badge' => null],
                ];
            @endphp
            @foreach($statuses as $s)
            <div class="col-xl-3 col-md-6">
                <div class="bd-one bd-ra-10 p-18 bg-white d-flex align-items-center cg-14"
                    style="border-color: {{ $s['ok'] ? '#10b981' : '#e5e7eb' }};">
                    <div class="wh-44 bd-ra-50 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="background:{{ $s['color'] }}1a;">
                        <i class="{{ $s['icon'] }} fs-20" style="color:{{ $s['color'] }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="fs-13 fw-500 text-textBlack">{{ $s['label'] }}</p>
                        @if($s['badge'])
                            <div id="{{ $s['badge'] }}" class="conn-badge">
                                @if($s['ok'])
                                    <span class="fs-12 fw-600" style="color:#10b981;">
                                        <i class="fa-solid fa-circle-check me-4"></i>{{ __('Configured') }}
                                    </span>
                                @else
                                    <span class="fs-12 fw-500 text-para-text">
                                        <i class="fa-solid fa-circle-exclamation me-4"></i>{{ __('Not set') }}
                                    </span>
                                @endif
                            </div>
                        @else
                            @if($s['ok'])
                                <span class="fs-12 fw-600" style="color:#10b981;">
                                    <i class="fa-solid fa-circle-check me-4"></i>{{ __('Configured') }}
                                </span>
                            @else
                                <span class="fs-12 fw-500 text-para-text">
                                    <i class="fa-solid fa-circle-exclamation me-4"></i>{{ __('Not set') }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Setup Guide Banner --}}
        <div class="bd-one bd-ra-10 p-20 mb-26 d-flex align-items-start cg-15"
            style="background:#eff6ff; border-color:#bfdbfe;">
            <i class="fa-solid fa-circle-info fs-18 mt-2 flex-shrink-0" style="color:#3b82f6"></i>
            <div>
                <p class="fs-14 fw-600 text-textBlack mb-8">{{ __('How to get your credentials?') }}</p>
                <ol class="fs-13 text-para-text ps-3 mb-0" style="line-height:1.9;">
                    <li>{{ __('Go to') }} <a href="https://developers.facebook.com" target="_blank" class="text-main-color">developers.facebook.com</a> {{ __('→ My Apps → Create App → choose "Business" type.') }}</li>
                    <li>{{ __('In App Dashboard → Settings → Basic: copy your') }} <strong>{{ __('App ID') }}</strong> {{ __('and') }} <strong>{{ __('App Secret') }}</strong>.</li>
                    <li>{{ __('For Facebook Pages: use Graph API Explorer or generate a System User token from') }} <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">{{ __('Business Settings → System Users') }}</a>.</li>
                    <li>{{ __('For WhatsApp: add "WhatsApp" product in your App Dashboard → WhatsApp → API Setup → copy') }} <strong>{{ __('Phone Number ID') }}</strong> {{ __('and') }} <strong>{{ __('Business Account ID') }}</strong>.</li>
                    <li>{{ __('Paste the') }} <strong>{{ __('Webhook URL') }}</strong> {{ __('and') }} <strong>{{ __('Verify Token') }}</strong> {{ __('below into') }} <em>{{ __('Meta App Dashboard → Webhooks → Edit') }}</em>.</li>
                    <li>{{ __('Subscribe to webhook fields: messages, messaging_postbacks (FB), message (WA).') }}</li>
                </ol>
            </div>
        </div>

        <form class="ajax" action="{{ route('admin.meta-app.update') }}" method="POST"
            data-handler="commonResponse">
            @csrf

            {{-- ── Shared App Credentials ──────────────────────────────────── --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <div class="d-flex align-items-center cg-10 pb-18 bd-b-one bd-c-stroke mb-20">
                    <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center"
                        style="background:#1877F21a;">
                        <i class="fa-brands fa-meta fs-18" style="color:#1877F2"></i>
                    </div>
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('Meta Developer App — Shared Credentials') }}</h5>
                </div>
                <p class="fs-13 text-para-text mb-20 bd-one bd-ra-8 p-14" style="background:#fefce8; border-color:#fde68a;">
                    <i class="fa-solid fa-lock me-6" style="color:#d97706"></i>
                    {{ __('These credentials are shared across Facebook, Instagram and WhatsApp since they use the same Meta App. Your App Secret is never displayed after saving.') }}
                </p>
                <div class="row rg-20">
                    <div class="col-md-6">
                        <label class="zForm-label">
                            {{ __('App ID') }}
                            <a href="https://developers.facebook.com/apps" target="_blank"
                                class="fs-11 text-main-color ms-5">{{ __('(find it here)') }}</a>
                        </label>
                        <input type="text" name="fb_app_id" class="form-control zForm-control"
                            value="{{ $config->fb_app_id }}"
                            placeholder="{{ __('e.g. 123456789012345') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="zForm-label">
                            {{ __('App Secret') }}
                            <span class="fs-11 text-para-text ms-5">{{ __('(leave blank to keep existing)') }}</span>
                        </label>
                        <div class="position-relative">
                            <input type="password" name="fb_app_secret" id="appSecretInput"
                                class="form-control zForm-control"
                                placeholder="{{ $config->fb_app_secret ? '••••••••••••••••••••' : __('Paste App Secret') }}">
                            <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y"
                                style="right:12px;" onclick="toggleSecret()">
                                <i class="fa-solid fa-eye fs-14 text-para-text" id="secretEyeIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Facebook Page ────────────────────────────────────────────── --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <div class="d-flex align-items-center cg-10 pb-18 bd-b-one bd-c-stroke mb-20">
                    <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center"
                        style="background:#1877F21a;">
                        <i class="fa-brands fa-facebook fs-18" style="color:#1877F2"></i>
                    </div>
                    <div>
                        <h5 class="fs-16 fw-600 text-textBlack">{{ __('Facebook Page') }}</h5>
                        <p class="fs-12 text-para-text">{{ __('For Messenger auto-reply and Facebook Page comments.') }}</p>
                    </div>
                </div>
                <div class="row rg-20">
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Facebook Page ID') }}</label>
                        <input type="text" name="fb_page_id" class="form-control zForm-control"
                            value="{{ $config->fb_page_id }}"
                            placeholder="{{ __('e.g. 102938475610293') }}">
                        <p class="fs-12 text-para-text mt-5">
                            {{ __('Found on your Page → About → Page transparency → Page ID.') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="zForm-label">
                            {{ __('Page Access Token') }}
                            <span class="fs-11 text-para-text ms-5">{{ __('(leave blank to keep existing)') }}</span>
                        </label>
                        <input type="password" name="fb_page_access_token" class="form-control zForm-control"
                            placeholder="{{ $config->fb_page_access_token ? '••••••••••••••••••••' : __('Long-lived page access token') }}">
                        <p class="fs-12 text-para-text mt-5">
                            {{ __('Generate via') }}
                            <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">
                                {{ __('Business Settings → System Users → Generate Token') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── WhatsApp Business API ────────────────────────────────────── --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <div class="d-flex align-items-center cg-10 pb-18 bd-b-one bd-c-stroke mb-20">
                    <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center"
                        style="background:#25D3661a;">
                        <i class="fa-brands fa-whatsapp fs-18" style="color:#25D366"></i>
                    </div>
                    <div>
                        <h5 class="fs-16 fw-600 text-textBlack">{{ __('WhatsApp Business API') }}</h5>
                        <p class="fs-12 text-para-text">{{ __('For automated WhatsApp messaging.') }}</p>
                    </div>
                </div>
                <div class="row rg-20">
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Phone Number ID') }}</label>
                        <input type="text" name="wa_phone_number_id" class="form-control zForm-control"
                            value="{{ $config->wa_phone_number_id }}"
                            placeholder="{{ __('e.g. 123456789012345') }}">
                        <p class="fs-12 text-para-text mt-5">
                            {{ __('Meta App Dashboard → WhatsApp → API Setup → Phone Number ID.') }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('WhatsApp Business Account ID (WABA)') }}</label>
                        <input type="text" name="wa_business_account_id" class="form-control zForm-control"
                            value="{{ $config->wa_business_account_id }}"
                            placeholder="{{ __('e.g. 987654321098765') }}">
                        <p class="fs-12 text-para-text mt-5">
                            {{ __('Meta App Dashboard → WhatsApp → API Setup → WhatsApp Business Account ID.') }}
                        </p>
                    </div>
                    <div class="col-md-12">
                        <label class="zForm-label">
                            {{ __('WhatsApp System User Access Token') }}
                            <span class="fs-11 text-para-text ms-5">{{ __('(leave blank to keep existing)') }}</span>
                        </label>
                        <input type="password" name="wa_access_token" class="form-control zForm-control"
                            placeholder="{{ $config->wa_access_token ? '••••••••••••••••••••' : __('Permanent System User token with whatsapp_business_messaging permission') }}">
                        <p class="fs-12 text-para-text mt-5">
                            {{ __('Generate via') }}
                            <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">
                                {{ __('Business Settings → System Users → Generate Token → select whatsapp_business_management + whatsapp_business_messaging') }}
                            </a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Instagram ───────────────────────────────────────────────── --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <div class="d-flex align-items-center cg-10 pb-18 bd-b-one bd-c-stroke mb-20">
                    <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center"
                        style="background:#E1306C1a;">
                        <i class="fa-brands fa-instagram fs-18" style="color:#E1306C"></i>
                    </div>
                    <div>
                        <h5 class="fs-16 fw-600 text-textBlack">{{ __('Instagram Messaging') }}</h5>
                        <p class="fs-12 text-para-text">{{ __('For Instagram DM auto-reply.') }}</p>
                    </div>
                </div>
                <div class="row rg-20">
                    <div class="col-md-6">
                        <label class="zForm-label">{{ __('Instagram Business Account ID') }}</label>
                        <input type="text" name="ig_user_id" class="form-control zForm-control"
                            value="{{ $config->ig_user_id }}"
                            placeholder="{{ __('e.g. 172839456172839') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="zForm-label">
                            {{ __('Instagram Access Token') }}
                            <span class="fs-11 text-para-text ms-5">{{ __('(leave blank to keep existing)') }}</span>
                        </label>
                        <input type="password" name="ig_access_token" class="form-control zForm-control"
                            placeholder="{{ $config->ig_access_token ? '••••••••••••••••••••' : __('Long-lived Instagram token') }}">
                    </div>
                </div>
            </div>

            {{-- ── Webhook Configuration ────────────────────────────────────── --}}
            <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-20">
                <div class="d-flex align-items-center cg-10 pb-18 bd-b-one bd-c-stroke mb-20">
                    <div class="wh-36 bd-ra-8 d-flex align-items-center justify-content-center"
                        style="background:#6366f11a;">
                        <i class="fa-solid fa-plug fs-16" style="color:#6366f1"></i>
                    </div>
                    <div>
                        <h5 class="fs-16 fw-600 text-textBlack">{{ __('Webhook Configuration') }}</h5>
                        <p class="fs-12 text-para-text">{{ __('Copy these values into your Meta App Dashboard → Webhooks.') }}</p>
                    </div>
                </div>

                {{-- Webhook URL --}}
                <div class="mb-20">
                    <label class="zForm-label">{{ __('Webhook Callback URL') }}</label>
                    <div class="d-flex align-items-center cg-10">
                        <input type="text" id="webhookUrlInput" class="form-control zForm-control"
                            value="{{ $webhookUrl }}" readonly>
                        <button type="button" class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0 copy-btn"
                            data-copy="webhookUrlInput">
                            <i class="fa-solid fa-copy me-5"></i>{{ __('Copy') }}
                        </button>
                    </div>
                    <p class="fs-12 text-para-text mt-6">
                        {{ __('Paste this URL in: Meta App Dashboard → WhatsApp / Messenger → Configuration → Webhooks → Callback URL') }}
                    </p>
                </div>

                {{-- Verify Token --}}
                <div>
                    <label class="zForm-label">{{ __('Webhook Verify Token') }}</label>
                    <div class="d-flex align-items-center cg-10">
                        <input type="text" id="verifyTokenInput" class="form-control zForm-control"
                            value="{{ $config->webhook_verify_token }}" readonly>
                        <button type="button" class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0 copy-btn"
                            data-copy="verifyTokenInput">
                            <i class="fa-solid fa-copy me-5"></i>{{ __('Copy') }}
                        </button>
                        <button type="button" id="regenTokenBtn"
                            class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0"
                            data-route="{{ route('admin.meta-app.regenerate-token') }}">
                            <i class="fa-solid fa-rotate-right me-5"></i>{{ __('Regenerate') }}
                        </button>
                    </div>
                    <p class="fs-12 text-para-text mt-6">
                        {{ __('Paste this token in: Meta App Dashboard → Webhooks → Verify Token field → click Verify and Save.') }}
                    </p>
                </div>
            </div>

            {{-- ── Subscribed Webhook Fields Guide ─────────────────────────── --}}
            <div class="bd-one bd-ra-10 p-20 mb-26" style="background:#f0fdf4; border-color:#bbf7d0;">
                <p class="fs-14 fw-600 text-textBlack mb-10">
                    <i class="fa-solid fa-list-check me-8" style="color:#10b981"></i>
                    {{ __('Required Webhook Fields to Subscribe') }}
                </p>
                <div class="row rg-10">
                    @php
                        $fields = [
                            ['platform' => 'Facebook Messenger',  'color' => '#1877F2', 'icon' => 'fa-brands fa-facebook-messenger', 'fields' => 'messages, messaging_postbacks, messaging_optins'],
                            ['platform' => 'WhatsApp',            'color' => '#25D366', 'icon' => 'fa-brands fa-whatsapp',            'fields' => 'messages, message_status'],
                            ['platform' => 'Instagram',           'color' => '#E1306C', 'icon' => 'fa-brands fa-instagram',           'fields' => 'messages, messaging_seen'],
                        ];
                    @endphp
                    @foreach($fields as $f)
                    <div class="col-md-4">
                        <div class="bd-one bd-ra-8 p-14 bg-white">
                            <div class="d-flex align-items-center cg-8 mb-8">
                                <i class="{{ $f['icon'] }} fs-16" style="color:{{ $f['color'] }}"></i>
                                <span class="fs-13 fw-600 text-textBlack">{{ $f['platform'] }}</span>
                            </div>
                            <p class="fs-12 text-para-text">
                                {{ __('Subscribe to:') }} <code class="fs-11">{{ $f['fields'] }}</code>
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Save Button --}}
            <div class="d-flex justify-content-end">
                <button type="submit"
                    class="py-13 px-25 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">
                    <i class="fa-solid fa-save me-6"></i> {{ __('Save Configuration') }}
                </button>
            </div>

        </form>
    </div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/meta-app.js') }}"></script>
@endpush
