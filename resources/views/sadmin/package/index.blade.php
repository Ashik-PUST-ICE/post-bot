@extends('sadmin.layouts.app')
@section('content')
    @push('title')
        {{ $title }}
    @endpush
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-40 p-15">
        <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>
        <div class="table-wrap-one">
            <div class="table-wrapTop d-flex align-items-center justify-content-center justify-content-md-between flex-wrap g-10 pb-18">
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <div class="search-one flex-grow-1 max-w-207">
                        <button class="icon"><img src="{{ asset('assets/images/icon/search.svg') }}" alt=""/></button>
                        <input type="text" placeholder="{{ __('Search here...') }}" id="searchByPackage"/>
                    </div>
                </div>
                <div class="d-flex justify-content-center justify-content-sm-start g-10 flex-wrap">
                    <button
                        class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white"
                        type="button" id="add">
                        <i class="fa fa-plus"></i> {{ __('Add Package') }}
                    </button>
                </div>
            </div>
            <table class="table zTable zTable-last-item-right" id="packageDataTable">
                <thead>
                <tr>
                    <th>
                        <div>{{ __('SL') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Name') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Icon') }}</div>
                    </th>
                    <th>
                        <div class="text-nowrap">{{ __('Monthly Price') }}</div>
                    </th>
                    <th>
                        <div class="text-nowrap">{{ __('Yearly Price') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('Status') }}</div>
                    </th>
                    <th class="desktop">
                        <div class="text-nowrap">{{ __('Action') }}</div>
                    </th>
                    <th>
                        <div>{{ __('Action') }}</div>
                    </th>
                </tr>
                </thead>
            </table>
        </div>
    </div>

    {{-- modal --}}
    <div class="modal fade" id="addModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                    <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                        <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Add Package') }}</h4>
                        <button type="button"
                                class="border-0 p-0 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>

                    <form class="ajax reset" action="{{ route('super-admin.packages.store') }}" method="post"
                          data-handler="commonResponseForModal" enctype="multipart/form-data">
                        @csrf

                        <div class="row rg-20 pb-20">
                            <div>
                                <label for="name" class="zForm-label">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="{{ __('Name') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div>
                                <label for="icon" class="zForm-label">{{ __('Icon') }}</label>
                                <div class="upload-img-box">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/camera.svg') }}" alt=""/></div>
                                    <img src="{{ asset('assets/images/no-image.jpg') }}"/>
                                    <input type="file" name="icon" id="icon" accept="image/*" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <div>
                                <label for="page_limit" class="zForm-label">{{ __('Page Limit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="page_limit" id="page_limit"
                                       placeholder="{{ __('Page Limit') }}" class="form-control zForm-control mb-8">
                            </div>
                            <div>
                                <label for="message_limit" class="zForm-label">{{ __('Message Limit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="message_limit" id="message_limit"
                                       placeholder="{{ __('Message Limit') }}" class="form-control zForm-control mb-8">
                            </div>
                        </div>

                        <div class="row pb-20">
                            <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center g-10">
                                <label class="zForm-label mb-0">{{ __('Other Fields') }}</label>
                                <button type="button"
                                        class="bg-main-color text-white border-0 bd-ra-8 h-30 p-0 w-30 addOtherField">
                                    <i class="fa fa-plus"></i></button>
                            </div>
                            <div class="otherFields d-flex flex-column g-20">
                            </div>
                        </div>
                        <div class="row rg-20 pb-25">
                            <div class="">
                                <label for="monthly_price" class="zForm-label">{{ __('Monthly Price') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="monthly_price" id="monthly_price"
                                       placeholder="{{ __('Monthly Price') }}" class="form-control zForm-control">
                            </div>
                            <div class="">
                                <label for="yearly_price" class="zForm-label">{{ __('Yearly Price') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="yearly_price" id="yearly_price"
                                       placeholder="{{ __('Yearly Price') }}" class="form-control zForm-control">
                            </div>
                            <div class="d-flex flex-wrap g-10">
                                <div class="">
                                    <div class="d-flex form-check ps-0">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input mt-0" value="1" name="status"
                                                   type="checkbox" id="status">
                                        </div>
                                        <label class="form-check-label ps-3 d-flex" for="status">
                                            {{ __('Status') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex form-check ps-0">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input mt-0" value="1" name="is_default"
                                                   type="checkbox" id="is_default">
                                        </div>
                                        <label class="form-check-label ps-3 d-flex" for="is_default">
                                            {{ __('Is Popular') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex form-check ps-0">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input mt-0" value="1" name="is_trail"
                                                   type="checkbox" id="is_trail">
                                        </div>
                                        <label class="form-check-label ps-3 d-flex" for="is_trail">
                                            {{ __('Is Trail') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Stripe sync --}}
                            <div class="bd-t-one bd-c-stroke pt-15 mt-5">
                                <div class="d-flex align-items-center g-10 mb-8">
                                    <img src="{{ asset('assets/images/icon/stripe.svg') }}" alt="stripe"
                                         onerror="this.style.display='none'"
                                         class="h-18">
                                    <span class="fs-13 fw-600 text-textBlack">{{ __('Stripe Integration') }}</span>
                                </div>
                                <div class="d-flex form-check ps-0">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="sync_stripe"
                                               type="checkbox" id="add_sync_stripe">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex fs-13 text-para-text" for="add_sync_stripe">
                                        {{ __('Create product & prices on Stripe') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Submit') }}</button>
                    </form>
            </div>
        </div>
    </div>

    {{-- edit modal --}}
    <div class="modal fade" id="editModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 bd-ra-4 p-20">
                    <div class="d-flex justify-content-between align-items-center bd-b-one bd-c-light-border pb-20 mb-20">
                        <h4 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit Package') }}</h4>
                        <button type="button"
                                class="border-0 p-0 bg-transparent text-para-text"
                                data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                    </div>

                    <form class="ajax reset" action="{{ route('super-admin.packages.store') }}" method="post"
                          data-handler="commonResponseForModal" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id">

                        <div class="row rg-20 pb-20">
                            <div>
                                <label for="name" class="zForm-label">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" placeholder="{{ __('Name') }}"
                                       class="form-control zForm-control">
                            </div>
                            <div>
                                <label for="icon" class="zForm-label">{{ __('Icon') }}</label>
                                <div class="upload-img-box">
                                    <div class="icon"><img src="{{ asset('assets/images/icon/camera.svg') }}" alt=""/></div>
                                    <img src="{{ asset('assets/images/no-image.jpg') }}" class="icon-preview"/>
                                    <input type="file" name="icon" id="icon" accept="image/*" onchange="previewFile(this)"/>
                                </div>
                            </div>
                            <div>
                                <label for="page_limit" class="zForm-label">{{ __('Page Limit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="page_limit" id="page_limit"
                                       placeholder="{{ __('Page Limit') }}" class="form-control zForm-control mb-8">
                            </div>
                            <div>
                                <label for="message_limit" class="zForm-label">{{ __('Message Limit') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="message_limit" id="message_limit"
                                       placeholder="{{ __('Message Limit') }}" class="form-control zForm-control mb-8">
                            </div>
                        </div>

                        <div class="row pb-20">
                            <div class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center g-10">
                                <label class="zForm-label mb-0">{{ __('Other Fields') }}</label>
                                <button type="button"
                                        class="bg-main-color text-white border-0 bd-ra-8 h-30 p-0 w-30 addOtherField"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                            <div class="otherFields d-flex flex-column g-20">
                            </div>
                        </div>
                        <div class="row rg-20 pb-25">
                            <div class="">
                                <label for="monthly_price" class="zForm-label">{{ __('Monthly Price') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="monthly_price" id="monthly_price"
                                       placeholder="{{ __('Monthly Price') }}" class="form-control zForm-control">
                            </div>
                            <div class="">
                                <label for="yearly_price" class="zForm-label">{{ __('Yearly Price') }} <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="yearly_price" id="yearly_price"
                                       placeholder="{{ __('Yearly Price') }}" class="form-control zForm-control">
                            </div>
                            <div class="d-flex flex-wrap g-10">
                                <div class="">
                                    <div class="d-flex form-check ps-0">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input mt-0 status" value="1" name="status"
                                                   type="checkbox" id="status">
                                        </div>
                                        <label class="form-check-label ps-3 d-flex" for="status">
                                            {{ __('Status') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex form-check ps-0">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input mt-0" value="1" name="is_default"
                                                   type="checkbox" id="is_default">
                                        </div>
                                        <label class="form-check-label ps-3 d-flex" for="is_default">
                                            {{ __('Is Popular') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="">
                                    <div class="d-flex form-check ps-0">
                                        <div class="zCheck form-check form-switch">
                                            <input class="form-check-input mt-0" value="1" name="is_trail"
                                                   type="checkbox" id="is_trail">
                                        </div>
                                        <label class="form-check-label ps-3 d-flex" for="is_trail">
                                            {{ __('Is Trail') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Stripe sync --}}
                            <div class="bd-t-one bd-c-stroke pt-15 mt-5">
                                <div class="d-flex align-items-center g-10 mb-8">
                                    <img src="{{ asset('assets/images/icon/stripe.svg') }}" alt="stripe"
                                         onerror="this.style.display='none'"
                                         class="h-18">
                                    <span class="fs-13 fw-600 text-textBlack">{{ __('Stripe Integration') }}</span>
                                </div>
                                <div class="d-flex form-check ps-0 mb-10">
                                    <div class="zCheck form-check form-switch">
                                        <input class="form-check-input mt-0" value="1" name="sync_stripe"
                                               type="checkbox" id="edit_sync_stripe">
                                    </div>
                                    <label class="form-check-label ps-3 d-flex fs-13 text-para-text" for="edit_sync_stripe">
                                        {{ __('Sync / re-sync with Stripe') }}
                                    </label>
                                </div>
                                <div class="stripe-ids-panel bd-one bd-c-stroke bd-ra-4 p-10" style="display:none;">
                                    <p class="fs-12 text-para-text mb-4">
                                        <span class="fw-600">{{ __('Product ID') }}:</span>
                                        <span class="stripe-product-id text-textBlack">—</span>
                                    </p>
                                    <p class="fs-12 text-para-text mb-4">
                                        <span class="fw-600">{{ __('Monthly Price ID') }}:</span>
                                        <span class="stripe-monthly-id text-textBlack">—</span>
                                    </p>
                                    <p class="fs-12 text-para-text mb-0">
                                        <span class="fw-600">{{ __('Yearly Price ID') }}:</span>
                                        <span class="stripe-yearly-id text-textBlack">—</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Submit') }}</button>
                    </form>
            </div>
        </div>
    </div>


    <input type="hidden" id="packageIndexRoute" value="{{ route('super-admin.packages.index') }}">
    <input type="hidden" id="packageInfoRoute" value="{{ route('super-admin.packages.get.info') }}">
@endsection
@push('script')
    <script src="{{ asset('sadmin/custom/js/package.js') }}"></script>
@endpush
