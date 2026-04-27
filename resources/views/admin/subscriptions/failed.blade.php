@extends('admin.layouts.app')
@push('title')
{{ $pageTitle }}
@endpush
@section('content')
    <div class="px-24 pb-24 position-relative">
        <!-- Info & Add product button -->
        <div class="d-flex justify-content-between align-items-center g-10 flex-wrap pb-20">
            <div class="">
                <h4 class="fs-24 fw-500 lh-24 text-white"></h4>
            </div>
        </div>
        <div class="py-26 px-20 bg-white bd-one bd-c-stroke-color bd-ra-8 mb-20">
            <div class="align-items-center cg-10 d-flex flex-column justify-content-center">
                <div class="max-w-206 pb-22 m-auto"><img src="{{ asset('user/images/error.png') }}" alt=""/></div>
                <h4 class="pb-8 title">{{ __('Payment Failed') }}</h4>
                <p class="fs-14 fw-400 lh-24 text-para-text pb-13">
                    {{ __('We apologize, but it seems there was an issue processing your payment. Please check the following details: Payment Information, Sufficient Funds, Payment Method, Billing Address') }}
                </p>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <a href="{{ route('admin.dashboard') }}" class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white">
                        {{__('Go To Dashboard')}}
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
