@extends('user.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Page content area start -->
    <div class="px-24 pb-24 position-relative">
        <!-- Table -->
        <div class="p-20 bd-one bd-c-stroke-color bd-ra-12 bg-white overflow-hidden">
            <h4 class="fs-18 fw-600 lh-24 text-textBlack pb-13">{{ __('Customer Details') }}</h4>
            <div id="customersTable_wrapper" class="dataTables_wrapper no-footer">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="py-23 px-25 bd-ra-10 bg-bg-color">
                            <div class="pb-11 mb-25 border-bottom bd-c-ededed">
                                <h4 class="fs-18 fw-500 lh-22 text-1b1c17 pb-10">{{ __('Profile Bio') }}</h4>
                            </div>
                            <ul class="zList-one">
                                <li class="d-flex align-items-center">
                                    <p class="fw-600 fs-16">{{ __('Full Name') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->name }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('Email') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->email }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('Phone') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->mobile }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('Country') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->userDetail->billing_country }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('State') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->userDetail->billing_state }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('City') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->userDetail->billing_city }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('Zip Code') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->userDetail->billing_zip_code }}</p>
                                </li>
                                <li class="d-flex align-items-center pt-1">
                                    <p class="fw-600 fs-16">{{ __('Address') }} :</p>
                                    <p class="fs-14">{{ $customer_detail->userDetail->billing_address }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page content area end -->
    @endsection
