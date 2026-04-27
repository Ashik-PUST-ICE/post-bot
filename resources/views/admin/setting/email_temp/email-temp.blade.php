@extends('admin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-12">
                <div>
                    <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="customers__area">
                                    <div class="customers__table">
                                        <div class="table-responsive zTable-responsive">
                                            <table class="table zTable zTable-last-item-right" id="">
                                                <thead>
                                                <tr>
                                                    <th>
                                                        <div>{{ __('Title') }}</div>
                                                    </th>
                                                    <th>
                                                        <div>{{ __('Subject') }}</div>
                                                    </th>
                                                    <th>
                                                        <div>{{ __('Action') }}</div>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach ($emailTemplates as $key => $template)
                                                    <tr>
                                                        <input type="hidden" name="category"
                                                               value="{{ $template->category }}">
                                                        <td>{{ $template->title }}</td>
                                                        <td> {{ $template->subject }}</td>
                                                        <td>
                                                            <ul
                                                                class="d-flex align-items-center cg-5 justify-content-end">
                                                                <li>
                                                                    <button
                                                                        class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-white edit"
                                                                        data-id="{{ $template->id }}">
                                                                        <img
                                                                            src="{{ asset('assets/images/icon/edit-black.svg') }}"
                                                                            alt="edit"/>
                                                                    </button>
                                                                </li>
                                                            </ul>
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
                <!-- Page content area end -->
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailConfigureModal" tabindex="-1" aria-labelledby="emailConfigureModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-10">
                <div class="d-flex justify-content-between align-items-center pb-20 mb-20 bd-b-one bd-c-stroke">
                    <h5 class="fs-18 fw-600 lh-18 text-textBlack">{{ __('Edit Email Template') }}</h5>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text"
                            data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('admin.setting.email.template.config.update') }}" method="POST"
                      data-handler="commonResponseForModal">
                    @csrf
                    <input type="hidden" name="id">
                    <div class="row rg-20 pb-20">
                        <div class="col-12">
                            <p class="alert-success p-20 templateFields bd-one bd-c-stroke bd-ra-4"></p>
                        </div>
                        <div class="col-12">
                            <label for="title" class="zForm-label">{{ __('Title') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="title" name="title"
                                   placeholder="{{ __('Title') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="subject" class="zForm-label">{{ __('Subject') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="subject" name="subject"
                                   placeholder="{{ __('Subject') }}" required>
                        </div>
                        <div class="col-12">
                            <label for="body" class="zForm-label">{{ __('Body') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control summernoteOne" name="body" id="body"
                                      placeholder="{{__('Body')}}"></textarea>
                        </div>
                    </div>
                    <div class="bd-t-one bd-c-light-border pt-17 d-flex justify-content-end g-10">
                        <button type="submit"
                                class="py-13 px-20 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-14 fw-600 lh-14">{{ __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <input type="hidden" id="emailTemplateConfigRoute" value="{{ route('admin.setting.email.template.config') }}">
@endsection
@push('script')
    <script src="{{ asset('sadmin/js/email-temp.js') }}"></script>
@endpush
