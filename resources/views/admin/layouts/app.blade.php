<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('admin.layouts.header')

<body class="{{ selectedLanguage()->rtl == 1 ? 'direction-rtl' : 'direction-ltr' }}">
    <input type="hidden" id="lang_code" value="{{session('local')}}">
    @if (getOption('app_preloader_status', 0) == STATUS_ACTIVE)
    <div id="preloader">
        <div id="preloader_status">
            <img src="{{ getSettingImage('app_preloader') }}" alt="{{ getOption('app_name') }}" />
        </div>
    </div>
    @endif
    <div class="zMain-wrap">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')
        <!-- Main Content -->
        <div class="zMainContent">
            <!-- Header -->
            @include('admin.layouts.nav')
            <!-- Content -->
            @yield('content')
        </div>
    </div>


    @if (!empty(getOption('cookie_status')) && getOption('cookie_status') == STATUS_ACTIVE)
    <div class="cookie-consent-wrap shadow-lg position-fixed bottom-0 w-100 bg-white z-index-1001">
        @include('cookie-consent::index')
    </div>
    @endif
    @include('admin.layouts.script')
</body>

</html>
