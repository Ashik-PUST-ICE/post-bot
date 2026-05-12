@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
        <div>
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Select Account to Connect') }}</h4>
            <p class="fs-14 fw-400 text-para-text mt-5">
                {{ __('Choose the Facebook Page, Instagram, or WhatsApp number you want to connect.') }}
            </p>
        </div>
        <a href="{{ route('admin.platforms.index') }}"
            class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack d-flex align-items-center cg-8">
            <i class="fa-solid fa-arrow-left"></i> {{ __('Back to Platforms') }}
        </a>
    </div>

    @php
        $platform  = $oauthData['platform'];
        $pages     = $oauthData['pages'] ?? [];
        $waPhones  = $oauthData['wa_phones'] ?? [];
    @endphp

    {{-- Final Step Alert --}}
    <div class="bd-one bd-ra-10 p-20 mb-26 d-flex align-items-center justify-content-between flex-wrap g-15"
         style="background: linear-gradient(90deg, #6366f1, #a855f7); border: none; color: #fff;">
        <div class="d-flex align-items-center cg-15">
            <div class="wh-48 bd-ra-50 bg-white-20 d-flex align-items-center justify-content-center">
                <i class="fa-solid fa-circle-check fs-24"></i>
            </div>
            <div>
                <h5 class="fs-18 fw-700 mb-2">{{ __('Almost there!') }}</h5>
                <p class="fs-14 opacity-80">{{ __('You’ve logged in with Meta. Now, click the "Connect" button on the specific account you want to use.') }}</p>
            </div>
        </div>
        <div class="zCheck d-none d-md-block">
            <i class="fa-solid fa-arrow-down fs-20 bounce-y"></i>
        </div>
    </div>

    {{-- ── WhatsApp: Phone Number Picker ──────────────────────────────────── --}}
    @if($platform === 'whatsapp')
        <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
            <div class="d-flex align-items-center cg-12 pb-18 bd-b-one bd-c-stroke mb-20">
                <div class="wh-40 bd-ra-50 d-flex align-items-center justify-content-center" style="background:#25D3661a;">
                    <i class="fa-brands fa-whatsapp fs-20" style="color:#25D366"></i>
                </div>
                <div>
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('WhatsApp Phone Numbers') }}</h5>
                    <p class="fs-12 text-para-text">{{ __('Select the number to use for messaging.') }}</p>
                </div>
            </div>
            @if(empty($waPhones))
                <div class="text-center py-40">
                    <i class="fa-brands fa-whatsapp fs-40 mb-15" style="color:#25D36640"></i>
                    <p class="fs-14 text-para-text">{{ __('No WhatsApp phone numbers found under your WABA.') }}</p>
                    <p class="fs-12 text-para-text mt-8">{{ __('Make sure your WABA ID is saved in Meta App Config.') }}</p>
                </div>
            @else
                <div class="row rg-15">
                    @foreach($waPhones as $phone)
                    <div class="col-md-6 col-lg-4">
                        <div class="bd-one bd-ra-10 p-20" style="border-color:#e5e7eb; transition:border-color .2s;" id="wa-card-{{ $loop->index }}">
                            <div class="d-flex align-items-center cg-12 mb-15">
                                <div class="wh-44 bd-ra-50 d-flex align-items-center justify-content-center"
                                    style="background:#25D3661a;">
                                    <i class="fa-brands fa-whatsapp fs-22" style="color:#25D366"></i>
                                </div>
                                <div>
                                    <p class="fs-14 fw-700 text-textBlack">{{ $phone['display_phone_number'] ?? 'N/A' }}</p>
                                    <p class="fs-12 text-para-text">{{ $phone['verified_name'] ?? '' }}</p>
                                </div>
                            </div>
                            @if(!empty($phone['quality_rating']))
                                <span class="py-4 px-10 bd-ra-50 fs-11 fw-600 mb-15 d-inline-block"
                                    style="background:#10b9811a;color:#10b981;">
                                    {{ __('Quality:') }} {{ ucfirst(strtolower($phone['quality_rating'])) }}
                                </span>
                            @endif
                            <button type="button"
                                class="w-100 py-11 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600 connect-oauth-btn"
                                data-page-id="{{ $phone['id'] }}"
                                data-page-name="{{ $phone['display_phone_number'] ?? 'WhatsApp' }}"
                                data-access-token="{{ $oauthData['long_token'] }}"
                                data-platform-type="{{ PLATFORM_WHATSAPP }}"
                                data-phone-number-id="{{ $phone['id'] }}"
                                data-route="{{ route('admin.meta-oauth.save.page') }}">
                                <i class="fa-solid fa-plug me-6"></i>{{ __('Connect This Number') }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    {{-- ── Instagram: Instagram Account Picker ─────────────────────────────── --}}
    @elseif($platform === 'instagram')
        <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
            <div class="d-flex align-items-center cg-12 pb-18 bd-b-one bd-c-stroke mb-20">
                <div class="wh-40 bd-ra-50 d-flex align-items-center justify-content-center" style="background:#E1306C1a;">
                    <i class="fa-brands fa-instagram fs-20" style="color:#E1306C"></i>
                </div>
                <div>
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('Instagram Business Accounts') }}</h5>
                    <p class="fs-12 text-para-text">{{ __('Select the Instagram account linked to your Facebook Page.') }}</p>
                </div>
            </div>
            @php $hasIg = collect($pages)->filter(fn($p) => !empty($p['instagram_account']))->count(); @endphp
            @if(!$hasIg)
                <div class="text-center py-40">
                    <i class="fa-brands fa-instagram fs-40 mb-15" style="color:#E1306C40"></i>
                    <p class="fs-14 text-para-text">{{ __('No Instagram Business accounts found.') }}</p>
                    <p class="fs-12 text-para-text mt-8">{{ __('Make sure your Instagram is connected as a Business account to a Facebook Page.') }}</p>
                </div>
            @else
                <div class="row rg-15">
                    @foreach($pages as $page)
                        @if(!empty($page['instagram_account']))
                        @php $ig = $page['instagram_account']; @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="bd-one bd-ra-10 p-20" style="border-color:#e5e7eb; transition:border-color .2s;">
                                <div class="d-flex align-items-center cg-12 mb-15">
                                    @if(!empty($ig['profile_picture_url']))
                                        <img src="{{ $ig['profile_picture_url'] }}" class="wh-44 bd-ra-50 object-fit-cover" alt="">
                                    @else
                                        <div class="wh-44 bd-ra-50 d-flex align-items-center justify-content-center text-white fs-16 fw-700"
                                            style="background: linear-gradient(135deg,#E1306C,#F77737);">
                                            {{ strtoupper(substr($ig['username'] ?? 'I', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="fs-14 fw-700 text-textBlack">@{{ $ig['username'] ?? $ig['id'] }}</p>
                                        <p class="fs-12 text-para-text">{{ __('via page:') }} {{ $page['name'] }}</p>
                                    </div>
                                </div>
                                <button type="button"
                                    class="w-100 py-11 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600 connect-oauth-btn"
                                    data-page-id="{{ $ig['id'] }}"
                                    data-page-name="{{ '@' . ($ig['username'] ?? $ig['id']) }}"
                                    data-access-token="{{ $page['access_token'] }}"
                                    data-platform-type="{{ PLATFORM_INSTAGRAM }}"
                                    data-ig-user-id="{{ $ig['id'] }}"
                                    data-route="{{ route('admin.meta-oauth.save.page') }}">
                                    <i class="fa-solid fa-plug me-6"></i>{{ __('Connect This Account') }}
                                </button>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>

    {{-- ── Facebook / Messenger: Page Picker ────────────────────────────────── --}}
    @else
        <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
            <div class="d-flex align-items-center cg-12 pb-18 bd-b-one bd-c-stroke mb-20">
                <div class="wh-40 bd-ra-50 d-flex align-items-center justify-content-center" style="background:#1877F21a;">
                    <i class="fa-brands fa-facebook fs-20" style="color:#1877F2"></i>
                </div>
                <div>
                    <h5 class="fs-16 fw-600 text-textBlack">{{ __('Facebook Pages') }}</h5>
                    <p class="fs-12 text-para-text">{{ __('Select the Page to use for Messenger auto-reply.') }}</p>
                </div>
            </div>
            @if(empty($pages))
                <div class="text-center py-40">
                    <i class="fa-brands fa-facebook fs-40 mb-15" style="color:#1877F240"></i>
                    <p class="fs-14 text-para-text">{{ __('No Facebook Pages found in this account.') }}</p>
                    <p class="fs-12 text-para-text mt-8">
                        {{ __('Make sure you are an admin of a Facebook Page.') }}
                    </p>
                </div>
            @else
                <div class="row rg-15">
                    @foreach($pages as $page)
                    <div class="col-md-6 col-lg-4">
                        <div class="bd-one bd-ra-10 p-20" style="border-color:#e5e7eb; transition:border-color .2s;">
                            <div class="d-flex align-items-center cg-12 mb-15">
                                @if(!empty($page['picture']['data']['url']))
                                    <img src="{{ $page['picture']['data']['url'] }}" class="wh-44 bd-ra-50 object-fit-cover" alt="">
                                @else
                                    <div class="wh-44 bd-ra-50 d-flex align-items-center justify-content-center text-white fs-16 fw-700"
                                        style="background:#1877F2;">
                                        {{ strtoupper(substr($page['name'], 0, 1)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="fs-14 fw-700 text-textBlack text-truncate">{{ $page['name'] }}</p>
                                    <p class="fs-11 text-para-text">{{ __('ID:') }} {{ $page['id'] }}</p>
                                    @if(!empty($page['category']))
                                        <p class="fs-11 text-para-text">{{ $page['category'] }}</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Messenger --}}
                            <button type="button"
                                class="w-100 py-10 px-14 bd-one bd-ra-4 fs-13 fw-600 mb-8 connect-oauth-btn"
                                style="border-color:#0084FF; background:#0084FF1a; color:#0084FF;"
                                data-page-id="{{ $page['id'] }}"
                                data-page-name="{{ $page['name'] }}"
                                data-access-token="{{ $page['access_token'] }}"
                                data-platform-type="{{ PLATFORM_MESSENGER }}"
                                data-route="{{ route('admin.meta-oauth.save.page') }}">
                                <i class="fa-brands fa-facebook-messenger me-6"></i>{{ __('Connect Messenger') }}
                            </button>

                            {{-- Facebook Page --}}
                            <button type="button"
                                class="w-100 py-10 px-14 bd-one bd-ra-4 fs-13 fw-600 connect-oauth-btn"
                                style="border-color:#1877F2; background:#1877F21a; color:#1877F2;"
                                data-page-id="{{ $page['id'] }}"
                                data-page-name="{{ $page['name'] }}"
                                data-access-token="{{ $page['access_token'] }}"
                                data-platform-type="{{ PLATFORM_FACEBOOK_PAGE }}"
                                data-route="{{ route('admin.meta-oauth.save.page') }}">
                                <i class="fa-brands fa-facebook me-6"></i>{{ __('Connect FB Page') }}
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/meta-oauth.js') }}"></script>
@endpush
