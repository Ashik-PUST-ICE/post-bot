@extends('admin.layouts.app')
@push('title'){{ $title }}@endpush

@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">

    {{-- Hidden route inputs (read by inbox.js) --}}
    <input type="hidden" id="inboxTableRoute"   value="{{ route('admin.inbox.get.data') }}">
    <input type="hidden" id="inboxStatusFilter" value="all">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
        <div>
            <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Inbox') }}</h4>
            <p class="fs-14 fw-400 text-para-text mt-5">{{ __('All incoming customer conversations across platforms.') }}</p>
        </div>
        <div class="d-flex align-items-center cg-10 flex-wrap">
            {{-- Platform filter --}}
            <select id="inboxPlatformFilter" class="form-control zForm-control" style="min-width:160px;">
                <option value="">{{ __('All Platforms') }}</option>
                @foreach($platforms as $p)
                    <option value="{{ $p->platform_type }}">{{ platformTypes($p->platform_type) }} — {{ $p->platform_name }}</option>
                @endforeach
            </select>
            {{-- Search --}}
            <div class="position-relative">
                <input type="text" id="inboxSearch" class="form-control zForm-control ps-36"
                    placeholder="{{ __('Search by name...') }}" style="min-width:200px;">
                <i class="fa-solid fa-magnifying-glass position-absolute top-50 translate-middle-y text-para-text fs-13" style="left:12px;"></i>
            </div>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="d-flex align-items-center cg-8 pb-20 flex-wrap">
        @php
            $tabs = [
                ['status' => 'all',                         'label' => __('All'),       'count' => $counts['all']],
                ['status' => CONVERSATION_STATUS_OPEN,      'label' => __('Open'),      'count' => $counts['open']],
                ['status' => CONVERSATION_STATUS_PENDING,   'label' => __('Pending'),   'count' => $counts['pending']],
                ['status' => CONVERSATION_STATUS_RESOLVED,  'label' => __('Resolved'),  'count' => $counts['resolved']],
                ['status' => CONVERSATION_STATUS_ESCALATED, 'label' => __('Escalated'), 'count' => $counts['escalated']],
            ];
        @endphp
        @foreach($tabs as $i => $tab)
            <button class="inbox-tab py-9 px-16 bd-one bd-ra-4 fs-13 fw-500 d-flex align-items-center cg-6
                {{ $i === 0 ? 'bg-main-color text-white bd-c-main-color' : 'bg-white text-textBlack bd-c-stroke' }}"
                data-status="{{ $tab['status'] }}">
                {{ $tab['label'] }}
                <span class="py-2 px-8 bd-ra-50 fs-11 fw-700
                    {{ $i === 0 ? 'bg-white text-main-color' : 'bg-body text-textBlack' }}">
                    {{ $tab['count'] }}
                </span>
            </button>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="bd-one bd-c-stroke bd-ra-10 bg-white">
        <div class="p-20">
            <table id="inboxTable" class="table zTable zTable-last-item-right w-100">
                <thead>
                    <tr>
                        <th><div>{{ __('Platform') }}</div></th>
                        <th><div>{{ __('Contact') }}</div></th>
                        <th><div>{{ __('Last Message') }}</div></th>
                        <th><div>{{ __('Status') }}</div></th>
                        <th><div>{{ __('AI Replies') }}</div></th>
                        <th><div>{{ __('Last Active') }}</div></th>
                        <th><div>{{ __('Action') }}</div></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/custom/js/inbox.js') }}"></script>
@endpush
