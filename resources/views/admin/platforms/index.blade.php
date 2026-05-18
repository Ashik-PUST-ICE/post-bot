@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Hidden route inputs (read by platforms.js) --}}
    <input type="hidden" id="platformTableRoute"   value="{{ route('admin.platforms.get.data') }}">
    <input type="hidden" id="platformInfoRoute"    value="{{ route('admin.platforms.get.info') }}">
    <input type="hidden" id="platformUpdateRoute"  value="{{ url('admin/platforms/update/:id') }}">
    <input type="hidden" id="whatsappTypeValue"    value="{{ PLATFORM_WHATSAPP }}">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
        <div>
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Platform Connections') }}</h4>
            <p class="fs-14 fw-400 text-para-text mt-5">{{ __('Connect your Facebook, WhatsApp, and Instagram accounts.') }}</p>
        </div>
        <div class="d-flex align-items-center cg-10 flex-wrap">
            @php $metaConfig = \App\Models\MetaAppConfig::forUser(auth()->id()); @endphp

            @if($metaConfig->hasFacebook())
                {{-- ✅ App ID + Secret are saved → show OAuth buttons --}}
                <div class="dropdown">
                    <button class="py-11 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14 d-flex align-items-center cg-8 dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <i class="fa-brands fa-meta"></i> {{ __('Connect via Meta OAuth') }}
                    </button>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item d-flex align-items-center cg-10 py-10 px-15"
                                href="{{ route('admin.meta-oauth.redirect', ['platform' => 'facebook']) }}">
                                <i class="fa-brands fa-facebook fs-16" style="color:#1877F2;width:20px;"></i>
                                <span class="fs-13 fw-500">{{ __('Facebook Page / Messenger') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center cg-10 py-10 px-15"
                                href="{{ route('admin.meta-oauth.redirect', ['platform' => 'instagram']) }}">
                                <i class="fa-brands fa-instagram fs-16" style="color:#E1306C;width:20px;"></i>
                                <span class="fs-13 fw-500">{{ __('Instagram Business') }}</span>
                            </a>
                        </li>
                        @if($metaConfig->hasWhatsApp())
                        <li>
                            <a class="dropdown-item d-flex align-items-center cg-10 py-10 px-15"
                                href="{{ route('admin.meta-oauth.redirect', ['platform' => 'whatsapp']) }}">
                                <i class="fa-brands fa-whatsapp fs-16" style="color:#25D366;width:20px;"></i>
                                <span class="fs-13 fw-500">{{ __('WhatsApp Business') }}</span>
                            </a>
                        </li>
                        @endif
                        <li><hr class="dropdown-divider my-5"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center cg-10 py-10 px-15 text-para-text"
                                href="#" data-bs-toggle="modal" data-bs-target="#addPlatformModal">
                                <i class="fa-solid fa-keyboard fs-14" style="width:20px;"></i>
                                <span class="fs-13">{{ __('Add Manually (Token)') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            @else
                {{-- ⚠️ No App credentials yet → guide user --}}
                <a href="{{ route('admin.meta-app.index') }}"
                    class="py-11 px-18 bd-one bd-ra-4 bd-c-stroke bg-white fs-13 fw-500 text-textBlack d-flex align-items-center cg-8">
                    <i class="fa-solid fa-circle-exclamation text-warning"></i>
                    {{ __('Set Meta App Credentials First') }}
                </a>
                <button class="py-11 px-18 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-13 fw-600 d-flex align-items-center cg-8"
                    data-bs-toggle="modal" data-bs-target="#addPlatformModal">
                    <i class="fa-solid fa-plus"></i> {{ __('Add Manually') }}
                </button>
            @endif
        </div>
    </div>

    {{-- Table --}}
    <div class="bd-one bd-c-stroke bd-ra-10 bg-white">
        <div class="p-20 bd-b-one bd-c-stroke d-flex align-items-center justify-content-between flex-wrap g-10">
            <h5 class="fs-16 fw-600 text-textBlack">{{ __('Connected Platforms') }}</h5>
            <div class="position-relative">
                <input type="text" id="platformSearch" class="form-control zForm-control ps-36"
                    placeholder="{{ __('Search...') }}" style="min-width:220px;">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-para-text fs-13" style="left:12px;"></i>
            </div>
        </div>
        <div class="p-20">
            <table id="platformTable" class="table zTable zTable-last-item-right w-100">
                <thead>
                    <tr>
                        <th><div>{{ __('#') }}</div></th>
                        <th><div>{{ __('Platform') }}</div></th>
                        <th><div>{{ __('Name') }}</div></th>
                        <th><div>{{ __('Page / Phone ID') }}</div></th>
                        <th><div>{{ __('Auto Reply') }}</div></th>
                        <th><div>{{ __('Status') }}</div></th>
                        <th><div>{{ __('Action') }}</div></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Add Platform Modal --}}
<div class="modal fade" id="addPlatformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                <h4 class="fs-18 fw-600 text-textBlack">{{ __('Add Platform Manually') }}</h4>
                <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>

            {{-- Manual vs OAuth tip --}}
            <div class="bd-one bd-ra-8 p-12 mb-20 d-flex align-items-start cg-10"
                style="background:#fffbeb; border-color:#fde68a;">
                <i class="fa-solid fa-circle-info fs-13 mt-1 flex-shrink-0" style="color:#d97706"></i>
                <p class="fs-12 text-para-text mb-0">
                    {{ __('Use this form only if you have a token from') }}
                    <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">{{ __('Meta Business System Users') }}</a>.
                    {{ __("For the easiest setup, use the 'Connect via Meta OAuth' button above — it fills everything automatically.") }}
                </p>
            </div>

            <form class="ajax reset" action="{{ route('admin.platforms.store') }}" method="POST"
                data-handler="commonResponse">
                @csrf
                <div class="d-flex flex-column rg-15 pb-20">
                    <div>
                        <label class="zForm-label">{{ __('Platform') }} <span class="text-red">*</span></label>
                        <select name="platform_type" id="platformTypeSelect" class="form-control zForm-control">
                            @foreach(platformTypes() as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Display Name') }} <span class="text-red">*</span></label>
                        <input type="text" name="platform_name" class="form-control zForm-control"
                            placeholder="{{ __('e.g. My Business Page') }}">
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Page / App ID') }}</label>
                        <input type="text" name="platform_id" class="form-control zForm-control"
                            placeholder="{{ __('Facebook Page ID or IG Business Account ID') }}">
                    </div>
                    <div class="whatsapp-field d-none">
                        <label class="zForm-label">{{ __('Phone Number ID') }}</label>
                        <input type="text" name="phone_number" class="form-control zForm-control"
                            placeholder="{{ __('WhatsApp Phone Number ID from Meta Dashboard') }}">
                    </div>
                    <div class="whatsapp-field d-none">
                        <label class="zForm-label">{{ __('WABA ID') }}</label>
                        <input type="text" name="waba_id" class="form-control zForm-control"
                            placeholder="{{ __('WhatsApp Business Account ID') }}">
                        <p class="fs-12 text-para-text mt-5">{{ __('Found in: Meta App Dashboard → WhatsApp → API Setup → WhatsApp Business Account ID.') }}</p>
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Access Token') }}</label>
                        <div class="position-relative">
                            <input type="password" name="access_token" id="addAccessToken"
                                class="form-control zForm-control"
                                placeholder="{{ __('Paste System User token (not needed for OAuth')  }}">
                            <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y toggle-token-vis"
                                data-target="addAccessToken" style="right:12px;">
                                <i class="fa-solid fa-eye fs-13 text-para-text"></i>
                            </button>
                        </div>
                        <p class="fs-12 text-para-text mt-5">
                            {{ __('Skip this if you connected via OAuth above. For manual entry, generate at') }}
                            <a href="https://business.facebook.com/settings/system-users" target="_blank" class="text-main-color">
                                {{ __('Meta Business Settings → System Users → Generate Token') }}
                            </a>
                        </p>
                    </div>
                </div>
                <div class="d-flex g-10 justify-content-end">
                    <button type="button" class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">
                        {{ __('Connect') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Platform Modal --}}
<div class="modal fade" id="editPlatformModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                <h4 class="fs-18 fw-600 text-textBlack">{{ __('Edit Connection') }}</h4>
                <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <form class="ajax" id="editPlatformForm" action="" method="POST" data-handler="commonResponse">
                @csrf
                <input type="hidden" id="edit_platform_id" name="id">
                <div class="d-flex flex-column rg-15 pb-20">
                    <div>
                        <label class="zForm-label">{{ __('Display Name') }} <span class="text-red">*</span></label>
                        <input type="text" id="edit_platform_name" name="platform_name" class="form-control zForm-control">
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Page / App ID') }}</label>
                        <input type="text" id="edit_platform_id_input" name="platform_id" class="form-control zForm-control" placeholder="{{ __('Facebook Page ID or IG Business Account ID') }}">
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Phone Number (WhatsApp)') }}</label>
                        <input type="text" id="edit_phone_number" name="phone_number" class="form-control zForm-control">
                    </div>
                    <div>
                        <label class="zForm-label">
                            {{ __('Access Token') }}
                        </label>
                        <div class="position-relative">
                            <input type="password" id="edit_access_token" name="access_token"
                                class="form-control zForm-control"
                                placeholder="{{ __('Your access token') }}">
                            <button type="button" class="border-0 bg-transparent position-absolute top-50 translate-middle-y toggle-token-vis"
                                data-target="edit_access_token" style="right:12px;">
                                <i class="fa-solid fa-eye fs-13 text-para-text"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="zForm-label">{{ __('Status') }}</label>
                        <select id="edit_status" name="status" class="form-control zForm-control">
                            <option value="{{ STATUS_ACTIVE }}">{{ __('Active') }}</option>
                            <option value="{{ DEACTIVATE }}">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <div class="d-flex g-10 justify-content-end">
                    <button type="button" class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14"
                        data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">
                        {{ __('Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/platforms.js') }}"></script>
@endpush
