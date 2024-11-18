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
    @include('backend.includes.sidebar')
    <div class="w-9/12">
        <div class="p-4 text-gray-500">
            @yield('content')
        </div>
    </div>
{{--
    <div class="c-wrapper c-fixed-components">
        <div class="c-body">
            <main class="c-main"> --}}
                {{-- <div class="container-fluid">
                    <div class="fade-in"> --}}

                        {{-- @yield('content') --}}
                    {{-- </div><!--fade-in-->
                {{-- </div><!--container-fluid--> --}}
            </main>
        </div><!--c-body--> --}}

        {{-- @include('backend.includes.footer') --}}
    <!--c-wrapper-->

    {{-- @stack('before-scripts')
@vite( 'resources/js/backend/backend.js')
    @stack('after-scripts') --}}
</body>
</html>
