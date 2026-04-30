@extends('sadmin.layouts.app')

@push('title')
    {{ __('Dashboard') }}
@endpush

@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        {{-- Page title --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between g-10 pb-22">
            <div class="d-flex align-items-center cg-10">
                <h4 class="fs-24 fw-600 lh-29 text-textBlack mb-0">{{ __('Super Admin') }} — {{ __('Dashboard') }}</h4>
                <span class="d-flex"><img src="{{ asset('assets/images/icon/hand-wave.svg') }}" alt="" /></span>
            </div>
            <span class="fs-13 text-para-text">{{ now()->translatedFormat('l, j F Y') }}</span>
        </div>

        {{-- Insight strip --}}
        <div class="bd-one bd-c-stroke bd-ra-10 p-20 mb-26 bg-white position-relative overflow-hidden">
            <div class="position-absolute top-0 start-0 w-100" style="height:4px;background:linear-gradient(90deg,#6366f1,#10b981,#0ea5e9);"></div>
            <div class="row align-items-center rg-15">
                <div class="col-lg">
                    <p class="fs-12 fw-500 text-uppercase text-para-text mb-6 letter-spacing-1">{{ __('Platform overview') }}</p>
                    <h5 class="fs-18 fw-600 text-textBlack mb-6">{{ getOption('app_name') }}</h5>
                    <p class="fs-13 text-para-text mb-0">
                        {{ __('Subscriptions, billing, users, and plans — central control for your SaaS.') }}
                    </p>
                </div>
                <div class="col-lg-auto">
                    <div class="d-flex flex-wrap justify-content-lg-end g-20">
                        <div class="text-lg-end">
                            <p class="fs-12 text-para-text mb-4">{{ __('New businesses (7 days)') }}</p>
                            <p class="fs-22 fw-700 text-textBlack mb-0">{{ $newBusinessUsersWeek }}</p>
                        </div>
                        <div class="text-lg-end">
                            <p class="fs-12 text-para-text mb-4">{{ __('Pending payments') }}</p>
                            <p class="fs-22 fw-700 mb-0" style="color:#f59e0b;">{{ $pendingOrders }}</p>
                        </div>
                        <div class="text-lg-end">
                            <p class="fs-12 text-para-text mb-4">{{ __('Logged in as') }}</p>
                            <p class="fs-14 fw-600 text-textBlack mb-0">{{ auth()->user()->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary cards (no messages / conversations / AI) --}}
        @php
            $revDisplay = number_format($paidRevenue, 2) . ' ' . getCurrencySymbol();
            $revMonthDisplay = number_format($revenueThisMonth, 2) . ' ' . getCurrencySymbol();
            $cards = [
                ['icon' => 'fa-solid fa-building', 'color' => '#6366f1', 'label' => __('Organizations'), 'value' => $totalOrganizations],
                ['icon' => 'fa-solid fa-user-tie', 'color' => '#8b5cf6', 'label' => __('Business accounts'), 'value' => $businessUsers],
                ['icon' => 'fa-solid fa-credit-card', 'color' => '#ec4899', 'label' => __('Active subscriptions'), 'value' => $activeSubscriptions],
                ['icon' => 'fa-solid fa-sack-dollar', 'color' => '#059669', 'label' => __('Paid revenue (all time)'), 'value' => $revDisplay],
                ['icon' => 'fa-solid fa-receipt', 'color' => '#0ea5e9', 'label' => __('Paid orders (total)'), 'value' => $paidOrdersTotal],
                ['icon' => 'fa-solid fa-box-open', 'color' => '#f59e0b', 'label' => __('Subscription plans'), 'value' => $subscriptionPlans],
                ['icon' => 'fa-solid fa-plug', 'color' => '#14b8a6', 'label' => __('Connected platforms'), 'value' => $connectedPlatforms],
                ['icon' => 'fa-solid fa-calendar-week', 'color' => '#ef4444', 'label' => __('Revenue (this month)'), 'value' => $revMonthDisplay],
            ];
        @endphp
        {{-- Summary cards (same shell as admin dashboard: padding, icon, fs-24 value) --}}
        <div class="row rg-20 pb-26">
            @foreach ($cards as $c)
                <div class="col-xl-3 col-md-6">
                    <div class="bd-one bd-c-stroke bd-ra-10 bg-white d-flex align-items-center cg-15 py-24 px-22">
                        <div class="wh-50 bd-ra-50 d-flex align-items-center justify-content-center flex-shrink-0"
                            style="background:{{ $c['color'] }}1a;">
                            <i class="{{ $c['icon'] }} fs-20" style="color:{{ $c['color'] }}"></i>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <p class="fs-13 fw-400 text-para-text mb-6">{{ $c['label'] }}</p>
                            <h4 class="fs-24 fw-700 lh-29 text-textBlack mb-0 text-break">{{ $c['value'] }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Plans & income note --}}
        <div class="row rg-20 pb-26">
            <div class="col-12">
                <div class="bd-one bd-c-stroke bd-ra-10 p-20 bg-white">
                    <p class="fs-14 fw-600 text-textBlack mb-8">
                        <i class="fa-solid fa-circle-info text-main-color me-2"></i>{{ __('Plans & revenue') }}
                    </p>
                    <p class="fs-13 text-para-text mb-0">
                        {{ __('Each row shows how many business users are on a plan, how many paid orders that plan has generated, and total paid revenue from those orders.') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Per-plan table + chart --}}
        <div class="row rg-20 pb-26">
            <div class="col-xl-7">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 h-100">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('Plans: users & paid income') }}
                    </h5>
                    @if ($planRows->isEmpty())
                        <p class="fs-13 text-para-text mb-0">{{ __('No packages defined yet.') }}</p>
                    @else
                        <div class="table-responsive">
                            <table class="table zTable mb-0">
                                <thead>
                                    <tr>
                                        <th><div>{{ __('Plan') }}</div></th>
                                        <th><div class="text-nowrap">{{ __('Users on plan') }}</div></th>
                                        <th><div class="text-nowrap">{{ __('Paid orders') }}</div></th>
                                        <th class="text-end"><div class="text-nowrap">{{ __('Paid revenue') }}</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($planRows as $pr)
                                        <tr>
                                            <td class="fw-500 text-textBlack">{{ $pr->name }}</td>
                                            <td>{{ $pr->active_users }}</td>
                                            <td>{{ $pr->paid_orders }}</td>
                                            <td class="text-end fw-600">
                                                {{ number_format($pr->paid_revenue, 2) }} {{ getCurrencySymbol() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-xl-5">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 h-100">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('Paid revenue by plan') }}
                    </h5>
                    @if ($planRows->isEmpty())
                        <p class="fs-13 text-para-text mb-0">{{ __('No packages to chart.') }}</p>
                    @else
                        <div id="saPlanRevenueChart"></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick actions --}}
        <div class="row rg-20 pb-26">
            <div class="col-12">
                <div class="bd-one bd-c-stroke bd-ra-10 p-18 bg-white">
                    <p class="fs-13 fw-600 text-textBlack mb-12">{{ __('Quick actions') }}</p>
                    <div class="d-flex flex-wrap g-10">
                        <a href="{{ route('super-admin.packages.index') }}"
                            class="d-inline-flex align-items-center cg-8 py-10 px-16 bd-one bd-c-stroke bd-ra-8 fs-13 fw-500 text-textBlack text-decoration-none">
                            <i class="fa-solid fa-box-open text-main-color"></i> {{ __('Packages') }}
                        </a>
                        <a href="{{ route('super-admin.user.list') }}"
                            class="d-inline-flex align-items-center cg-8 py-10 px-16 bd-one bd-c-stroke bd-ra-8 fs-13 fw-500 text-textBlack text-decoration-none">
                            <i class="fa-solid fa-users text-main-color"></i> {{ __('Users') }}
                        </a>
                        <a href="{{ route('super-admin.subscriptions.orders') }}"
                            class="d-inline-flex align-items-center cg-8 py-10 px-16 bd-one bd-c-stroke bd-ra-8 fs-13 fw-500 text-textBlack text-decoration-none">
                            <i class="fa-solid fa-file-invoice-dollar text-main-color"></i> {{ __('Orders') }}
                        </a>
                        <a href="{{ route('super-admin.packages.user') }}"
                            class="d-inline-flex align-items-center cg-8 py-10 px-16 bd-one bd-c-stroke bd-ra-8 fs-13 fw-500 text-textBlack text-decoration-none">
                            <i class="fa-solid fa-user-tag text-main-color"></i> {{ __('User packages') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row rg-20 pb-26">
            <div class="col-xl-6">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 h-100">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('New business accounts – Last 7 Days') }}
                    </h5>
                    <div id="saSignupsChart"></div>
                </div>
            </div>

            <div class="col-xl-6">
                <div class="bd-one bd-c-stroke bd-ra-10 bg-white p-25 h-100">
                    <h5 class="fs-16 fw-600 text-textBlack pb-18 bd-b-one bd-c-stroke mb-20">
                        {{ __('Billing snapshot') }}
                    </h5>
                    <div class="row rg-15">
                        <div class="col-sm-6">
                            <div class="bd-one bd-c-stroke bd-ra-8 p-16">
                                <p class="fs-12 text-para-text mb-6">{{ __('This month (paid)') }}</p>
                                <p class="fs-20 fw-700 text-textBlack mb-0">
                                    {{ number_format($revenueThisMonth, 2) }} {{ getCurrencySymbol() }}
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bd-one bd-c-stroke bd-ra-8 p-16">
                                <p class="fs-12 text-para-text mb-6">{{ __('Subscription plans') }}</p>
                                <p class="fs-20 fw-700 text-textBlack mb-0">{{ $subscriptionPlans }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between py-10 bd-b-one bd-c-stroke">
                                <span class="fs-13 text-para-text">{{ __('Paid orders (all time)') }}</span>
                                <span class="fs-15 fw-700 text-textBlack">{{ $paidOrdersTotal }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between py-10">
                                <span class="fs-13 text-para-text">{{ __('Paid revenue (all time)') }}</span>
                                <span class="fs-15 fw-700 text-textBlack">{{ number_format($paidRevenue, 2) }} {{ getCurrencySymbol() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        var saSignupsChart = new ApexCharts(document.querySelector('#saSignupsChart'), {
            chart: {
                type: 'area',
                height: 240,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            series: [{
                name: '{{ __('Signups') }}',
                data: @json($chartSignups)
            }],
            xaxis: {
                categories: @json($chartLabels)
            },
            colors: ['#8b5cf6'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2
            },
            grid: {
                borderColor: '#f1f1f1'
            },
            dataLabels: {
                enabled: false
            },
        });
        saSignupsChart.render();

        @if (!$planRows->isEmpty())
            var saPlanRevChart = new ApexCharts(document.querySelector('#saPlanRevenueChart'), {
                chart: {
                    type: 'bar',
                    height: {{ max(320, $planRows->count() * 44) }},
                    toolbar: {
                        show: false
                    }
                },
                series: [{
                    name: '{{ __('Paid revenue') }}',
                    data: @json($planRevenueValues)
                }],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 4,
                        barHeight: '72%',
                        distributed: true,
                        dataLabels: {
                            position: 'bottom'
                        },
                    }
                },
                colors: ['#6366f1', '#8b5cf6', '#10b981', '#0ea5e9', '#f59e0b', '#ec4899', '#059669'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val >= 1000 ? val.toFixed(0) : (val > 0 ? val.toFixed(2) : '0');
                    },
                    offsetX: 6,
                    style: {
                        fontSize: '11px'
                    }
                },
                xaxis: {
                    categories: @json($planRevenueLabels),
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return parseFloat(val).toFixed(2) + ' {{ getCurrencySymbol() }}';
                        }
                    }
                },
                grid: {
                    borderColor: '#f1f1f1'
                },
                legend: {
                    show: false
                },
            });
            saPlanRevChart.render();
        @endif
    </script>
@endpush
