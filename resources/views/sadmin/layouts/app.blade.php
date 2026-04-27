<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('sadmin.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">

        @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
            <div id="preloader">
                <div id="preloader_status">
                    <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}" />
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <div class="zMain-wrap">
            <!-- Sidebar -->
            @include('sadmin.layouts.sidebar')
            <!-- Main Content -->
            <div class="zMainContent">
                <!-- Header -->
                @include('sadmin.layouts.nav')
                <!-- Content -->
                @yield('content')
            </div>
        </div>

    @if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
        <div class="cookie-consent-wrap shadow-lg">
            @include('cookie-consent::index')
        </div>
    @endif
    @include('sadmin.layouts.script')
</body>

</html>
