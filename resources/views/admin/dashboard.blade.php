@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush

@section('content')
    @if (auth()->user()->role == USER_ROLE_ADMIN)
        <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
            <div class="row rg-26">
                <div class="col-lg-6">
                    <div class="bg-white p-sm-25 p-15 bd-ra-15 h-100">
                        <h3 class="fs-16 fw-600 lh-16 text-textBlack pb-26">{{ __('Overall Summery') }}</h3>
                        <div class="row rg-15">
                            <div class="col-sm-6">
                                <div class="bd-one bd-c-light-border bd-ra-15 bg-white px-sm-20 px-10 py-sm-25 py-10">
                                    <div class="pb-21 d-flex justify-content-between align-items-center">
                                        <h4 class="fs-32 fw-600 lh-32 text-textBlack">
                                            {{ $totalEmployeeCount < 10 ? 0 . $totalEmployeeCount : $totalEmployeeCount }}
                                        </h4>
                                        <div
                                            class="flex-shrink-0 w-35 h-35 bd-ra-12 d-flex align-items-center justify-content-center bg-dashboard-1">
                                            <img src="{{ asset('assets') }}/images/icon/calendar.svg" alt="" />
                                        </div>
                                    </div>
                                    <p class="fs-16 fw-400 lh-16 text-para-text">{{ __('Total Employees') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bd-one bd-c-light-border bd-ra-15 bg-white px-sm-20 px-10 py-sm-25 py-10">
                                    <div class="pb-21 d-flex justify-content-between align-items-center">
                                        <h4 class="fs-32 fw-600 lh-32 text-textBlack">
                                            {{ $totalDepartmentCount < 10 ? 0 . $totalDepartmentCount : $totalDepartmentCount }}
                                        </h4>
                                        <div
                                            class="flex-shrink-0 w-35 h-35 bd-ra-12 d-flex align-items-center justify-content-center bg-dashboard-2">
                                            <img src="{{ asset('assets') }}/images/icon/calendar-2.svg" alt="" />
                                        </div>
                                    </div>
                                    <p class="fs-16 fw-400 lh-16 text-para-text">{{ __('Total Departments') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bd-one bd-c-light-border bd-ra-15 bg-white px-sm-20 px-10 py-sm-25 py-10">
                                    <div class="pb-21 d-flex justify-content-between align-items-center">
                                        <h4 class="fs-32 fw-600 lh-32 text-textBlack">
                                            {{ $totalDivisionCount < 10 ? 0 . $totalDivisionCount : $totalDivisionCount }}
                                        </h4>
                                        <div
                                            class="flex-shrink-0 w-35 h-35 bd-ra-12 d-flex align-items-center justify-content-center bg-dashboard-3">
                                            <img src="{{ asset('assets') }}/images/icon/calendar-3.svg" alt="" />
                                        </div>
                                    </div>
                                    <p class="fs-16 fw-400 lh-16 text-para-text">{{ __('Total Division') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="bd-one bd-c-light-border bd-ra-15 bg-white px-sm-20 px-10 py-sm-25 py-10">
                                    <div class="pb-21 d-flex justify-content-between align-items-center">
                                        <h4 class="fs-32 fw-600 lh-32 text-textBlack">
                                            {{ $performerOfTheYearCount < 10 ? 0 . $performerOfTheYearCount : $performerOfTheYearCount }}
                                        </h4>
                                        <div
                                            class="flex-shrink-0 w-35 h-35 bd-ra-12 d-flex align-items-center justify-content-center bg-dashboard-4">
                                            <img src="{{ asset('assets') }}/images/icon/calendar-1.svg" alt="" />
                                        </div>
                                    </div>
                                    <p class="fs-16 fw-400 lh-16 text-para-text">{{ __('Performer Of The Year') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bg-white p-sm-25 p-15 bd-ra-15 h-100">
                        <h3 class="fs-16 fw-600 lh-16 text-textBlack pb-15">{{ __('All Departments') }}</h3>
                        <div
                            class="mb-18 bd-one bd-c-light-border bd-ra-8 bg-body-bg py-9 px-14 d-flex justify-content-between align-items-center flex-wrap">
                            <h4 class="fs-12 fw-400 lh-17 text-para-text"><span
                                    class="fw-600">{{ __('Employees') }}:</span>
                                {{ array_sum($allDepartmentEmployeeCountArray) }}</h4>
                        </div>
                        <div id="allDepartmentChart"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="bg-white p-sm-25 p-15 bd-ra-15 h-100 dashboard-summary-wrap">
                        <div class="rg-20 row">
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('KPI Session Summary') }}
                                    </h4>
                                    @if($kpiSessionSummaryChartEnable)
                                        <div id="kpiSessionSummaryChart"></div>
                                    @else
                                        <div class="h-75 w-100 d-flex justify-content-center align-items-center">
                                            <p>{{__("Data not found!")}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('KPI Session Summary') }}
                                        ({{ __('Last year') }})
                                    </h4>
                                        <div id="kpiSessionSummaryLastYearChart"></div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('KPI Session Type') }}
                                    </h4>
                                    @if($kpiSessionTypeChartEnable)
                                        <div id="kpiSessionTypeChart"></div>
                                    @else
                                        <div class="h-75 w-100 d-flex justify-content-center align-items-center">
                                            <p>{{__("Data not found!")}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('Performer Of The Year') }}</h4>
                                    <div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Designation')}}</th>
                                                    <th>{{__('Year')}}</th>
                                                    <th>{{__('Point')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employeeRatingHistories as $employeeRatingHistory)
                                                    <tr>
                                                        <td>{{ $employeeRatingHistory->name }}</td>
                                                        <td>{{ $employeeRatingHistory->designation_title }}</td>
                                                        <td>{{ $employeeRatingHistory->year }}</td>
                                                        <td>{{ number_format($employeeRatingHistory->get_avg_rating_point, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
            <div class="row rg-26">
                <div class="col-12">
                    <div class="bg-white p-sm-25 p-15 bd-ra-15 h-100 dashboard-summary-wrap">
                        <div class="rg-20 row">
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('KPI Session Summary') }}
                                    </h4>
                                    @if($kpiSessionSummaryChartEnable)
                                        <div id="kpiSessionSummaryChart"></div>
                                    @else
                                        <div class="h-75 w-100 d-flex justify-content-center align-items-center">
                                            <p>{{__("Data not found!")}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('KPI Session Summary') }}
                                        ({{ __('Last year') }})
                                    </h4>
                                    <div id="kpiSessionSummaryLastYearChart"></div>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('KPI Session Type') }}
                                    </h4>
                                    @if($kpiSessionTypeChartEnable)
                                        <div id="kpiSessionTypeChart"></div>
                                    @else
                                        <div class="h-75 w-100 d-flex justify-content-center align-items-center">
                                            <p>{{__("Data not found!")}}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-6">
                                <div class="dashboard-summary-item">
                                    <h4 class="pb-sm-59 pb-20 fs-16 fw-600 lh-16 text-textBlack">
                                        {{ __('Performer Of The Year') }}</h4>
                                    <div>
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{__('Name')}}</th>
                                                    <th>{{__('Designation')}}</th>
                                                    <th>{{__('Year')}}</th>
                                                    <th>{{__('Point')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($employeeRatingHistories as $employeeRatingHistory)
                                                    <tr>
                                                        <td>{{ $employeeRatingHistory->name }}</td>
                                                        <td>{{ $employeeRatingHistory->designation_title }}</td>
                                                        <td>{{ $employeeRatingHistory->year }}</td>
                                                        <td>{{ number_format($employeeRatingHistory->get_avg_rating_point, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('script')
    @if (auth()->user()->role == USER_ROLE_ADMIN)
        <script>
            // all department
            const allDepartmentNameArray = {!! json_encode($allDepartmentNameArray) !!};
            const allDepartmentEmployeeCountArray = {!! json_encode($allDepartmentEmployeeCountArray) !!};
            const allDepartmentEmployeeCountMin = {{ $allDepartmentEmployeeCountMin }};
            const allDepartmentEmployeeCountMax = {{ $allDepartmentEmployeeCountMax }};
        </script>
    @else
        <script>
            // all department
            const allDepartmentNameArray = [];
            const allDepartmentEmployeeCountArray = [];
            const allDepartmentEmployeeCountMin = [];
            const allDepartmentEmployeeCountMax = [];
        </script>
    @endif
    <script>
        // all kpi session
        const allKpiSessionStatusArray = {!! json_encode($allKpiSessionStatusArray) !!};
        const allKpiSessionStatusLastYearArray = {!! json_encode($allKpiSessionStatusLastYearArray) !!};

        // all kpi session type
        const allSessionTypeSessionArray = {!! json_encode($allSessionTypeSessionArray) !!};
        const sessionTypeColors = {!! json_encode($sessionTypeColors) !!};
        const allSessionTypeNameArray = {!! json_encode($allSessionTypeNameArray) !!};
    </script>
    <script src="{{ asset('admin/custom/js/admin-dashboard.js') }}"></script>
@endpush
