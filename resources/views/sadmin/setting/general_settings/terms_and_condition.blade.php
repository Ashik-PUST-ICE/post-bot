@extends('sadmin.layouts.app')
@push('title')
    {{ $title }}
@endpush
@section('content')
    <div data-aos="fade-up" data-aos-duration="1000" class="p-sm-30 p-15">
        <div class="">
            <h4 class="fs-24 fw-500 lh-34 text-title-black pb-16">{{ __($title) }}</h4>
            <div class="row rg-20">
                <div class="col-xl-3">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
                        @include('sadmin.setting.partials.frontend-sidebar')
                    </div>
                </div>
                <div class="col-xl-9">
                    <div class="bg-white p-sm-25 p-15 bd-one bd-c-stroke bd-ra-8">
{{--                        <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ $title }}</h4>--}}
                        <form class="ajax" action="{{ route('super-admin.setting.application-settings.update') }}"
                              method="POST" enctype="multipart/form-data" data-handler="settingCommonHandler">
                            @csrf

                            <div class="row">
                                <div class="col-lg-12">
                                    @foreach(appLanguages() as $lan)
                                        @php
                                            // Find the privacy policy for the current language
                                            $policy = $t_and_c->firstWhere('option_key', 't_and_c_' . $lan->iso_code);
                                            $policyContent = $policy ? $policy->option_value : '';
                                        @endphp

                                        <div class="col-lg-12">
                                            <div class="primary-form-group">
                                                <h4 class="fs-18 fw-600 lh-22 text-title-black">{{ $title }} {{ $lan->language }}</h4>
                                                <div class="primary-form-group-wrap">
                                                    <textarea class="summernoteOne" name="t_and_c_{{ $lan->iso_code }}" placeholder="Body" style="height: 800px;">{!! $policyContent !!}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="d-flex g-12 flex-wrap mt-25">
                                <button type="submit"
                                        class="py-10 px-26 bg-main-color bd-one bd-c-main-color bd-ra-8 fs-15 fw-600 lh-25 text-white">{{
                                __('Update') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>

    </script>
@endpush
