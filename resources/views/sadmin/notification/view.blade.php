@extends('sadmin.layouts.app')
@push('title')
{{ __('Create Ticket') }}
@endpush
@section('content')
<!-- Content -->
<div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
    <div class="p-sm-30 p-15">
        <div class="d-flex align-items-start cg-15">
            <div
                class="flex-grow-0 flex-shrink-0 w-32 h-32 rounded-circle d-flex justify-content-center align-items-center bg-main-color">
                <img src="{{asset("assets/images/icon/bell-white.svg")}}" alt="" />
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center pb-8">
                    <p class="fs-13 fw-500 lh-20 text-title-black">{{ $singleNotification->title }}</p>
                    <p class="fs-10 fw-400 lh-20 text-para-text">{{ $singleNotification->created_at?->diffForHumans() }}
                    </p>
                </div>
                <p class="fs-12 fw-400 lh-17 text-para-text text-justify">
                    {!! $singleNotification->body !!}</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="{{asset('agent/assets/js/custom/notification.js')}}"></script>
@endpush
