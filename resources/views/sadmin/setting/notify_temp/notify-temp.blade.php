@extends('sadmin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-18 fw-600 lh-18 text-textBlack pb-16">{{ __($title) }}</h4>
            <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">

                <input type="hidden" value="{{ route('super-admin.setting.notify-template') }}" id="notify-temp-route">

                <div class="table-responsive zTable-responsive">
                    <table class="table zTable zTable-last-item-right" id="">
                        <thead>
                        <tr>
                            <th>
                                <div>{{ __('Title') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Slug') }}</div>
                            </th>
                            <th>
                                <div>{{ __('Action') }}</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($notifyTemplates as $key => $template)
                            <tr>
                                <input type="hidden" name="slug" value="{{ $template->slug }}">

                                <td>{{ $template->title }}</td>
                                <td> {{ $template->slug }}</td>
                                <td>
                                    <ul class="d-flex align-items-center cg-5 justify-content-end">
                                        <li>
                                            <button
                                                class="d-flex justify-content-center align-items-center w-30 h-30 rounded-circle bd-one bd-c-stroke bg-white edit"
                                                data-id="{{ $template->id }}">
                                                <img src="{{ asset('assets/images/icon/edit-black.svg') }}"
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
    <!-- Page content area end -->

    {{-- notify tamp edit model--}}
    <div class="modal fade" id="notifyConfigureModal" tabindex="-1" aria-labelledby="notifyConfigureModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 bd-ra-4 p-10">
                <div
                    class="bd-b-one bd-c-stroke pb-20 mb-20 d-flex align-items-center flex-wrap justify-content-between g-10">
                    <h5 class="fs-18 fw-600 lh-22 text-textBlack">{{ __('Edit Notification Template') }}</h5>
                    <button type="button" class="border-0 p-0 bg-transparent text-para-text" data-bs-dismiss="modal"
                            aria-label="Close"><i class="fa-solid fa-times"></i></button>
                </div>
                <form class="ajax" action="{{ route('super-admin.setting.notify.template.config.update') }}"
                      method="POST"
                      data-handler="commonResponseForModal">
                    @csrf
                    <input type="hidden" name="id">

                    <div class="row rg-20 pb-20">
                        <div class="col-md-12">
                            <p class="alert-success p-20 templateFields">

                            </p>
                        </div>
                        <div class="col-md-12">
                            <label for="title" class="zForm-label">{{ __('Title') }}<span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control zForm-control" id="title" name="title"
                                   placeholder="{{ __('Title') }}" required>
                        </div>
                        <div class="col-md-12">
                            <label for="body" class="zForm-label">{{ __('Body') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control zForm-control summernoteOne" name="body" id="body"
                                      placeholder="Body"></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit"
                                class="py-12 pr-15 pl-10 bd-one bd-c-main-color bg-main-color bd-ra-4 fs-14 fw-500 lh-14 text-white">{{
                        __('Update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <input type="hidden" id="notifyTemplateConfigRoute"
           value="{{ route('super-admin.setting.notify.template.config') }}">
@endsection

@push('script')
    <script src="{{ asset('sadmin/js/notify-temp.js') }}"></script>
@endpush
