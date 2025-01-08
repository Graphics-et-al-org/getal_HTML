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
    @vite('resources/css/backend/backend.css')
    @stack('before-scripts')
</head>

<body class="bg-gray-200">
    @include('backend.includes.header')
    <div class="flex h-screen pt-16">
        @include('backend.includes.sidebar')
           <!-- Scrollable Main Content -->
           <main class="ml-64 flex-1 overflow-y-auto">
                @include('includes.flashmessages')
                @yield('content')
        </main>

    </div>
    @yield('modals')
</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/backend/backend.js')

</html>
