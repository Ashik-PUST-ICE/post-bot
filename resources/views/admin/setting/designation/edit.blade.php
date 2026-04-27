@extends('admin.layouts.app')
@push('title')
    {{ $pageTitle }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="row rg-20">
            <div class="col-xl-3">
                <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                    @include('admin.setting.sidebar')
                </div>
            </div>
            <div class="col-xl-9">
                <form class="ajax" action="{{ route('admin.setting.designation.store') }}" method="POST"
                    data-handler="commonResponse">
                    @csrf
                    <input type="hidden" name="id" value="{{ encrypt($designation->id) }}">
                    <div class="p-sm-25 p-15 bd-one bd-c-stroke bd-ra-10 bg-white mb-25">
                        <div class="row rg-20">
                            <div class="col-12">
                                <label for="addTitle" class="zForm-label">{{ __('Title') }}</label>
                                <input type="text" name="title" value="{{ $designation->title }}"
                                    class="form-control zForm-control" id="addTitle" placeholder="{{ __('Title') }}" />
                            </div>
                            <div class="col-md-12">
                                <label for="addStatus" class="zForm-label">{{ __('Status') }}</label>
                                <select class="sf-select-two" id="addStatus" name="status">
                                    <option value="1" {{ $designation->status == 1 ? 'selected' : '' }}>
                                        {{ __('Active') }}
                                    </option>
                                    <option value="0" {{ $designation->status != 1 ? 'selected' : '' }}>
                                        {{ __('Deactivate') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex g-12 flex-wrap">
                        <button
                            class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{ __('Update') }}</button>
                        <a href="{{ route('admin.setting.designation.index') }}"
                            class="py-10 px-26 bg-white bd-one bd-c-para-text bd-ra-8 fs-15 fw-600 lh-25 text-para-text">{{ __('Cancel') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
