@extends('frontend.layouts.app')
@push('title')
{{ __(@$pageTitle) }}
@endpush
@section('content')
    <section class="py-sm-50 py-30 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center pb-55">
                        <h4 class="lh-sm-57 lh-44 landing-section-title landing-section-title-1 text-ld-black">{{ $pageTitle }}</h4>
                    </div>
                    <div class=" pb-55">
                        {!! getOption('t_and_c_'.session()->get('local')) !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if (isset($section['demo_ection']) && $section['demo_ection']->status == STATUS_ACTIVE)
        <section class="landing-demo-section">
            <div class="container">
                <div class="landing-demo-content-wrap">
                    <div class="landing-demo-content"
                         data-background="{{ getFileUrl($section['demo_ection']->banner_image) }}">
                        <h4 class="title"><span class="d-md-block">{{ __($section['demo_ection']->title) }}</span>
                        </h4>
                        <a href="{{ route('login') }}" class="link">{{__('View Demo')}}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
