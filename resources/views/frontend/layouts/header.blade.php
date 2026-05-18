<style>
    /* Premium sleek landing header styling */
    .landing-header {
        padding: 1.25rem 0 !important;
        border-bottom: 1px solid rgba(229, 229, 224, 0.3);
        transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
    }

    /* Slim sticky state with backdrop blur */
    .landing-header.sticky-on {
        padding: 0.875rem 0 !important;
        background-color: rgba(255, 255, 255, 0.85) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.02) !important;
        border-bottom: 1px solid rgba(229, 229, 224, 0.7);
    }

    /* Adjust logo size to look ultra-clean */
    .landing-header img {
        max-height: 44px !important;
        width: auto !important;
        object-fit: contain;
    }

    /* Premium menu navigation items styling */
    .landing-menu-navbar-nav li a {
        font-size: 1.05rem !important;
        font-weight: 600 !important;
        color: #4A4A45 !important;
        padding: 0.5rem 1.25rem !important;
        transition: color 0.2s ease !important;
    }

    .landing-menu-navbar-nav li a:hover {
        color: var(--main-color) !important;
    }

    /* Overriding the chunky py-19 px-33 buttons */
    .landing-header .col-xl-3 a {
        padding: 0.75rem 1.75rem !important;
        font-size: 0.975rem !important;
        font-weight: 700 !important;
        line-height: 1.25rem !important;
        border-radius: 8px !important;
        transition: all 0.25s ease !important;
    }

    .landing-header.sticky-on .col-xl-3 a {
        padding: 0.625rem 1.5rem !important;
        font-size: 0.925rem !important;
    }
</style>

<!-- Start Header -->
<header class="landing-header">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2 col-6">
                <a href="{{route('frontend')}}">
                    <img src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
                </a>
            </div>
            <div class="col-xl-7 col-lg-6 col-6">
                <nav class="navbar navbar-expand-lg p-0">
                    <button class="navbar-toggler bd-c-main-color text-main-color fs-30 ms-auto" type="button"
                            data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                            aria-label="Toggle navigation">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="navbar-collapse landing-menu-navbar-collapse offcanvas offcanvas-start" tabindex="-1"
                         id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                        <button type="button"
                                class="d-lg-none w-30 h-30 p-0 rounded-circle bg-white border-0 position-absolute top-10 right-10"
                                data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-solid fa-times"></i></button>
                        <ul class="navbar-nav landing-menu-navbar-nav justify-content-md-center flex-wrap w-100">
                            <li class="nav-item"><a class="nav-link" href="/">{{ __('Home') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="/#features">{{ __('Features') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="/#price">{{ __('Pricing') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="/#faq">{{ __('FAQ\'s') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('about_us') }}">{{ __('About Us') }}</a></li>
                        </ul>
                        <div class="d-lg-none mt-7">
                            <ul class="landing-menu-navbar-nav d-flex flex-column">
                                @if (auth()->check())
                                    @if (auth()->user()->role == USER_ROLE_SUPER_ADMIN)
                                    <li class="nav-item">
                                        <a href="{{ route('super-admin.dashboard') }}"
                                        class="nav-link">{{ __('Dashboard') }}</a>
                                    </li>
                                    @else
                                    
                                    <li class="nav-item">
                                        <a href="{{ route('admin.dashboard') }}"
                                        class="nav-link">{{ __('Dashboard') }}</a>
                                    </li>
                                    @endif
                                @else
                                <li class="nav-item">
                                    <a href="{{route('login')}}"
                                    class="nav-link">{{__('Log In')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('register')}}"
                                    class="nav-link">{{__('Sign Up')}}</a>
                                </li>
                                @endif
                            </ul>
                            <div class="mt-25">
                                <div class="dropdown lanDropdown">
                                    <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8"
                                            type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        <img src="{{ asset(selectedLanguage()?->flag) }}" alt=""/>
                                        <span>{{ selectedLanguage()?->language }}</span>
                                    </button>
                                    <ul class="dropdown-menu  dropdownItem-one">
                                        @foreach (appLanguages() as $app_lang)
                                            <li>
                                                <a class="d-flex align-items-center cg-8"
                                                href="{{ url('/local/' . $app_lang->iso_code) }}">
                                                    <div class="d-flex rounded-circle overflow-hidden flex-shrink-0 language-image">
                                                        <img src="{{ asset($app_lang->flag) }}" alt="" class="max-w-26"/>
                                                    </div>
                                                    <p class="fs-13 fw-500 lh-16 text-title-black">{{ $app_lang->language }}</p>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-xl-3 col-lg-4 d-none d-lg-block">
                <div class="d-flex justify-content-end g-10 align-items-center">
                    <div class="d-flex justify-content-end g-5">
                        <div class="dropdown lanDropdown">
                            <button class="dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center cg-8"
                                    type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="{{ asset(selectedLanguage()?->flag) }}" alt=""/>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdownItem-one">
                                @foreach (appLanguages() as $app_lang)
                                    <li>
                                        <a class="d-flex align-items-center cg-8"
                                           href="{{ url('/local/' . $app_lang->iso_code) }}">
                                            <div class="d-flex rounded-circle overflow-hidden flex-shrink-0 language-image">
                                                <img src="{{ asset($app_lang->flag) }}" alt="" class="max-w-26"/>
                                            </div>
                                            <p class="fs-13 fw-500 lh-16 text-title-black">{{ $app_lang->language }}</p>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end g-5">
                        @if (auth()->check())
                            @if (auth()->user()->role == USER_ROLE_SUPER_ADMIN)
                                <a href="{{ route('super-admin.dashboard') }}"
                                   class="py-19 px-33 bd-one bd-c-main-color bd-ra-4 d-inline-flex bg-white fs-18 fw-700 lh-24 text-main-color">{{ __('Dashboard') }}</a>
                            @else
                                <a href="{{ route('admin.dashboard') }}"
                                   class="py-19 px-33 bd-one bd-c-main-color bd-ra-4 d-inline-flex bg-white fs-18 fw-700 lh-24 text-main-color">{{ __('Dashboard') }}</a>
                            @endif
                        @else
                            <a href="{{route('login')}}"
                               class="py-19 px-33 bd-one bd-c-ld-black bd-ra-4 d-inline-flex bg-ld-black fs-18 fw-700 lh-24 text-white">{{__('Log In')}}</a>
                            <a href="{{route('register')}}"
                               class="py-19 px-33 bd-one bd-c-main-color bd-ra-4 d-inline-flex bg-white fs-18 fw-700 lh-24 text-main-color">{{__('Sign Up')}}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- End Header -->
