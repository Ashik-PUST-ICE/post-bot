@extends('admin.layouts.app')
@push('title')
{{ $pageTitle }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
        <div class="table-wrap-one">
            <div class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                <div class="search-one flex-grow-1 max-w-207">
                    <button class="icon"><img src="{{asset('assets/images/icon/search.svg')}}" alt="" /></button>
                    <input type="text" placeholder="{{__("Search here")}}..." id="appraisementListTableSearch" />
                </div>
                <div class="flex-grow-1 max-sm-w-174">
                    <select class="sf-select-without-search" id="searchBySessionType">
                        @foreach($sessionType as $type)
                            <option value="{{$type->name}}">{{$type->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <table class="table zTable zTable-last-item-right" id="processApprovalListTable">
                <thead>
                <tr>
                    <th><div class="text-nowrap">{{__("Session Name")}}</div></th>
                    <th><div class="text-nowrap">{{__("Session Type")}}</div></th>
                    <th><div class="text-nowrap">{{__("Employee Name")}}</div></th>
                    <th><div class="text-nowrap">{{__("Email Address")}}</div></th>
                    <th><div class="text-nowrap">{{__("Status")}}</div></th>
                    <th><div>{{__("Action")}}</div></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <input type="hidden" value="{{route('admin.approval-process.list')}}" id="processApprovalListRoute">
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/approval_process.js') }}"></script>
@endpush
