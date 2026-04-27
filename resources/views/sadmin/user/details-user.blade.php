@extends('sadmin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <!-- Page content area start -->
    <div data-aos="fade-up" data-aos-duration="1000" class="overflow-x-hidden">
        <div class="p-sm-30 p-15">
            <!-- Table -->
            <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $pageTitle }}</h4>

            <div class="row rg-20">
                <div class="col-xl-4 col-md-5">
                    <div class="bd-one bd-c-stroke bd-ra-8 bg-white p-sm-25 p-15">
                        <div class="w-105 h-105 rounded-circle overflow-hidden">
                            <img src="{{ asset(getFileUrl($user->image)) }}" alt=""/>
                        </div>
                        <div class="bd-t-one bd-c-stroke pt-22 mt-22">
                            <ul class="zList-pb-16">
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Name')}} :</h4></div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$user->name}}</p></div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Email')}} :</h4></div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$user->email}}</p></div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Mobile')}} :</h4>
                                    </div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$user->mobile}}</p></div>
                                </li>
                            </ul>
                        </div>

                        <div class="bd-t-one bd-c-stroke pt-15 mt-20">
                            <ul class="zList-pb-16">
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Address')}} :</h4>
                                    </div>
                                    <div class="col-6"><p
                                            class="fs-12 fw-500 lh-19 text-para-text">{{$user->address?? __("No")}}</p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Email Verify')}}
                                            :</h4></div>
                                    <div class="col-6">
                                        <p class="fs-12 fw-500 lh-19 text-para-text">
                                            @if ($user->email_verification_status == ACTIVE)
                                                {{__('Yes')}}
                                            @else
                                                {{__('NO')}}
                                            @endif
                                        </p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Mobile Verify')}}
                                            :</h4></div>
                                    <div class="col-6">
                                        <p class="fs-12 fw-500 lh-19 text-para-text">
                                            @if ($user->phone_verification_status == ACTIVE)
                                                {{__('Yes')}}
                                            @else
                                                {{__('NO')}}
                                            @endif
                                        </p>
                                    </div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Join Date')}} :</h4>
                                    </div>
                                    <div class="col-6"><p class="fs-12 fw-500 lh-19 text-para-text">
                                            {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->created_at ??
                                now())->format('jS F, h:i:s A')}}
                                        </p></div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Last Update')}} :</h4>
                                    </div>
                                    <div class="col-6"><p class="fs-12 fw-500 lh-19 text-para-text">
                                            {{\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->updated_at ??
                                now())->format('jS F, h:i:s A')}}
                                        </p></div>
                                </li>
                                <li class="row flex-wrap">
                                    <div class="col-6"><h4
                                            class="fs-12 fw-500 lh-19 text-textBlack">{{__('Status')}} :</h4>
                                    </div>
                                    <div class="col-6"><p class="fs-12 fw-500 lh-19 text-para-text">
                                            @if ($user->status == ACTIVE)
                                                <span
                                                    class="zBadge zBadge-active">{{__("Active")}}</span>
                                            @else
                                                <span
                                                    class="zBadge zBadge-inactive">{{__("Deactivate")}}</span>
                                            @endif
                                        </p></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-md-7">
                    <div class="table-wrap-one">
                        <div
                            class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                            <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{__('Activity Log')}}</h4>
                        </div>
                        <table class="table zTable zTable-last-item-right" id="activityDataTable"
                               aria-describedby="activityLogDataTable">
                            <thead>
                            <tr>
                                <th>
                                    <div class="min-w-150">{{ __('Action') }}</div>
                                </th>
                                <th>
                                    <div class="min-w-150">{{ __('Source') }}</div>
                                </th>
                                <th>
                                    <div class="min-sm-w-100 text-nowrap">{{ __('IP Address') }}</div>
                                </th>
                                <th>
                                    <div class="min-w-150">{{ __('Location') }}</div>
                                </th>
                                <th>
                                    <div class="min-w-150">{{ __('When') }}</div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <input type="hidden" id="user-activity-route" value="{{ route('super-admin.user.activity', $user->id )}}">
        <!-- Page content area end -->
        @endsection

        @push('script')
            <script src="{{asset('sadmin/custom/js/user.js')}}"></script>
    @endpush
