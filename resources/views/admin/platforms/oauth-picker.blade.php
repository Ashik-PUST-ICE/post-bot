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
    </div>    {{-- ── Facebook / Messenger: Page Picker ────────────────────────────────── --}}
    @if(!empty($pages))
    <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-26">
        <div class="d-flex align-items-center cg-12 pb-18 bd-b-one bd-c-stroke mb-20">
            <div class="wh-40 bd-ra-50 d-flex align-items-center justify-content-center" style="background:#1877F21a;">
                <i class="fa-brands fa-facebook fs-20" style="color:#1877F2"></i>
            </div>
            <div>
                <h5 class="fs-16 fw-600 text-textBlack">{{ __('Facebook Pages') }}</h5>
                <p class="fs-12 text-para-text">{{ __('Select the Page to use for Messenger and Page auto-replies.') }}</p>
            </div>
        </div>
        <div class="row rg-15">
            @foreach($pages as $page)
            <div class="col-md-6 col-lg-4">
                @php $isConnected = in_array($page['id'], $existingPlatformIds); @endphp
                <div class="bd-one bd-ra-10 p-20 h-100 d-flex flex-column oauth-card-box" 
                    style="border-color:{{ $isConnected ? '#10b981' : '#e5e7eb' }}; background-color:{{ $isConnected ? '#10b98108' : '#fff' }}; transition:all .3s; {{ $isConnected ? 'box-shadow: 0 4px 12px #10b9811a;' : '' }}">
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
                        </div>
                    </div>

                    <div class="mt-auto d-flex flex-column rg-8">
                        {{-- Messenger --}}
                        @if(in_array($page['id'], $existingPlatformIds))
                            <button type="button" class="w-100 py-10 px-14 bd-one bd-ra-4 fs-13 fw-600"
                                style="border-color:#10b981; background:#10b9811a; color:#10b981;" disabled>
                                <i class="fa-solid fa-check me-6"></i>{{ __('Connected') }}
                            </button>
                        @else
                            <button type="button"
                                class="w-100 py-10 px-14 bd-one bd-ra-4 fs-13 fw-600 connect-oauth-btn"
                                style="border-color:#0084FF; background:#0084FF1a; color:#0084FF;"
                                data-page-id="{{ $page['id'] }}"
                                data-page-name="{{ $page['name'] }}"
                                data-access-token="{{ $page['access_token'] }}"
                                data-platform-type="{{ PLATFORM_MESSENGER }}"
                                data-route="{{ route('admin.meta-oauth.save.page') }}">
                                <i class="fa-brands fa-facebook-messenger me-6"></i>{{ __('Connect Messenger') }}
                            </button>
                        @endif

                        {{-- Facebook Page --}}
                        @if(in_array($page['id'], $existingPlatformIds))
                            <button type="button" class="w-100 py-10 px-14 bd-one bd-ra-4 fs-13 fw-600"
                                style="border-color:#10b981; background:#10b9811a; color:#10b981;" disabled>
                                <i class="fa-solid fa-check me-6"></i>{{ __('Connected') }}
                            </button>
                        @else
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
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Instagram: Instagram Account Picker ─────────────────────────────── --}}
    @php $igPages = collect($pages)->filter(fn($p) => !empty($p['instagram_account'])); @endphp
    @if($igPages->count() > 0)
    <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 mb-26">
        <div class="d-flex align-items-center cg-12 pb-18 bd-b-one bd-c-stroke mb-20">
            <div class="wh-40 bd-ra-50 d-flex align-items-center justify-content-center" style="background:#E1306C1a;">
                <i class="fa-brands fa-instagram fs-20" style="color:#E1306C"></i>
            </div>
            <div>
                <h5 class="fs-16 fw-600 text-textBlack">{{ __('Instagram Business Accounts') }}</h5>
                <p class="fs-12 text-para-text">{{ __('Select the Instagram account linked to your Facebook Page.') }}</p>
            </div>
        </div>
        <div class="row rg-15">
            @foreach($igPages as $page)
                @php $ig = $page['instagram_account']; @endphp
                <div class="col-md-6 col-lg-4">
                    @php $isConnected = in_array($ig['id'], $existingPlatformIds); @endphp
                    <div class="bd-one bd-ra-10 p-20 h-100 d-flex flex-column oauth-card-box" 
                        style="border-color:{{ $isConnected ? '#10b981' : '#e5e7eb' }}; background-color:{{ $isConnected ? '#10b98108' : '#fff' }}; transition:all .3s; {{ $isConnected ? 'box-shadow: 0 4px 12px #10b9811a;' : '' }}">
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
                                <p class="fs-12 text-para-text text-truncate">{{ $page['name'] }}</p>
                            </div>
                        </div>
                        <div class="mt-auto">
                            @if(in_array($ig['id'], $existingPlatformIds))
                                <button type="button" class="w-100 py-11 px-18 bd-one bd-ra-4 fs-13 fw-600"
                                    style="border-color:#10b981; background:#10b9811a; color:#10b981;" disabled>
                                    <i class="fa-solid fa-check me-6"></i>{{ __('Connected') }}
                                </button>
                            @else
                                <button type="button"
                                    class="w-100 py-11 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600 connect-oauth-btn"
                                    data-page-id="{{ $ig['id'] }}"
                                    data-page-name="{{ '@' . ($ig['username'] ?? $ig['id']) }}"
                                    data-access-token="{{ $page['access_token'] }}"
                                    data-platform-type="{{ PLATFORM_INSTAGRAM }}"
                                    data-ig-user-id="{{ $ig['id'] }}"
                                    data-fb-page-id="{{ $page['id'] }}"
                                    data-route="{{ route('admin.meta-oauth.save.page') }}">
                                    <i class="fa-solid fa-plug me-6"></i>{{ __('Connect Instagram') }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── WhatsApp: Phone Number Picker ──────────────────────────────────── --}}
    @if(!empty($waPhones))
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
        <div class="row rg-15">
            @foreach($waPhones as $phone)
            <div class="col-md-6 col-lg-4">
                @php $isConnected = in_array($phone['id'], $existingPlatformIds); @endphp
                <div class="bd-one bd-ra-10 p-20 h-100 d-flex flex-column oauth-card-box" 
                    style="border-color:{{ $isConnected ? '#10b981' : '#e5e7eb' }}; background-color:{{ $isConnected ? '#10b98108' : '#fff' }}; transition:all .3s; {{ $isConnected ? 'box-shadow: 0 4px 12px #10b9811a;' : '' }}">
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
                    <div class="mt-auto">
                        @if(in_array($phone['id'], $existingPlatformIds))
                            <button type="button" class="w-100 py-11 px-18 bd-one bd-ra-4 fs-13 fw-600"
                                style="border-color:#10b981; background:#10b9811a; color:#10b981;" disabled>
                                <i class="fa-solid fa-check me-6"></i>{{ __('Connected') }}
                            </button>
                        @else
                            <button type="button"
                                class="w-100 py-11 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600 connect-oauth-btn"
                                data-page-id="{{ $phone['id'] }}"
                                data-page-name="{{ $phone['display_phone_number'] ?? 'WhatsApp' }}"
                                data-access-token="{{ $oauthData['long_token'] }}"
                                data-platform-type="{{ PLATFORM_WHATSAPP }}"
                                data-phone-number-id="{{ $phone['id'] }}"
                                data-waba-id="{{ $phone['waba_id'] ?? '' }}"
                                data-route="{{ route('admin.meta-oauth.save.page') }}">
                                <i class="fa-solid fa-plug me-6"></i>{{ __('Connect WhatsApp') }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    {{-- Empty State --}}
    @if(empty($pages) && empty($waPhones))
        <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-50 text-center">
            <i class="fa-brands fa-meta fs-50 mb-20 text-para-text opacity-30"></i>
            <h5 class="fs-18 fw-600 text-textBlack">{{ __('No Accounts Found') }}</h5>
            <p class="fs-14 text-para-text mt-10">{{ __('We couldn’t find any Facebook Pages or Instagram Business accounts linked to this account.') }}</p>
            <a href="{{ route('admin.platforms.index') }}" class="btn btn-primary mt-25">{{ __('Go Back') }}</a>
        </div>
    @endif

</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/meta-oauth.js') }}"></script>
@endpush
