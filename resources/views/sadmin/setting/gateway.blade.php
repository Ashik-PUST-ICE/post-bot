@extends('sadmin.layouts.app')
@section('content')
    @push('title')
        {{ $title }}
    @endpush
    @push('style')
        <style>
            .gateway-image {
                max-width: 100px;
                max-height: 40px;
                object-fit: contain;
            }
            .modal-inner-form-box .image {
                max-width: 150px;
                max-height: 60px;
                object-fit: contain;
            }
        </style>
    @endpush
    <!-- Page content area start -->
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>
        <div class="d-flex justify-content-lg-end">
            <a title="{{__('Sync missing gateway')}}" href="{{ route('super-admin.setting.gateway.syncs') }}" class="py-12 pr-15 pl-13 bd-one bg-main-color bd-ra-4 fs-14 text-white" onclick="return confirm('Are you sure you want to sync gateways?');">
                <i class="fa fa-sync-alt"></i>
            </a>
        </div>
        <div class="bd-one bd-c-light-border bd-ra-8 bg-white p-sm-25 p-15">
            <table class="table zTable zTable-last-item-right">
                <thead>
                <tr>
                    <th>
                        <div>{{ __('SL') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Image') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Title') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Slug') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Status') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Mode') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Action') }}</div>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($gateways as $gateway)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="max-w-150 site-language">
                                <img src="{{ asset($gateway->image) }}" class="gateway-image" alt="">
                            </div>
                        </td>
                        <td>{{ $gateway->title }}</td>
                        <td>{{ $gateway->slug }}</td>
                        <td>
                            @if ($gateway->status == ACTIVE)
                                <div class="zBadge zBadge-active">
                                    {{ __('Active') }}</div>
                            @else
                                <div class="zBadge zBadge-inactive">
                                    {{ __('Deactive') }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($gateway->mode == GATEWAY_MODE_LIVE)
                                <div class="status-btn status-btn-green font-13 radius-4">
                                    {{ __('Live') }}</div>
                            @elseif($gateway->slug != 'bank')
                                <div class="status-btn status-btn-orange font-13 radius-4">
                                    {{ __('Sandbox') }}</div>
                            @endif
                        </td>
                        <td>
                            <button type="button"
                                    class="p-0 bg-transparent w-30 h-30 ms-auto bd-one bd-c-stroke rounded-circle d-flex justify-content-center align-items-center edit"
                                    data-toggle="tooltip" title="{{ __('Edit') }}" data-id="{{encrypt($gateway->id)}}">
                                <img src="{{ asset('assets/images/icon/edit-black.svg') }}" alt="edit">
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Page content area end -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit Gateway') }}</h4>
                    <button type="button"
                            class="border-0 p-0 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('super-admin.setting.gateway.store') }}" method="POST"
                      data-handler="responseOnGatewaStore">
                    @csrf
                    <input type="hidden" name="id" id="id" required>

                    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __('Gateway') }}</h4>
                    <div class="modal-inner-form-box bg-off-white theme-border radius-4">
                        <div class="row">
                            <div class="upload-profile-photo-box mb-10">
                                <div class="profile-user position-relative d-inline-block max-w-150">
                                    <img src="" class="image" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="row rg-20">
                            <div class="col-12">
                                <label class="zForm-label">{{
                                __('Title') }}</label>
                                <input type="text" class="form-control zForm-control title" readonly>
                            </div>

                            <div class="col-12">
                                <label class="zForm-label">{{
                                __('Slug') }}</label>
                                <input type="text" name="slug" class="form-control zForm-control slug" readonly>
                            </div>
                            <div class="col-12">
                                <label class="zForm-label">{{
                                __('Status') }}</label>
                                <select name="status" id="status" class="sf-select-without-search">
                                    <option value="0">{{ __('Deactivate') }}</option>
                                    <option value="1">{{ __('Active') }}</option>
                                </select>
                            </div>
                            <div class="col-12 mode-div">
                                <label class="zForm-label">{{
                                __('Mode') }}</label>
                                <select name="mode" id="mode" class="sf-select-without-search">
                                    <option value="1">{{ __('Live') }}</option>
                                    <option value="2">{{ __('Sandbox') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="bank-div py-20">
                            <div class="bank-div-append">

                            </div>
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button"
                                            class="fs-15 fw-500 lh-25 border-0 py-10 px-26 bg-cdef84 hover-bg-one bd-ra-12 ml-10 border-0 green-color add-bank"
                                            title="{{ __('Add Bank') }}">
                                        <span class="iconify" data-icon="material-symbols:add"></span>
                                        {{ __('Add Bank') }}</button>
                                </div>
                            </div>
                        </div>

                        <div class="row url-div py-20">
                            <div class="col-md-12 gateway-input" id="gateway-url">
                                <label class="zForm-label">{{
                                __('Url') }}
                                    /{{ __('Hash') }}</label>
                                <input class="form-control zForm-control" type="text" name="url">
                            </div>
                        </div>
                        <div class="row rg-20 key-secret-div">
                            <div class="col-md-12 gateway-input" id="gateway-key">
                                <div class="">
                                    <label class="zForm-label">{{
                                    __('Key') }}</label>
                                    <input class="form-control zForm-control" type="text" name="key">
                                </div>
                                <small class="d-none small">{{ __('Client id, Public Key, Key, Store id, Api Key')
                                }}</small>
                            </div>
                            <div class="col-md-12 gateway-input" id="gateway-secret">
                                <div class="">
                                    <label class="zForm-label">{{
                                    __('Secret') }}</label>
                                    <input class="form-control zForm-control" type="text" name="secret">
                                </div>
                                <small class="d-none small">{{ __('Client Secret, Secret, Store Password, Auth
                                Token') }}</small>
                            </div>
                        </div>
                        <div class="row pt-20">
                            <div class="col-md-12">
                                <label class="zForm-label d-flex align-items-center g-10">{{ __('Conversion
                                Rate') }}
                                    <button type="button"
                                            class="add-currency rounded-circle bg-main-color border-0 edit-btn fs-15 fw-500 lh-25 ml-10fs-15 text-white w-30 h-30 d-inline-flex justify-content-center align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21"
                                             viewBox="0 0 21 21">
                                            <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                  stroke-linejoin="round" d="M5.5 10.5h10m-5-5v10"/>
                                        </svg>
                                        </span>
                                    </button>
                                </label>
                                <div id="currencyConversionRateSection"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex g-10 pt-25">
                        <button type="button"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-body-text bg-white text-textBlack fs-14 fw-600 lh-14"
                                data-bs-dismiss="modal" title="{{ __('Back') }}">{{ __('Back') }}</button>
                        <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                                title="{{ __('Submit') }}">{{ __('Update') }}</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="getInfoRoute" value="{{ route('super-admin.setting.gateway.get.info') }}">
    <input type="hidden" id="getCurrencySymbol" value="{{ getCurrencySymbol() }}">
    <input type="hidden" id="allCurrency" value="{{ json_encode(getCurrency()) }}">
    <input type="hidden" id="gatewaySettings" value="{{ gatewaySettings() }}">
    <input type="hidden" id="supportedCurrency" value="{{json_encode(getGatewaySupportedCurrencies())}}">

@endsection
@push('script')
    <script src="{{ asset('sadmin/js/gateway.js') }}"></script>
@endpush
