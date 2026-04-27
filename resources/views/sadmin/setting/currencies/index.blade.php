@extends('sadmin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>

            <input type="hidden" id="currency-route" value="{{ route('super-admin.setting.currencies.index') }}">

            <div class="table-wrap-one">
                <div class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                    <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                        <div class="search-one flex-grow-1 max-w-207">
                            <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt=""/>
                            </button>
                            <input type="text" placeholder="{{ __('Search here...') }}" id="superAdminCurrencySearch"/>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                        <button
                            class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                            type="button" data-bs-toggle="modal" data-bs-target="#add-modal">
                            {{ __('+ Add Currency') }}
                        </button>
                    </div>
                </div>
                <table class="table zTable zTable-last-item-right" id="commonDataTable">
                    <thead>
                    <tr>
                        <th>
                            <div>{{ __('#SL') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Code') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Symbol') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Placemnent') }}</div>
                        </th>
                        <th>
                            <div>{{ __('Action') }}</div>
                        </th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
    <!-- Page content area end -->
    <!-- Add Modal section start -->
    <div class="modal fade" id="add-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Add Currency') }}</h4>
                    <button type="button"
                            class="border-0 p-0 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset" action="{{ route('super-admin.setting.currencies.store') }}" method="post"
                      data-handler="settingCommonHandler">
                    @csrf

                    <div class="row rg-20 pb-20">
                        <div class="col-12">
                            <label for="currency_code" class="zForm-label">{{ __('Currency ISO Code') }} <span
                                    class="text-danger">*</span></label>
                            <select id="sf-select-currency-add" class="primary-form-control" name="currency_code">
                                @foreach (getCurrency() as $code => $currencyItem)
                                    <option value="{{ $code }}">{{ $currencyItem }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="symbol" class="zForm-label">{{ __('Symbol') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="symbol" id="symbol" placeholder="{{ __('e.g. $') }}"
                                   class="form-control zForm-control">
                        </div>
                        <div class="col-12">
                            <label for="currency_placement" class="zForm-label">{{ __('Currency Placement') }}
                                <span class="text-danger">*</span></label>
                            <select class="sf-select-without-search primary-form-control" name="currency_placement">
                                <option value="">--{{ __('Select Option') }}--</option>
                                <option value="before">{{ __('Before Amount (e.g. $100)') }}</option>
                                <option value="after">{{ __('After Amount (e.g. 100$)') }}</option>
                            </select>
                        </div>
                        <div class="col-12 mt-4">
                            <div class="d-flex form-check ps-0">
                                <div class="zCheck form-check form-switch">
                                    <input class="form-check-input mt-0" value="1" name="current_currency"
                                           type="checkbox"
                                           id="flexCheckChecked">
                                </div>
                                <label class="form-check-label ps-3 d-flex" for="flexCheckChecked">
                                    {{ __('Current Currency') }}
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex g-12 flex-wrap pt-20 bd-t-one bd-c-light-border">
                        <button
                            class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14"
                            type="submit">{{
                        __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Modal section end -->
    <!-- Edit Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">

            </div>
        </div>
    </div>
    <!-- Edit Modal section end -->
@endsection
@push('script')
    <script src="{{ asset('sadmin/js/currencies.js') }}"></script>
@endpush
