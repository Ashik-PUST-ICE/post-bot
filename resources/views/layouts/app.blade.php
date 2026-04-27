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
            @if(auth()->user()->role == USER_ROLE_SUPER_ADMIN)
                @include('sadmin.layouts.sidebar')
            @elseif(auth()->user()->role == USER_ROLE_ADMIN)
                @include('admin.layouts.sidebar')
            @else
                @include('layouts.sidebar')
            @endif
            <!-- Main Content -->
            <div class="zMainContent">
                <!-- Header -->
                @if(auth()->user()->role == USER_ROLE_SUPER_ADMIN)
                    @include('sadmin.layouts.nav')
                @else
                    @include('admin.layouts.nav')
                @endif
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
