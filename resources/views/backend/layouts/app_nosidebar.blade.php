<!doctype html>

<html lang="{{ htmlLang() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
    @yield('meta')

    @stack('before-styles')

    @stack('after-styles')
    @vite('resources/css/backend/backend.css')
</head>
@include("backend.includes.header")
<body class="bg-gray-200">

    @yield('content')



    {{-- @include('backend.includes.footer') --}}
    <!--c-wrapper-->


</body>
@stack('before-scripts')

    @stack('after-scripts')
</html>
