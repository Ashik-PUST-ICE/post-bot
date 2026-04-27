@extends('sadmin.layouts.app')

@push('title')
    {{ __('Dashboard') }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="d-flex align-items-center cg-5 pb-26">
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Dashboard') }}</h4>
            <span class="d-flex"><img src="{{ asset('assets/images/icon/hand-wave.svg') }}" alt="" /></span>
        </div>

        <div class="bd-one bd-c-stroke bd-ra-10 p-30 bg-white text-center">
            <p class="fs-16 fw-400 text-para-text">{{ __('Welcome to Super Admin Panel') }}</p>
        </div>
    </div>
@endsection
