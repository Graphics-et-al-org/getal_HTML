<!doctype html>
<html lang="{{ htmlLang() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', appName())</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Adam Landow')">
    @yield('meta')
    @stack('before-styles')
    @vite('resources/css/app.css')
    @stack('before-scripts')

</head>

<body class="antialiased bg-gray-50  text-gray-800 p-5 font-sans" style="font-family: 'Inter', sans-serif;">
    @yield('header')
    <div class=" mx-auto bg-white rounded-lg shadow-md p-6" >


        @yield('content')

    </div>

    @yield('footer')
    @yield('modals')

</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/frontend/frontend.js')

</html>
