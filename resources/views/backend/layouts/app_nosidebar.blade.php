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
    @stack('after-styles')

</head>


<body class="bg-gray-200">
    @include('backend.includes.header')

    <div class="fixed top-16 left-0 right-0 bottom-0 overflow-auto">
           <!-- Scrollable Main Content -->
           <main class="w-full p-4">
                @include('includes.flashmessages')
                @yield('content')
        </main>
    </div>
    @yield('modals')
</body>

@stack('before-scripts')
@stack('after-scripts')

@vite('resources/js/backend/backend.js')

</html>
