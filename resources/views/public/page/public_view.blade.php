<!doctype html>
<html lang="{{ htmlLang() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ appName() }} | @yield('title')</title>
    <meta name="description" content="@yield('meta_description', appName())">
    <meta name="author" content="@yield('meta_author', 'Adam Landow')">
    @yield('meta')
    @stack('before-styles')
    @vite('resources/css/public.css')
    @stack('before-scripts')
</head>

<body class="bg-gray-200">
  {!! $page->content !!}

</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/public/public.js')
@vite('resources/js/common/editor_tailwind_clases.js')

</html>
