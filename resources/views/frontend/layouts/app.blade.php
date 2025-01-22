<!doctype html>
<html lang="{{ htmlLang() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Adam Landow')">
    @yield('meta')
    @stack('before-styles')
    @vite('resources/css/backend/backend.css')
    @stack('before-scripts')
</head>

<body class="bg-gray-200">
    <div class="antialiased bg-gray-50 dark:bg-gray-900">
        @include('frontend.includes.header')


            @yield('content')


    </div>

</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/frontend/frontend.js')

</html>
