@extends('sadmin.layouts.app')
@push('title')
{{ $title }}
@endpush
@section('content')
<div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
    <h3 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ $title }}</h3>
    <div class="table-wrap-one">
        <div
            class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
            <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                <div class="search-one flex-grow-1 max-w-207">
                    <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt="" /></button>
                    <input type="text" placeholder="{{ __('Search here...') }}" id="searchByUserPackage" />
                </div>
            </div>
            <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                <button
                    class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                    type="button" id="assignPackage">
                    <i class="fa fa-plus"></i> {{ __('Add Package') }}
                </button>
            </div>
        </div>
        <table id="packageUserDataTableList" class="table zTable zTable-last-item-right">
            <thead>
                <tr>

                    <th class="all">
                        <div class="text-nowrap">{{ __('User Name') }}</div>
                    </th>
                    <th class="all">
                        <div class="text-nowrap">{{ __('Package Name') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('Start Date') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('End Date') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('Payment Status') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('Status') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('Action') }}</div>
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="assignPackageModal" tabindex="-1" aria-labelledby="assignPackageModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Assign Package') }}</h4>
                <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-times"></i></button>
            </div>
            <form class="ajax reset" action="{{ route('super-admin.packages.assign') }}" method="post"
                enctype="multipart/form-data" data-handler="commonResponseForModal">
                @csrf
                <input type="hidden" name="gateway" value="cash">
                <input type="hidden" name="currency" value="{{ currentCurrencyType() }}">
                <div class="row rg-20 pb-25">
                    <div class="">
                        <label class="zForm-label">{{ __('User') }}
                            <span class="text-danger">*</span></label>
                        <select name="user_id" class="sf-select-without-search">
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }}({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="">
                        <label class="zForm-label">{{ __('Package') }}
                            <span class="text-danger">*</span></label>
                        <select name="package_id" class="sf-select-without-search">
                            @foreach ($packages as $package)
                            <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="">
                        <label class="zForm-label">{{ __('Duration Type') }}
                            <span class="text-danger">*</span></label>
                        <select name="duration_type" class="sf-select-without-search">
                            <option value="1">{{ __('Monthly') }}</option>
                            <option value="2">{{ __('Yearly') }}</option>
                        </select>
                    </div>
                </div>

                <button type="submit"
                    class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{
                    __('Assign') }}</button>
            </form>
        </div>
    </div>
</div>

<!-- Edit user subscription row -->
<div class="modal fade" id="editUserPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bd-ra-4 p-20">
            <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit user package') }}</h4>
                <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
                    aria-label="Close"><i class="fa-solid fa-times"></i></button>
            </div>
            <p class="fs-14 text-para-text pb-10"><strong>{{ __('User') }}:</strong> <span id="editUserPackageUser"></span></p>
            <p class="fs-14 text-para-text pb-20"><strong>{{ __('Package') }}:</strong> <span id="editUserPackagePkg"></span></p>
            <form id="editUserPackageForm" class="ajax reset" method="post" enctype="multipart/form-data"
                data-handler="commonResponseWithPageLoad">
                @csrf
                <div class="row rg-20 pb-25">
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Start date') }} <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="start_date" id="editUserPackageStart" class="form-control zForm-control" required>
                    </div>
                    <div class="col-12">
                        <label class="zForm-label">{{ __('End date') }} <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="end_date" id="editUserPackageEnd" class="form-control zForm-control" required>
                    </div>
                    <div class="col-12">
                        <label class="zForm-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                        <select name="status" id="editUserPackageStatus" class="sf-select-without-search form-control zForm-control">
                            <option value="{{ ACTIVE }}">{{ __('Active') }}</option>
                            <option value="{{ DEACTIVATE }}">{{ __('Inactive') }}</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Update') }}</button>
            </form>
        </div>
    </div>
</div>

<input type="hidden" id="packagesUserRoute" value="{{ route('super-admin.packages.user') }}">
<input type="hidden" id="userPackageInfoRoute" value="{{ route('super-admin.packages.user.info') }}">
<input type="hidden" id="userPackageUpdateBaseUrl" value="{{ url('sadmin/packages/user-package-update') }}">
@endsection

@push('script')
<script src="{{ asset('sadmin/custom/js/package.js') }}"></script>
@endpush
