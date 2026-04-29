@extends('sadmin.layouts.app')
@section('content')
    @push('title')
        {{ $title }}
    @endpush
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
        <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{$title}}</h4>

        <div class="table-wrap-one table-wrap-two">
            <div
                class="table-wrapTop d-flex flex-column-reverse flex-lg-row justify-content-center justify-content-lg-between align-items-center align-items-lg-end">
                <ul class="nav nav-tabs zTab-reset zTab-one flex-wrap" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active orderStatusTab" data-bs-toggle="tab" data-bs-target="#allTabPane"
                                type="button" data-status="All" role="tab" aria-controls="allTabPane"
                                aria-selected="true">{{
                    __('All') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-bs-toggle="tab" data-bs-target="#paidTabPane"
                                type="button"
                                role="tab" data-status="Paid" aria-controls="paidTabPane" aria-selected="false"
                                tabindex="-1">{{
                    __('Paid') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-bs-toggle="tab" data-status="Pending"
                                data-bs-target="#pendingTabPane" type="button" role="tab" aria-controls="pendingTabPane"
                                aria-selected="false" tabindex="-1">{{ __('Pending') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-bs-toggle="tab" data-bs-target="#bankTabPane"
                                type="button"
                                role="tab" aria-controls="bankTabPane" data-status="Bank" aria-selected="false"
                                tabindex="-1">{{
                    __('Bank Pending') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link orderStatusTab" data-bs-toggle="tab" data-bs-target="#cancelTabPane"
                                type="button" role="tab" aria-controls="cancelTabPane" data-status="Cancelled"
                                aria-selected="false"
                                tabindex="-1">{{ __('Cancelled') }}
                        </button>
                    </li>
                </ul>
            </div>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="allTabPane" role="tabpanel" aria-labelledby="all-tab"
                     tabindex="0">
                    <table class="table zTable zTable-last-item-right" id="orderDataTableAll"
                           aria-describedby="orderDataTableall">
                        <thead>
                        <tr>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-w-150">{{ __('SL') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Package') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Amount') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Status') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="paidTabPane" role="tabpanel" aria-labelledby="paid-tab" tabindex="0">
                    <table class="table zTable zTable-last-item-right" id="orderDataTablePaid"
                           aria-describedby="orderDataTablepaid">
                        <thead>
                        <tr>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-w-150">{{ __('SL') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Package') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Amount') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Status') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="pendingTabPane" role="tabpanel" aria-labelledby="pending-tab"
                     tabindex="0">
                    <table class="table zTable zTable-last-item-right" id="orderDataTablePending"
                           aria-describedby="orderDataTablepending">
                        <thead>
                        <tr>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-w-150">{{ __('SL') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Package') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Amount') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Status') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="cancelTabPane" role="tabpanel" aria-labelledby="cancel-tab" tabindex="0">
                    <table class="table zTable zTable-last-item-right" id="orderDataTableCancelled"
                           aria-describedby="orderDataTablecancelled">
                        <thead>
                        <tr>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-w-150">{{ __('SL') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Package') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Amount') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Status') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="tab-pane fade" id="bankTabPane" role="tabpanel" aria-labelledby="bank-tab" tabindex="0">
                    <table class="table zTable zTable-last-item-right" id="orderDataTableBank"
                           aria-describedby="orderDataTablebank">
                        <thead>
                        <tr>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-w-150">{{ __('SL') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Package') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Amount') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Status') }}</div>
                            </th>
                            <th scope="col" class="sorting_disabled" rowspan="1" colspan="1">
                                <div class="min-sm-w-100">{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payStatusChangeModal" tabindex="-1" aria-labelledby="payStatusChangeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Payment Status Change') }}</h4>
                    <button type="button"
                            class="border-0 p-0 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax reset"
                      action="{{ route('super-admin.subscriptions.order.payment.status.change') }}"
                      method="post" data-handler="commonResponseForModal">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="row rg-20 pb-25">
                        <div class="">
                            <label class="zForm-label" for="rtl">{{ __('Status') }}</label>
                            <select name="payment_status" class="sf-select-without-search">
                                <option value="0">{{ __('Pending') }}</option>
                                <option value="1">{{ __('Paid') }}</option>
                                <option value="2">{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit"
                            class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{
                        __('Submit') }}</button>

                </form>
            </div>
        </div>
    </div>


    <!-- order show Modal section start -->
    <div class="modal fade" id="edit-modal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-20">

            </div>
        </div>
    </div>
    <!-- order show Modal section end -->

    <input type="hidden" id="ordersGetInfoRoute" value="{{ route('super-admin.subscriptions.orders.get.info') }}">

@endsection

@push('script')
    <script src="{{ asset('sadmin/custom/js/orders.js') }}"></script>
@endpush
