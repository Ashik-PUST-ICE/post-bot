<!-- Start Footer -->
<section class="landing-footer">
    <div class="container">
        <!-- Top -->
        <div class="landing-footer-top">
            <div class="container">
                <div class="row rg-20">
                    <div class="col-lg-4">
                        <div class="max-w-366 me-auto me-lg-0 ms-auto text-center text-lg-start">
                            <div class="pb-22" style="max-width: 130px;"><img src="{{ getSettingImage('app_logo_white') }}"
                                    alt="{{ getOption('app_name') }}" style="max-height: 78px; width: auto; object-fit: contain;" /></div>
                            <p class="pb-32 fs-18 fw-400 lh-28 text-white">{{ getOption('app_footer_text') }}</p>
                            <ul class="d-flex justify-content-center justify-content-lg-start align-items-center g-12 landing-footer-social">
                                @if (getOption('social_media_facebook'))
                                <li>
                                    <a target="__blank" href="{{ getOption('social_media_facebook') }}"
                                        class="w-40 h-40 rounded-circle bd-one bd-c-white text-white d-flex justify-content-center align-items-center text-title-black"><i
                                            class="fa-brands fa-facebook-f"></i></a>
                                </li>
                                @endif

                                @if (getOption('social_media_twitter'))
                                <li>
                                    <a target="__blank" href="{{ getOption('social_media_twitter') }}"
                                        class="w-40 h-40 rounded-circle bd-one bd-c-white text-white d-flex justify-content-center align-items-center text-title-black"><i
                                            class="fa-brands fa-twitter"></i></a>
                                </li>
                                @endif

                                @if (getOption('social_media_linkedin'))
                                <li>
                                    <a target="__blank" href="{{ getOption('social_media_linkedin') }}"
                                        class="w-40 h-40 rounded-circle bd-one bd-c-white text-white d-flex justify-content-center align-items-center text-title-black"><i
                                            class="fa-brands fa-linkedin-in"></i></a>
                                </li>
                                @endif

                                @if (getOption('social_media_skype'))
                                <li>
                                    <a target="__blank" href="{{ getOption('social_media_skype') }}"
                                        class="w-40 h-40 rounded-circle bd-one bd-c-white text-white d-flex justify-content-center align-items-center text-title-black"><i
                                            class="fa-brands fa-skype"></i>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-4">
                        <div class="pl-xl-70">
                            <h4 class="pb-37 fs-24 fw-500 lh-30 text-white">{{__('Section')}}</h4>
                            <ul class="zList-pb-21">
                                <li><a href="#"
                                        class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Home')}}</a>
                                </li>
                                <li><a href="#features"
                                        class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Features')}}</a>
                                </li>
                                <li><a href="#core-features"
                                        class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Core
                                        Features')}}</a></li>
                                <li><a href="#price"
                                        class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Pricing')}}</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-5">
                        <div class="pl-xl-70">
                            <h4 class="pb-37 fs-24 fw-500 lh-30 text-white">{{__('Use Cases')}}</h4>
                            <ul class="zList-pb-21">
                                <li><a href="#goal-setup" class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Goal')}}</a></li>
                                <li><a href="#faq" class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Faqs')}}</a></li>
                                <li><a href="#why-us" class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Why Choose Us')}}</a></li>
                                <li><a href="{{ route('login') }}" class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{__('Request a Demo')}}</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-4 col-sm-4">
                        <div class="max-w-131 ms-lg-auto">
                            <h4 class="pb-37 fs-24 fw-500 lh-30 text-white">{{ __('Pages') }}</h4>
                            <ul class="zList-pb-21">
                                <li>
                                    <a href="{{ route('privacy_policy') }}"
                                       class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{ __('Privacy Policy') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('return_policy') }}"
                                       class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{ __('Return Policy') }}</a>
                                </li>
                                <li>
                                    <a href="{{ route('terms_and_condition') }}"
                                       class="fs-18 fw-400 lh-27 text-white hover-color-main-color">{{ __('Terms and Condition') }}</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Bottom -->
        <div class="landing-footer-bottom">
            <p class="fs-16 fw-600 lh-22 text-white">{{ __(getOption('app_copyright')) }}
                @if (getOption('develop_by'))
                <a href="{{ route('frontend') }}"
                    class="text-main-color text-decoration-underline">{{__(getOption('develop_by'))}}</a>
                @endif
            </p>
        </div>
    </div>
</section>
<!-- End Footer -->
