@extends('frontend.layouts.app')
@push('title')
    {{ __(@$pageTitle) }}
@endpush
@section('content')

    <!-- Start Hero -->
    <section class="py-md-150 py-sm-50 py-30 bg-ld-section-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="landing-about-content text-center">
                        <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">{{ __('About us') }}</p>
                        <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">{{ __($about->title) }}</h4>
                        <p class="landing-section-info">{{ __($about->description) }}</p>
                        <a href="{{ route('login') }}" class="btnLink">{{ __('Request a Demo') }}</a>
                    </div>
                </div>
            </div>
            <div class="landing-about-img">
                <div class="row rg-20">
                    @foreach (range(1, 4) as $i)
                        <div class="col-md-3 col-6">
                            <div class="about-imgItem about-imgItem-{{ $i }}">
                                <img src="{{ getFileUrl($about->{'image_' . $i}) }}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center pb-55">
                        <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">{{ __('Statistic') }}</p>
                        <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">{{ __('See Your Wealth Grow') }}</h4>
                        <p class="landing-section-info max-w-570 m-auto">{{ __('From our humble beginnings to our current standing, we have remained steadfast in our commitment to excellence, innovation, and integrity. ') }}</p>
                    </div>
                </div>
            </div>
            <div class="row rg-20 justify-content-center">
                @foreach (range(1, 3) as $i)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="landing-statistic-item">
                            <h4 class="title">{{ __($about->{'statistic_title_' . $i}) }}</h4>
                            <p class="info">{{ __($about->{'statistic_description_' . $i}) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End Hero -->


    <!-- Start Mission & Vision -->
    <section class="py-md-150 py-sm-50 py-30 position-relative bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="text-center pb-55">
                        <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">{{ __('KPI Roadmap') }}</p>
                        <h4 class="lh-sm-57 lh-44 landing-section-title text-ld-black">{{ __('Better Marketing SaaS for Business Growth.') }}</h4>
                    </div>
                </div>
            </div>
            <ul class="nav nav-tabs zTab-reset zTab-five" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="ourMission-tab" data-bs-toggle="tab" data-bs-target="#ourMission-tab-pane" type="button" role="tab" aria-controls="ourMission-tab-pane" aria-selected="true">{{ __('Our Mission') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ourVision-tab" data-bs-toggle="tab" data-bs-target="#ourVision-tab-pane" type="button" role="tab" aria-controls="ourVision-tab-pane" aria-selected="false" tabindex="-1">{{ __('Our Vision') }}</button>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="ourMission-tab-pane" role="tabpanel" aria-labelledby="ourMission-tab" tabindex="0">
                    <div class="mv-content-wrap">
                        <div class="row rg-20 align-items-center">
                            <div class="col-lg-6">
                                <div class="mv-content-text">
                                    <h4 class="title">{{ __($about->mission_title) }}</h4>
                                    <p class="info">{{ __($about->mission_description) }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mv-content-img">
                                    <img src="{{ getFileUrl($about->mission_image) }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="ourVision-tab-pane" role="tabpanel" aria-labelledby="ourVision-tab" tabindex="0">
                    <div class="mv-content-wrap">
                        <div class="row rg-20 align-items-center">
                            <div class="col-lg-6">
                                <div class="mv-content-text">
                                    <h4 class="title">{{ __($about->vision_title) }}</h4>
                                    <p class="info">{{ __($about->vision_description) }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mv-content-img">
                                    <img src="{{ getFileUrl($about->vision_image) }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Mission & Vision -->

    <!-- Start Team Members -->
    @if(isset($about->team_members) && is_array($about->team_members) && count($teamMembers = $about->team_members) > 0)
        <section class="py-md-150 py-sm-50 py-30 position-relative z-1 landing-teamMember-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="text-center pb-55">
                        <p class="landing-section-subtitle bg-transparent bd-one bd-c-white text-white">
                            {{ __('Team Member') }}
                        </p>
                        <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                            {{ __($about->team_section_title) }}
                        </h4>
                        <p class="landing-section-info text-white max-w-570 m-auto">
                            {{ __($about->team_section_description) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="row rg-20 justify-content-center">
                @foreach(json_decode($about->team_members, true) as $member)
                    <div class="col-lg-3 col-sm-6">
                        <div class="landing-teamMember">
                            <div class="img">
                                <img src="{{ getFileUrl($member['image']) }}" alt="{{ $member['name'] }}">
                            </div>
                            <div class="content">
                                <h4 class="name">{{ __($member['name']) }}</h4>
                                <p class="degi">{{ __($member['designation']) }}</p>
                                <div class="social-links">
                                    @if(!empty($member['facebook_link']))
                                        <a href="{{ $member['facebook_link'] }}" target="_blank">
                                            <i class="fab fa-facebook"></i>
                                        </a>
                                    @endif
                                    @if(!empty($member['instagram_link']))
                                        <a href="{{ $member['instagram_link'] }}" target="_blank">
                                            <i class="fab fa-instagram"></i>
                                        </a>
                                    @endif
                                    @if(!empty($member['twitter_link']))
                                        <a href="{{ $member['twitter_link'] }}" target="_blank">
                                            <i class="fab fa-twitter"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    <!-- End Team Members -->


    <!-- Start Core Values -->
    <section class="py-md-150 py-sm-50 py-30 position-relative z-1 bg-bg-color">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center pb-55">
                        <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                            {{ __('Core Value') }}
                        </p>
                        <h4 class="max-w-584 m-auto lh-sm-57 lh-44 landing-section-title text-ld-black">
                            {{ __($about->core_value_section_title) }}
                        </h4>
                        <p class="landing-section-info">
                            {{ __($about->core_value_section_description) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Core Values List -->
            <div class="row rg-20 justify-content-center">
                @foreach($about->core_values as $value)
                    <div class="col-lg-4 col-md-6">
                        <div class="landing-coreValue">
                            <div class="wrap">
                                <div class="icon">
                                    <img src="{{ getFileUrl($value['icon']) }}" alt="{{ $value['title'] }}">
                                </div>
                                <h4 class="title">{{ __($value['title']) }}</h4>
                                <p class="info">{{ __($value['description']) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- End Core Values -->

    <!-- Start Information -->
    <section class="bg-white position-relative z-1">
        <div class="row rg-30 align-items-center">
            <div class="col-lg-6">
                <div class="landing-information-left">
                    <div class="pb-24">
                        <p class="landing-section-subtitle">{{__('Information')}}</p>
                        <h4 class="lh-sm-57 lh-44 landing-section-title landing-section-title-1">{{__('Get In Touch For More')}}</h4>
                    </div>
                    <ul class="zList-pb-15">
                        <li>
                            <div class="row align-items-start rg-15">
                                <div class="col-sm-12">
                                    <p class="fs-22 fw-400 lh-21 text-para-text">
                                        <strong>{{__('Tax Number:')}}</strong> {{ getOption('tax_number') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="row align-items-start rg-15">
                                <div class="col-sm-12">
                                    <p class="fs-22 fw-400 lh-21 text-para-text">
                                        <strong>{{__('Phone Number:')}}</strong> {{ getOption('phone_number') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="row align-items-start rg-15">
                                <div class="col-sm-12">
                                    <p class="fs-22 fw-400 lh-21 text-para-text">
                                        <strong>{{__('Support Mail:')}}</strong> {{ getOption('support_email') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="row align-items-start rg-15">
                                <div class="col-sm-12">
                                    <p class="fs-22 fw-400 lh-21 text-para-text">
                                        <strong>{{__('Location:')}}</strong> {{ getOption('app_location') }}
                                    </p>
                                </div>
                            </div>
                        </li>
                    </ul>


                </div>
            </div>
            <div class="col-lg-6">
                <div class="landing-information-right">
                    {!! getOption('map_link') !!}
                </div>
            </div>
        </div>
    </section>
    <!-- End Information -->

    @if (isset($section['testimonials_area']) && $section['testimonials_area']->status == STATUS_ACTIVE)
        <!-- Start Testimonial -->
        <section class="py-md-150 py-sm-50 py-30 bg-ld-feature-bg position-relative z-1 overflow-hidden"
                 data-background="{{asset('assets/images/ld-bg-dot.svg')}}">
            <div class="container">
                <!--  -->
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="text-center pb-55">
                            <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                                {{ __($section['testimonials_area']->page_title) }}
                            </p>
                            <h4 class="lh-sm-57 lh-44 landing-section-title text-white">
                                {{ __($section['testimonials_area']->title) }}
                            </h4>
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
                                                                  alt="{{ getOption('app_name') }}"/></div>
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

    @if (isset($section['faqs_area']) && $section['faqs_area']->status == STATUS_ACTIVE)
        <!-- Start FAQ's -->
        <section class="py-md-150 py-sm-50 py-30 landing-faq-wrap" id="faq">
            <div class="container">
                <!--  -->
                <div class="row justify-content-center">
                    <div class="col-lg-7">
                        <div class="text-center pb-55">
                            <p class="landing-section-subtitle bg-transparent bd-one bd-c-main-color">
                                {{ __($section['faqs_area']->page_title) }}
                            </p>
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
                                        <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{$key}}" aria-controls="collapse{{$key}}">
                                            {{$key + 1}}. {{$faq->title}}
                                        </button>
                                    </h2>
                                    <div id="collapse{{$key}}" class="accordion-collapse collapse"
                                         data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <p>{{ $faq->description }}</p>
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
                    <div class="landing-demo-content"
                         data-background="{{ getFileUrl($section['demo_ection']->banner_image) }}">
                        <h4 class="title"><span class="d-md-block">{{$section['demo_ection']->title}}</span>
                        </h4>
                        <a href="{{ route('login') }}" class="link">{{__('View Demo')}}</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
