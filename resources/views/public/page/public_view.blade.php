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
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
                pageLanguage: "en",

                autoDisplay: true,
            },
            "google_translate_element"
        );


    }
    // function googleTranslateElementInit() {
    //   new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: "en,fr,ar,es", layout: google.translate.TranslateElement.InlineLayout.SIMPLE,  autoDisplay: false}, 'google_translate_element');
    // }
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
<style>
    /* .goog-te-gadget{
       width:300px !important;
   } */

    .goog-te-gadget {
        display: inline-block !important;
        white-space: nowrap !important;
        line-height: 1.25 !important;
        font-size: 0.875rem !important;
    }

    .goog-te-gadget .goog-logo-link {
        font-size: inherit !important;
        display: inline !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
    }

    .goog-te-gadget span {
        display: inline-block !important;
        white-space: nowrap !important;
    }

    .goog-te-gadget img {
        display: inline-block !important;
        white-space: nowrap !important;
    }
</style>
<main class="p-4 h-auto pt-20">
    {{-- Widget wrapper --}}
    <div class="fixed top-4 right-4 z-50 bg-white border border-gray-300 shadow-lg rounded-lg flex items-center gap-3 p-3 "
        id="google_translate_wrapper">
        <!-- Translate widget -->
        <div id="google_translate_element" class="inline-block"></div>
    </div>


    <div class="container mx-auto">
        {{-- Render the HTML --}}
        {!! $html !!}
    </div>

</main>
</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/public/public.js')
@vite('resources/js/common/editor_tailwind_clases.js')

</html>
