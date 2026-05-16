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
                {{ __('Configure your Meta Developer App credentials for Facebook, WhatsApp, and Instagram.') }}
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
                    @endif
                            @if($s['ok'])
                                <span class="fs-12 fw-600" style="color:#10b981;">
                                    <i class="fa-solid fa-circle-check me-4"></i>{{ __('Configured') }}
                                </span>
                            @else
                                <span class="fs-12 fw-500 text-para-text">
                                    <i class="fa-solid fa-circle-exclamation me-4"></i>{{ __('Not set') }}
                                </span>
                            @endif
                    @if($s['badge'])
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Tabs + Form --}}
    <form class="ajax" action="{{ route('admin.meta-app.update') }}" method="POST" data-handler="commonResponse">
        @csrf
        {{-- Identifies which tab's Save was clicked; read by MetaAppController::update() --}}
        <input type="hidden" name="section" id="metaSection" value="app">

        {{-- Tab Nav --}}
        <div class="bd-one bd-c-stroke bd-ra-10 bg-white mb-4">
            <style>
                #metaConfigTabs {
                    background: #f9fafb;
                    padding: 10px 15px !important;
                    border-bottom: 1px solid #e5e7eb;
                    gap: 8px !important;
                    border-radius: 10px 10px 0 0;
                }
                .meta-tab-link {
                    border-radius: 8px !important;
                    transition: all 0.2s ease;
                    border: 1px solid transparent !important;
                    color: #4b5563 !important;
                    position: relative;
                    white-space: nowrap;
                }
                .meta-tab-link:hover {
                    background: #f3f4f6;
                    color: #111827 !important;
                }
                .meta-tab-link:focus {
                    outline: none;
                    box-shadow: none !important;
                }
                .meta-tab-link.active {
                    background: var(--main-color, #6366f1) !important;
                    border-color: var(--main-color, #6366f1) !important;
                    color: white !important;
                    font-weight: 600 !important;
                    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
                }
                .meta-tab-link.active i {
                    color: white !important;
                    transform: scale(1.1);
                }
                .meta-tab-link.active .status-dot {
                    border: 1px solid white;
                }
                .status-dot {
                    width: 7px;
                    height: 7px;
                    border-radius: 50%;
                    background: #d1d5db;
                    position: absolute;
                    top: 10px;
                    right: 8px;
                }
                .status-dot.active {
                    background: #10b981;
                    box-shadow: 0 0 0 2px #10b98133;
                }
            </style>
            <ul class="nav d-flex flex-nowrap overflow-auto" id="metaConfigTabs" role="tablist">
                @php
                    $tabs = [
                        ['id' => 'tab-app',       'label' => __('Meta App'),   'icon' => 'fa-brands fa-meta',       'color' => '#1877F2', 'active' => true,  'ok' => ($config->fb_app_id && $config->fb_app_secret)],
                        ['id' => 'tab-facebook',  'label' => __('Facebook'),   'icon' => 'fa-brands fa-facebook',   'color' => '#1877F2', 'active' => false, 'ok' => $config->hasFacebook()],
                        ['id' => 'tab-whatsapp',  'label' => __('WhatsApp'),   'icon' => 'fa-brands fa-whatsapp',   'color' => '#25D366', 'active' => false, 'ok' => $config->hasWhatsApp()],
                        ['id' => 'tab-instagram', 'label' => __('Instagram'),  'icon' => 'fa-brands fa-instagram',  'color' => '#E1306C', 'active' => false, 'ok' => $config->hasInstagram()],
                        ['id' => 'tab-webhook',   'label' => __('Webhook'),    'icon' => 'fa-solid fa-plug',        'color' => '#6366f1', 'active' => false, 'ok' => !empty($config->webhook_verify_token)],
                    ];
                @endphp
                @foreach($tabs as $tab)
                <li class="nav-item" role="presentation">
                    <button class="nav-link meta-tab-link d-flex align-items-center cg-7 py-12 px-18 border-0 bg-transparent fs-13 fw-500 {{ $tab['active'] ? 'active' : '' }}"
                        id="{{ $tab['id'] }}-btn"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $tab['id'] }}"
                        type="button" role="tab">
                        <i class="{{ $tab['icon'] }} fs-15" style="color:{{ $tab['color'] }}"></i>
                        {{ $tab['label'] }}
                        <span class="status-dot {{ $tab['ok'] ? 'active' : '' }}" title="{{ $tab['ok'] ? __('Configured') : __('Not set') }}"></span>
                    </button>
                </li>
                @endforeach
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content p-25" id="metaConfigTabContent">

                {{-- ── Tab 1: Meta App (Shared Credentials) ─────────────────── --}}
                <div class="tab-pane fade show active" id="tab-app" role="tabpanel">
                    <p class="fs-13 text-para-text mb-20 bd-one bd-ra-8 p-14"
                        style="background:#fefce8; border-color:#fde68a;">
                        <i class="fa-solid fa-lock me-6" style="color:#d97706"></i>
                        {{ __('These credentials are shared across Facebook, Instagram and WhatsApp — they all use the same Meta App. Your App Secret is never displayed after saving.') }}
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
                            <p class="fs-12 text-para-text mt-5">
                                {{ __('Meta App Dashboard → Settings → Basic → App ID.') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <label class="zForm-label mb-0">{{ __('App Secret') }}</label>
                                @if($config->fb_app_secret)
                                    <span class="badge bg-success-transparent text-success fs-10 px-8 py-2">
                                        <i class="fa-solid fa-check-circle me-4"></i>{{ __('Configured') }}
                                    </span>
                                @endif
                            </div>
                            <div class="position-relative">
                                <input type="password" name="fb_app_secret" id="appSecretInput"
                                    class="form-control zForm-control"
                                    value="{{ $config->fb_app_secret }}"
                                    placeholder="{{ __('Paste App Secret') }}">
                                <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y"
                                    style="right:12px;" onclick="toggleSecret()">
                                    <i class="fa-solid fa-eye fs-14 text-para-text" id="secretEyeIcon"></i>
                                </button>
                            </div>
                            <p class="fs-12 text-para-text mt-5">
                                {{ __('Meta App Dashboard → Settings → Basic → App Secret → Show.') }}
                            </p>
                        </div>
                    </div>

                    {{-- OAuth Redirect URI --}}
                    <div class="bd-one bd-ra-8 p-14 mt-20" style="background:#f0f9ff; border-color:#bae6fd;">
                        <p class="fs-13 fw-600 text-textBlack mb-10">
                            <i class="fa-solid fa-link me-6" style="color:#0284c7"></i>
                            {{ __('OAuth Redirect URI') }}
                        </p>
                        <p class="fs-12 text-para-text mb-10">
                            {{ __('Copy this URL and add it to your') }}
                            <strong>{{ __('Meta App Dashboard → Facebook Login → Settings → Valid OAuth Redirect URIs') }}</strong>.
                            {{ __('This is required for the Connect via OAuth button to work.') }}
                        </p>
                        <div class="d-flex align-items-center cg-10">
                            <input type="text" id="oauthCallbackUrlInput" class="form-control zForm-control"
                                value="{{ $oauthCallbackUrl }}" readonly>
                            <button type="button"
                                class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0 copy-btn"
                                data-copy="oauthCallbackUrlInput">
                                <i class="fa-solid fa-copy me-5"></i>{{ __('Copy') }}
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-20">
                        <button type="submit" onclick="document.getElementById('metaSection').value='app'"
                            class="py-11 px-22 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                            <i class="fa-solid fa-save me-6"></i>{{ __('Save') }}
                        </button>
                    </div>
                </div>

                {{-- ── Tab 2: Facebook Page ──────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-facebook" role="tabpanel">

                    {{-- OAuth tip --}}
                    <div class="bd-one bd-ra-8 p-12 mb-20 d-flex align-items-start cg-10"
                        style="background:#f0f9ff; border-color:#bae6fd;">
                        <i class="fa-solid fa-wand-magic-sparkles fs-13 mt-1 flex-shrink-0" style="color:#0284c7"></i>
                        <p class="fs-12 text-para-text mb-0">
                            <strong class="text-textBlack">{{ __('Tip:') }}</strong>
                            {{ __('Page ID and Access Token are auto-filled when you connect Facebook via') }}
                            <a href="{{ route('admin.platforms.index') }}" class="text-main-color">{{ __('Platforms → Connect via Meta OAuth') }}</a>.
                            {{ __('Only fill manually if using a System User token.') }}
                        </p>
                    </div>

                    <div class="row rg-20">
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Facebook Page ID') }}</label>
                            <input type="text" name="fb_page_id" class="form-control zForm-control"
                                value="{{ $config->fb_page_id }}"
                                placeholder="{{ __('e.g. 102938475610293') }}">
                            <p class="fs-12 text-para-text mt-5">
                                {{ __('Your Page → About → Page transparency → Page ID.') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <label class="zForm-label mb-0">{{ __('Page Access Token') }}</label>
                                @if($config->fb_page_access_token)
                                    <span class="badge bg-success-transparent text-success fs-10 px-8 py-2">
                                        <i class="fa-solid fa-check-circle me-4"></i>{{ __('Configured') }}
                                    </span>
                                @endif
                            </div>
                            <div class="position-relative">
                                <input type="password" name="fb_page_access_token" id="fbPageTokenInput" 
                                    class="form-control zForm-control"
                                    value="{{ $config->fb_page_access_token }}"
                                    placeholder="{{ __('Auto-filled via OAuth, or paste manually') }}">
                                <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y toggle-token-vis"
                                    style="right:12px;" data-target="fbPageTokenInput">
                                    <i class="fa-solid fa-eye fs-14 text-para-text"></i>
                                </button>
                            </div>
                            <p class="fs-12 text-para-text mt-5">
                                {{ __('Manual:') }}
                                <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">
                                    {{ __('Business Settings → System Users → Generate Token') }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-20">
                        <button type="submit" onclick="document.getElementById('metaSection').value='facebook'"
                            class="py-11 px-22 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                            <i class="fa-solid fa-save me-6"></i>{{ __('Save') }}
                        </button>
                    </div>
                </div>

                {{-- ── Tab 3: WhatsApp ───────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-whatsapp" role="tabpanel">

                    {{-- System User Token warning --}}
                    <div class="bd-one bd-ra-8 p-12 mb-20 d-flex align-items-start cg-10"
                        style="background:#fffbeb; border-color:#fde68a;">
                        <i class="fa-solid fa-triangle-exclamation fs-13 mt-1 flex-shrink-0" style="color:#d97706"></i>
                        <p class="fs-12 text-para-text mb-0">
                            <strong class="text-textBlack">{{ __('Permanent System User Token required.') }}</strong>
                            {{ __('WhatsApp messaging needs a non-expiring token. The OAuth flow only saves your Phone Number ID — you') }}
                            <strong>{{ __('must') }}</strong>
                            {{ __('paste a permanent System User token here.') }}
                            <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">{{ __('How to generate →') }}</a>
                        </p>
                    </div>

                    <div class="row rg-20">
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Phone Number ID') }}</label>
                            <input type="text" name="wa_phone_number_id" class="form-control zForm-control"
                                value="{{ $config->wa_phone_number_id }}"
                                placeholder="{{ __('e.g. 123456789012345') }}">
                            <p class="fs-12 text-para-text mt-5">
                                {{ __('Auto-filled via OAuth, or: Meta App Dashboard → WhatsApp → API Setup.') }}
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
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <label class="zForm-label mb-0">{{ __('System User Access Token') }}</label>
                                @if($config->wa_access_token)
                                    <span class="badge bg-success-transparent text-success fs-10 px-8 py-2">
                                        <i class="fa-solid fa-check-circle me-4"></i>{{ __('Configured') }}
                                    </span>
                                @endif
                            </div>
                            <div class="position-relative">
                                <input type="password" name="wa_access_token" id="waTokenInput" 
                                    class="form-control zForm-control"
                                    value="{{ $config->wa_access_token }}"
                                    placeholder="{{ __('Permanent System User token — never expires') }}">
                                <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y toggle-token-vis"
                                    style="right:12px;" data-target="waTokenInput">
                                    <i class="fa-solid fa-eye fs-14 text-para-text"></i>
                                </button>
                            </div>
                            <p class="fs-12 text-para-text mt-5">
                                <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">
                                    {{ __('Business Settings → System Users → Generate Token → select whatsapp_business_management + whatsapp_business_messaging') }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-20">
                        <button type="submit" onclick="document.getElementById('metaSection').value='whatsapp'"
                            class="py-11 px-22 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                            <i class="fa-solid fa-save me-6"></i>{{ __('Save') }}
                        </button>
                    </div>
                </div>

                {{-- ── Tab 4: Instagram ──────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-instagram" role="tabpanel">

                    {{-- OAuth tip --}}
                    <div class="bd-one bd-ra-8 p-12 mb-20 d-flex align-items-start cg-10"
                        style="background:#f0f9ff; border-color:#bae6fd;">
                        <i class="fa-solid fa-wand-magic-sparkles fs-13 mt-1 flex-shrink-0" style="color:#0284c7"></i>
                        <p class="fs-12 text-para-text mb-0">
                            <strong class="text-textBlack">{{ __('Tip:') }}</strong>
                            {{ __('Instagram Account ID and token are auto-filled when you connect via') }}
                            <a href="{{ route('admin.platforms.index') }}" class="text-main-color">{{ __('Platforms → Connect via Meta OAuth') }}</a>.
                            {{ __('Your Instagram must be a Business account linked to a Facebook Page.') }}
                        </p>
                    </div>

                    <div class="row rg-20">
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Instagram Business Account ID') }}</label>
                            <input type="text" name="ig_user_id" class="form-control zForm-control"
                                value="{{ $config->ig_user_id }}"
                                placeholder="{{ __('e.g. 172839456172839') }}">
                            <p class="fs-12 text-para-text mt-5">
                                {{ __('Auto-filled via OAuth, or: Meta App Dashboard → Instagram → API Setup.') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-between mb-8">
                                <label class="zForm-label mb-0">{{ __('Instagram Access Token') }}</label>
                                @if($config->ig_access_token)
                                    <span class="badge bg-success-transparent text-success fs-10 px-8 py-2">
                                        <i class="fa-solid fa-check-circle me-4"></i>{{ __('Configured') }}
                                    </span>
                                @endif
                            </div>
                            <div class="position-relative">
                                <input type="password" name="ig_access_token" id="igTokenInput" 
                                    class="form-control zForm-control"
                                    value="{{ $config->ig_access_token }}"
                                    placeholder="{{ __('Auto-filled via OAuth, or paste long-lived token') }}">
                                <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y toggle-token-vis"
                                    style="right:12px;" data-target="igTokenInput">
                                    <i class="fa-solid fa-eye fs-14 text-para-text"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-20">
                        <button type="submit" onclick="document.getElementById('metaSection').value='instagram'"
                            class="py-11 px-22 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600">
                            <i class="fa-solid fa-save me-6"></i>{{ __('Save') }}
                        </button>
                    </div>
                </div>

                {{-- ── Tab 5: Webhook ────────────────────────────────────────── --}}
                <div class="tab-pane fade" id="tab-webhook" role="tabpanel">

                    {{-- Webhook URL --}}
                    <div class="mb-20">
                        <label class="zForm-label">{{ __('Webhook Callback URL') }}</label>
                        <div class="d-flex align-items-center cg-10">
                            <input type="text" id="webhookUrlInput" class="form-control zForm-control"
                                value="{{ $webhookUrl }}" readonly>
                            <button type="button"
                                class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0 copy-btn"
                                data-copy="webhookUrlInput">
                                <i class="fa-solid fa-copy me-5"></i>{{ __('Copy') }}
                            </button>
                        </div>
                        <p class="fs-12 text-para-text mt-6">
                            {{ __('Paste into: Meta App Dashboard → WhatsApp / Messenger → Configuration → Webhooks → Callback URL.') }}
                        </p>
                    </div>

                    {{-- Verify Token --}}
                    <div class="mb-20">
                        <label class="zForm-label">{{ __('Webhook Verify Token') }}</label>
                        <div class="d-flex align-items-center cg-10">
                            <input type="text" id="verifyTokenInput" class="form-control zForm-control"
                                value="{{ $config->webhook_verify_token }}" readonly>
                            <button type="button"
                                class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack flex-shrink-0 copy-btn"
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
                            {{ __('Paste into: Meta App Dashboard → Webhooks → Verify Token → click Verify and Save.') }}
                        </p>
                    </div>

                    {{-- Required Webhook Fields --}}
                    <div class="bd-one bd-ra-8 p-16" style="background:#f0fdf4; border-color:#bbf7d0;">
                        <p class="fs-13 fw-600 text-textBlack mb-12">
                            <i class="fa-solid fa-list-check me-8" style="color:#10b981"></i>
                            {{ __('Required Webhook Fields to Subscribe') }}
                        </p>
                        <div class="row rg-10">
                            @php
                                $fields = [
                                    ['platform' => 'Facebook & Messenger', 'color' => '#1877F2', 'icon' => 'fa-brands fa-facebook-messenger', 'fields' => 'messages, messaging_postbacks, feed'],
                                    ['platform' => 'WhatsApp',           'color' => '#25D366', 'icon' => 'fa-brands fa-whatsapp',            'fields' => 'messages, message_status'],
                                    ['platform' => 'Instagram',          'color' => '#E1306C', 'icon' => 'fa-brands fa-instagram',           'fields' => 'messages, messaging_seen'],
                                ];
                            @endphp
                            @foreach($fields as $f)
                            <div class="col-md-4">
                                <div class="bd-one bd-ra-8 p-14 bg-white">
                                    <div class="d-flex align-items-center cg-8 mb-6">
                                        <i class="{{ $f['icon'] }} fs-14" style="color:{{ $f['color'] }}"></i>
                                        <span class="fs-13 fw-600 text-textBlack">{{ $f['platform'] }}</span>
                                    </div>
                                    <p class="fs-12 text-para-text mb-0">
                                        {{ __('Subscribe to:') }} <code class="fs-11">{{ $f['fields'] }}</code>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>{{-- /tab-content --}}
        </div>{{-- /tab card --}}

    </form>

</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/meta-app.js') }}"></script>
@endpush
