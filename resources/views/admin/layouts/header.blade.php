<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>{{ getOption('app_name') }} - @stack('title' ?? '')</title>

    {{-- CSRF token MUST be outside any conditional so it is always rendered.
         jQuery AJAX reads it via $('meta[name="csrf-token"]').attr('content').
         If it were inside @else and a child view pushed a meta section it
         would silently disappear and every POST would return 419. --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @hasSection('meta')
        @stack('meta')
    @else
        @php
            $metaData = getMeta('home');
        @endphp
            <!-- Open Graph meta tags for social sharing -->
            <meta property="og:type" content="website">
            <meta property="og:title" content="{{ getOption('app_name') }}">
            <meta property="og:description" content="{{ getOption('meta_description') }}">
            <meta property="og:image" content="{{ getSettingImage('app_logo') }}">
            <meta property="og:url" content="{{ url()->current() }}">
            <meta property="og:site_name" content="{{ getOption('app_name') }}">

            <!-- Twitter Card meta tags for Twitter sharing -->
            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:title" content="{{ getOption('app_name') }}">
            <meta name="twitter:description" content="{{ getOption('meta_description') }}">
            <meta name="twitter:image" content="{{ getSettingImage('app_logo') }}">

            <!-- Meta keywords for SEO -->
            <meta name="keywords" content="{{ getOption('meta_keyword', '') }}">
    @endif

    <!-- Place favicon.ico in the root directory -->
    <link rel="icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/png" sizes="16x16">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ getSettingImage('app_fav_icon') }}">

    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.responsive.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/summernote/summernote-lite.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/aos.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/plugins.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/scss/style.css')}}" />
    <link rel="stylesheet" href="{{asset('common/css/common.css')}}" />

    @stack('style')
</head>
