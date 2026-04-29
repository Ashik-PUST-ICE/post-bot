@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<!-- Page content area start -->
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $title }}</h4>
    <div class="table-wrap-one">
        <div
            class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
            <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                <div class="search-one flex-grow-1 max-w-207">
                    <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt="" /></button>
                    <input type="text" id="searchByUser" placeholder="{{ __('Search here...') }}" />
                </div>
            </div>
            <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                <a href="{{route('super-admin.user.add-new')}}"
                    class="py-13 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white">
                    <i class="fa fa-plus"></i> {{ __('Add User') }}
                </a>
            </div>
        </div>
        <table class="table zTable zTable-last-item-right" id="userTable" aria-describedby="customersTable_info">
            <thead>
                <tr>
                    <th>
                        <div class="text-nowrap">{{ __('User Name') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Emails') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Package') }}</div>
                    </th>
                    <th>
                        <div class="text-nowrap">{{ __('Created Date') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Country') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Status') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Action') }}</div>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
    <input type="hidden" id="userTable-route" value="{{ route('super-admin.user.list') }}">
    <!-- Page content area end -->
    @endsection
    @push('script')
    <script src="{{asset('sadmin/custom/js/user.js')}}"></script>
    @endpush
