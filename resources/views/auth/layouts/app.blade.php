<!DOCTYPE html>
<html class="no-js" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('admin.layouts.header')

<body>
<!-- Main Content -->
@yield('content')

<!-- js file  -->
@include('admin.layouts.script')
</body>
</html>
