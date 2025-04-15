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
{!! $page->header !!}
<main class="p-4 h-auto pt-20">
    
    {{-- Widget wrapper --}}
    <div class="fixed top-2 right-2 z-50 bg-white border border-gray-300 shadow-lg rounded-lg flex items-center gap-3 p-3 "
        id="google_translate_wrapper">
        <!-- Translate widget -->
        <div id="google_translate_element" class="inline-block"></div>
    </div>

    <div class="container mx-auto">
        {{-- Title --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
            Title
        </h1>
        <div class=" items-center mb-4 border border-2 border-gray-500 rounded-md text-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                {{ $page->title }}
            </h1>
        </div>
        {{-- summary --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
            Summary
        </h1>
        <div class=" items-center mb-4 border border-2 border-slate-300 rounded-md text-center ">
            {{ $page->summary }}
        </div>
        {{-- Keypoints --}}
        {{-- <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
            Summary
        </h1> --}}
        @foreach ($page->components as $component)
            @switch($component->type)
                @case('keypoints')
                    {{-- <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                        Keypoints
                    </h1> --}}
                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-6 border border-2 border-red-500 rounded-md justify-center justify-items-center items-center  p-2 keypoints">
                        @foreach ($component->snippets as $snippet)
                            <div class="relative object-contain ">
                                {!! $snippet->content !!}
                            </div>
                        @endforeach

                    </div>
                @break

                @case('snippets')
                    {{-- <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center ">
                        Snippets
                    </h1> --}}
                    <div class="w-full border border-2 border-blue-500 rounded-md p-2 snippets">
                        @foreach ($component->snippets as $snippet)
                            {!! $snippet->content !!}
                        @endforeach
                    </div>
                @break

                @case('html')
                    {{-- <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                        Arbitrary HTML
                    </h1> --}}
                    {!! $component->content !!}
                @break

                @default
            @endswitch
        @endforeach


    </div>

</main>
{!! $page->footer !!}
</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/public/public.js')
@vite('resources/js/common/editor_tailwind_clases.js')

</html>
