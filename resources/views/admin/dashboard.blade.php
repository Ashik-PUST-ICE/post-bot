@extends('admin.layouts.app')
@push('title')
    {{ __('Dashboard') }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="d-flex align-items-center justify-content-between flex-wrap g-10 pb-26">
            <div class="d-flex align-items-center cg-10">
                <h4 class="fs-24 fw-600 lh-29 text-textBlack">{{ __('Dashboard') }}</h4>
                <span class="d-flex"><img src="{{ asset('assets/images/icon/hand-wave.svg') }}" alt="" /></span>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="row rg-20 pb-26">
            @php
                $cards = [
                    ['icon' => 'fa-solid fa-envelope-open-text', 'color' => '#6366f1', 'label' => __('Total Messages'),      'value' => $totalMessages],
                    ['icon' => 'fa-solid fa-robot',             'color' => '#10b981', 'label' => __('AI Replies'),           'value' => $aiReplied],
                    ['icon' => 'fa-solid fa-user-check',        'color' => '#f59e0b', 'label' => __('Human Replies'),        'value' => $humanReplied],
                    ['icon' => 'fa-solid fa-comments',          'color' => '#3b82f6', 'label' => __('Total Conversations'),  'value' => $totalConversations],
                    ['icon' => 'fa-solid fa-circle-check',      'color' => '#10b981', 'label' => __('Resolved'),             'value' => $resolvedConversations],
                    ['icon' => 'fa-solid fa-triangle-exclamation', 'color' => '#ef4444', 'label' => __('Escalated'),         'value' => $escalatedConversations],
                    ['icon' => 'fa-solid fa-reply',             'color' => '#8b5cf6', 'label' => __('Reply Rate'),           'value' => $replyRate . '%'],
                    ['icon' => 'fa-solid fa-plug',              'color' => '#0ea5e9', 'label' => __('Connected Platforms'),  'value' => $connectedPlatforms],
                ];
            @endphp
            @foreach($cards as $c)
            <div class="col-xl-3 col-md-6">
                <div class="bd-one bd-c-stroke bd-ra-10 p-20 bg-white d-flex align-items-center cg-15">
                    <div class="wh-50 bd-ra-50 d-flex align-items-center justify-content-center flex-shrink-0"
                        style="background:{{ $c['color'] }}1a;">
                        <i class="{{ $c['icon'] }} fs-20" style="color:{{ $c['color'] }}"></i>
                    </div>
                    <div>
                        <p class="fs-13 fw-400 text-para-text">{{ $c['label'] }}</p>
                        <h4 class="fs-24 fw-700 text-textBlack">{{ $c['value'] }}</h4>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row rg-20">

            {{-- Messages Chart (Last 7 Days) --}}
            <div class="col-xl-8">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('Messages – Last 7 Days') }}
                    </h5>
                    <div id="messagesChart"></div>
                </div>
            </div>

            {{-- Platform Breakdown --}}
            <div class="col-xl-4">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('Conversations by Platform') }}
                    </h5>
                    @if($platformBreakdown->isEmpty())
                        <div class="text-center py-30">
                            <p class="fs-13 text-para-text">{{ __('No data yet.') }}</p>
                        </div>
                    @else
                        <div id="platformPieChart"></div>
                        <div class="d-flex flex-column rg-10 mt-20">
                            @foreach($platformBreakdown as $row)
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center cg-8">
                                        <div class="wh-10 bd-ra-50" style="background:{{ platformColors($row->platform_type) }}"></div>
                                        <span class="fs-13 fw-500 text-textBlack">{{ platformTypes($row->platform_type) }}</span>
                                    </div>
                                    <span class="fs-14 fw-700 text-textBlack">{{ $row->total }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Conversation Statuses --}}
            <div class="col-xl-12">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('Conversation Status Overview') }}
                    </h5>
                    <div class="row rg-20">
                        @php
                            $statusRows = [
                                ['label' => __('Open'),      'value' => $openConversations,      'color' => '#3b82f6', 'total' => $totalConversations],
                                ['label' => __('Resolved'),  'value' => $resolvedConversations,  'color' => '#10b981', 'total' => $totalConversations],
                                ['label' => __('Escalated'), 'value' => $escalatedConversations, 'color' => '#ef4444', 'total' => $totalConversations],
                            ];
                        @endphp
                        @foreach($statusRows as $s)
                            <div class="col-md-4">
                                <div class="bd-one bd-c-stroke bd-ra-8 p-18">
                                    <div class="d-flex align-items-center justify-content-between mb-10">
                                        <span class="fs-14 fw-500 text-textBlack">{{ $s['label'] }}</span>
                                        <span class="fs-15 fw-700 text-textBlack">{{ $s['value'] }}</span>
                                    </div>
                                    <div class="progress" style="height:6px;border-radius:3px;">
                                        @php $pct = $s['total'] > 0 ? round(($s['value']/$s['total'])*100) : 0; @endphp
                                        <div class="progress-bar" role="progressbar"
                                            style="width:{{ $pct }}%;background:{{ $s['color'] }};border-radius:3px;"
                                            aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <p class="fs-12 text-para-text mt-8">{{ $pct }}% {{ __('of total') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('script')
<script>
    // Messages Line Chart
    var messagesChart = new ApexCharts(document.querySelector('#messagesChart'), {
        chart:  { type: 'area', height: 250, toolbar: { show: false }, zoom: { enabled: false } },
        series: [
            { name: '{{ __('Inbound') }}',  data: @json($chartInbound) },
            { name: '{{ __('Outbound') }}', data: @json($chartOutbound) },
        ],
        xaxis:  { categories: @json($chartLabels) },
        colors: ['#6366f1', '#10b981'],
        fill:   { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.3, opacityTo: 0.05 } },
        stroke: { curve: 'smooth', width: 2 },
        grid:   { borderColor: '#f1f1f1' },
        legend: { position: 'top' },
        dataLabels: { enabled: false },
    });
    messagesChart.render();

    @if(!$platformBreakdown->isEmpty())
    // Platform Pie Chart
    var pieChart = new ApexCharts(document.querySelector('#platformPieChart'), {
        chart:  { type: 'donut', height: 200 },
        series: @json($platformBreakdown->pluck('total')),
        labels: @json($platformBreakdown->map(fn($r) => platformTypes_js($r->platform_type))),
        colors: @json($platformBreakdown->map(fn($r) => platformColors_js($r->platform_type))),
        legend: { show: false },
        dataLabels: { enabled: false },
    });
    pieChart.render();
    @endif

    // Helpers exposed from PHP (for JS labels)
    function platformTypes_js(t) {
        var m = {
            {{ PLATFORM_FACEBOOK_PAGE }}: '{{ __('Facebook Page') }}',
            {{ PLATFORM_MESSENGER }}:     '{{ __('Messenger') }}',
            {{ PLATFORM_WHATSAPP }}:      '{{ __('WhatsApp') }}',
            {{ PLATFORM_INSTAGRAM }}:     '{{ __('Instagram') }}',
        };
        return m[t] || 'Unknown';
    }
    function platformColors_js(t) {
        var m = {
            {{ PLATFORM_FACEBOOK_PAGE }}: '#1877F2',
            {{ PLATFORM_MESSENGER }}:     '#0084FF',
            {{ PLATFORM_WHATSAPP }}:      '#25D366',
            {{ PLATFORM_INSTAGRAM }}:     '#E1306C',
        };
        return m[t] || '#6B7280';
    }
</script>
@endpush
