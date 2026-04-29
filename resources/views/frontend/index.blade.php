@extends('frontend.layouts.app')
@push('title')
{{ __(@$pageTitle) }}
@endpush
@section('content')


@if (isset($section['hero_area']) && $section['hero_area']->status == STATUS_ACTIVE)
<!-- Start Banner -->
<section class="landing-banner-wrap position-relative z-1">
    <div class="container">
        <div class="landing-hero-content">
            <h4 class="title">{{ __($section['hero_area']->title) }}</h4>
            <p class="info">{{ __($section['hero_area']->description) }}</p>
            <a href="{{ route('login') }}" class="btnLink">{{ __('Request a Demo') }}</a>
        </div>
        <!--  -->
        <div class="landing-hero-img">
            <div class="img"><img src="{{ getFileUrl($section['hero_area']->banner_image) }}"
                    alt="{{ __(getOption('app_name')) }}" /></div>
        </div>
    </div>
</section>
<!-- End Banner -->
@endif

@if (isset($section['features']) && $section['features']->status == STATUS_ACTIVE)
<!-- Start Features List -->
<section class="py-sm-150 py-30 landing-feature-wrap" id="features">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['features']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                        <span class="d-sm-block">{{ __($section['features']->title) }}</span>
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row rg-30 justify-content-center">
            @foreach ($features as $feature)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="features-list-item">
                    <div class="icon"><img src="{{ getFileUrl($feature->image) }}"
                            alt="{{ __(getOption('app_name')) }}" />
                    </div>
                    <div class="title"><span class="d-md-block">{{ __($feature->title) }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Features List -->
@endif

@if (isset($section['services']) && $section['services']->status == STATUS_ACTIVE)
<!-- Start Features Block -->
<section class="bg-white py-sm-150 py-30 position-relative z-1" id="goal-setup">
    <div class="container">
        <div class="features-block">
            @foreach ($services as $service)
            <div class="features-block-item">
                <div class="row align-items-center rg-20">
                    <div class="col-lg-6">
                        <div class="item-content">
                            <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                                {{ __($service->name) }}</p>
                            <h4 class="landing-section-title text-ld-black">{{ __($service->title) }}</h4>
                            <p class="fs-18 fw-600 lh-26 text-para-text max-w-400 pb-20">{{ __($service->sub_title) }}
                            </p>
                            <ul class="lists">
                                @foreach (json_decode($service->others) as $other)
                                <li>
                                    <div class="icon"><img
                                            src="{{ asset('assets/images/icon/features-check-icon.svg') }}" alt="" />
                                    </div>
                                    <div class="content">
                                        <p class="info">{{ __($other) }}</p>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image"><img src="{{ getFileUrl($service->image) }}"
                                alt="{{ __(getOption('app_name')) }}" /></div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Features Block -->
@endif


@if (isset($section['core_features']) && $section['core_features']->status == STATUS_ACTIVE)
<!-- Start Core Features -->
<section class="py-sm-150 py-30 landing-coreFeature-wrap position-relative z-2" id="core-features"
    data-background="assets/images/ld-bg-dot.svg">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['core_features']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                        {{ __($section['core_features']->title) }}
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="d-flex align-items-start flex-column flex-lg-row g-20">
            <div class="nav flex-row flex-lg-column flex-wrap flex-lg-nowrap justify-content-center nav-pills zTab-reset zTab-vertical-one g-20"
                id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @foreach ($coreFeatures as $key => $coreFeature)
                <button class="nav-link {{ $key == 0 ? 'active' : '' }}" id="v-pills-Dashboard-tab-{{ $key }}"
                    data-bs-toggle="pill" data-bs-target="#v-pills-Dashboard-{{ $key }}" type="button" role="tab"
                    aria-controls="v-pills-Dashboard-{{ $key }}" aria-selected="true">{{$key+1}}. {{
                    __($coreFeature->title)
                    }}</button>
                @endforeach
            </div>
            <div class="tab-content" id="v-pills-tabContent">
                @foreach ($coreFeatures as $key => $coreFeature)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="v-pills-Dashboard-{{ $key }}"
                    role="tabpanel" aria-labelledby="v-pills-Dashboard-tab-{{ $key }}" tabindex="0">
                    <div class="landing-coreFeatures-tabContent">
                        <div class="img">
                            <img src="{{ getFileUrl($coreFeature->image) }}" alt="{{ __($coreFeature->title) }}" />
                        </div>
                        <div class="content">
                            <h4 class="title">{{ __($coreFeature->title) }}</h4>
                            <p class="info">{{ __($coreFeature->description) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- End Core Features -->
@endif

@if (isset($section['choose_us']) && $section['choose_us']->status == STATUS_ACTIVE)
<!-- Start Why Choose Us -->
<section class="py-sm-150 py-30 landing-whyChooseUs-section"
    data-background="{{asset('assets/images/ld-why-choose-us.png')}}" id="why-us">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['choose_us']->page_title) }}</p>
                    <h4 class="landing-section-title text-ld-black">{{ __($section['choose_us']->title) }}</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="row rg-20 justify-content-center">
            @foreach ($chooseUs as $choose)
            <div class="col-lg-4 col-sm-6">
                <div class="landing-whyChooseUs-item">
                    <div class="icon">
                        <img src="{{ getFileUrl($choose->image) }}" alt="img" />
                    </div>
                    <div class="title-wrap">
                        <h4 class="title"><span class="d-md-block">{{ __($choose->title) }}</span></h4>
                    </div>
                    <p class="info">{{ __($choose->description) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End Why Choose Us -->
@endif

@if (isset($section['pricing']) && $section['pricing']->status == STATUS_ACTIVE)
<!-- Start Pricing -->
<section class="py-sm-150 py-30 landing-pricing-wrap position-relative z-1 bg-white" id="price">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="text-center pb-25">
                    <p class="landing-section-subtitle bd-one bd-c-main-color">{{ __($section['pricing']->page_title) }}
                    </p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">{{ __($section['pricing']->title) }}
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="d-flex justify-content-center align-items-center g-20 pb-55">
            <ul class="nav nav-tabs flex-column flex-sm-row zTab-reset zTab-four" id="pricePlanTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="billingMonthly-tab" data-bs-toggle="tab"
                        data-bs-target="#billingMonthly-tab-pane" type="button" role="tab"
                        aria-controls="billingMonthly-tab-pane" aria-selected="true">{{__('Monthly')}}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="billingYearly-tab" data-bs-toggle="tab"
                        data-bs-target="#billingYearly-tab-pane" type="button" role="tab"
                        aria-controls="billingYearly-tab-pane" aria-selected="false"
                        tabindex="-1">{{__('Yearly')}}</button>
                </li>
            </ul>
        </div>
        <!--  -->
        <div class="row rg-20">
            @foreach ($packages as $key => $package)
            <div class="col-xl-4 col-md-6">
                <div class="price-plan-one {{ $key == 1 ? 'price-plan-standard' : ($key >= 2 ? 'price-plan-enterprise' : '') }}">
                    <div class="price-head">
                        <h4 class="title">{{ $package->name }}</h4>
                        <h4 class="plan-price zPrice-plan-monthly">{{ showPrice($package->monthly_price) }}</h4>
                        <h4 class="plan-price zPrice-plan-yearly d-none">{{ showPrice($package->yearly_price) }}</h4>
                    </div>
                    <div class="price-body">
                        <ul class="zList-pb-10 mb-50">
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                        <img src="{{asset('assets/images/icon/features-check-icon.svg')}}" alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        @if ($package->page_limit == -1)
                                            {{ __('Unlimited Pages') }}
                                        @else
                                            {{ __('Up to :n Pages', ['n' => $package->page_limit]) }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                        <img src="{{asset('assets/images/icon/features-check-icon.svg')}}" alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-22 text-para-text">
                                        @if ($package->message_limit == -1)
                                            {{ __('Unlimited Messages/Month') }}
                                        @else
                                            {{ number_format($package->message_limit) }} {{ __('Messages/Month') }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            @foreach (json_decode($package->others) ?? [] as $other)
                            <li>
                                <div class="d-flex align-items-start g-10">
                                    <div class="flex-shrink-0 d-flex justify-content-center align-items-center w-15 h-15 rounded-circle bg-main-color mt-4">
                                        <img src="{{asset('assets/images/icon/features-check-icon.svg')}}" alt="{{ $package->name }}" />
                                    </div>
                                    <p class="fs-18 fw-400 lh-26 text-para-text">{{ __($other) }}</p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}" class="btn link" title="{{ __('Get Started') }}">{{ __('Get Started') }}</a>
                    </div>
                </div>
            </div>
            @endforeach

    </div>
</section>
<!-- End Pricing -->
@push('script')
<script>
// Monthly / Yearly price toggle
$('#billingMonthly-tab').on('shown.bs.tab', function () {
    $('.zPrice-plan-yearly').addClass('d-none');
    $('.zPrice-plan-monthly').removeClass('d-none');
});
$('#billingYearly-tab').on('shown.bs.tab', function () {
    $('.zPrice-plan-monthly').addClass('d-none');
    $('.zPrice-plan-yearly').removeClass('d-none');
});
</script>
@endpush
@endif

@if (isset($section['testimonials_area']) && $section['testimonials_area']->status == STATUS_ACTIVE)
<!-- Start Testimonial -->
<section class="py-sm-150 py-30 bg-ld-feature-bg position-relative z-1 overflow-hidden"
    data-background="{{asset('assets/images/ld-bg-dot.svg')}}">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['testimonials_area']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-white">{{ __($section['testimonials_area']->title)
                        }}</h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="landing-testimonial-wrap">
            <div class="swiper ldTestiItems">
                <div class="swiper-wrapper">
                    @foreach ($testimonials as $testimonial)
                    <div class="swiper-slide">
                        <div class="landing-testimonial-item">
                            <div class="left">
                                <div class="img"><img src="{{ getFileUrl($testimonial->image) }}"
                                        alt="{{ getOption('app_name') }}" /></div>
                            </div>
                            <div class="right">
                                <p class="text">"{{ $testimonial->comment }}”</p>
                                <div
                                    class="d-flex flex-column flex-sm-row justify-content-between align-items-center g-10">
                                    <div class="content">
                                        <h4 class="name">{{ $testimonial->name }}</h4>
                                        <p class="userUrl">{{"@"}}{{$testimonial->designation }}</p>
                                    </div>
                                    <div class="rating-date text-center text-sm-end">
                                        <ul class="ld-testi-rating">
                                            {!! reviewStar($testimonial->rating) !!}
                                        </ul>
                                        <p class="fs-18 fw-400 lh-27 text-white-80">{{
                                            $testimonial->created_at?->format('d-m-Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="arrowControl">
                    <div class="swiper-button-next"><i class="fa-solid fa-angle-right"></i></div>
                    <div class="swiper-button-prev"><i class="fa-solid fa-angle-left"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- End Testimonial -->
@endif


<!-- Contact us (landing) -->
<section class="py-sm-150 py-30 bg-white position-relative z-1" id="contact-us">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center pb-40">
                    <p class="landing-section-subtitle bd-one bd-c-main-color">{{ __('Get in touch') }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">{{ __('Contact us') }}</h4>
                    <p class="fs-18 fw-400 lh-26 text-para-text">
                        {{ __('Send us a message and we will get back to you.') }}</p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bd-one bd-c-stroke bd-ra-12 p-sm-40 p-20 bg-white">
                    <form action="{{ route('contact-us.store', [], false) }}" method="post" class="row rg-20">
                        @csrf
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Name') }} <span class="text-danger">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control zForm-control"
                                required maxlength="255">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Email') }} <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="form-control zForm-control" required maxlength="255">
                        </div>
                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Phone') }}</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="form-control zForm-control" maxlength="50">
                        </div>

                        <div class="col-md-6">
                            <label class="zForm-label">{{ __('Subject') }}</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                class="form-control zForm-control" maxlength="255">
                        </div>
                        <div class="col-12">
                            <label class="zForm-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                            <textarea name="message" rows="5" class="form-control zForm-control" required
                                maxlength="10000"
                                placeholder="{{ __('How can we help you?') }}">{{ old('message') }}</textarea>
                        </div>
                        <div class="col-12">
                            <button type="submit"
                                class="py-13 px-30 bd-one bd-ra-4 bd-c-main-color bg-main-color text-white fs-16 fw-600 lh-16">{{ __('Send message') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@if (isset($section['faqs_area']) && $section['faqs_area']->status == STATUS_ACTIVE)
<!-- Start FAQ's -->
<section class="py-sm-150 py-30 landing-faq-wrap" id="faq">
    <div class="container">
        <!--  -->
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="text-center pb-55">
                    <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                        {{ __($section['faqs_area']->page_title) }}</p>
                    <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">
                        {{ __($section['faqs_area']->title) }}
                    </h4>
                </div>
            </div>
        </div>
        <!--  -->
        <div class="accordion zAccordion-reset zAccordion-one" id="accordionExample">
            <div class="row rg-24">
                @foreach ($faqs as $key => $faq)
                <div class="col-lg-6">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{$key}}" aria-controls="collapse{{$key}}">
                                {{$key + 1}}. {{ __($faq->title) }}
                            </button>
                        </h2>
                        <div id="collapse{{$key}}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <p>{{ __($faq->description) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
<!-- End FAQ's -->
@endif

@if (isset($section['demo_ection']) && $section['demo_ection']->status == STATUS_ACTIVE)
<section class="landing-demo-section">
    <div class="container">
        <div class="landing-demo-content-wrap">
            <div class="landing-demo-content" data-background="{{ getFileUrl($section['demo_ection']->banner_image) }}">
                <h4 class="title"><span class="d-md-block">{{ __($section['demo_ection']->title) }}</span>
                </h4>
                <a href="{{ route('login') }}" class="link">{{__('View Demo')}}</a>
            </div>
        </div>
    </div>
</section>
@endif

@endsection