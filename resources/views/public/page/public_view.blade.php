<!doctype html>
<html lang="{{ htmlLang() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->label }} </title>
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
    const baseurl = '{{ URL::to('/') }}';
    // get php session ID
    const sessionid = "{{ Session::getId(); }}"
    // get the page uuid
    const uuid = "{{ $page->uuid }}"

    // get JWT token
    const jwt = "{{ $auth_token }}";

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

<body class="antialiased bg-gray-50  text-gray-800 p-5 font-sans" style="font-family: 'Inter', sans-serif;">
    {!! $page->header !!}
    <div class="mx-auto bg-white rounded-lg shadow-md p-6">
        <main class="p-4 h-auto ">

            {{-- Widget wrapper --}}
            <div class="fixed top-2 right-2 z-50 bg-white border border-gray-300 shadow-lg rounded-lg flex items-center gap-3 p-3 "
                id="google_translate_wrapper">
                <!-- Translate widget -->
                <div id="google_translate_element" class="inline-block"></div>
            </div>

            <div class="container mx-auto">
                {{-- Title --}}
                <div class="text-center mb-6 border-b border-gray-300">
                    <h1 id="title"
                        class="text-3xl font-semibold px-2 py-1 focus:rounded-md  focus:border-2 focus:outline-none focus:border-blue-500 focus:border">
                        {{ $page->title }}
                    </h1>
                </div>
                {{-- summary --}}

                <div class=" items-center mb-4 ">
                    <div id="summary"
                        class="w-full p-4 text-xl border-2 border-gray-300 rounded-md focus:outline-none focus:border-blue-500 resize-none">
                        {{ $page->summary }}</div>

                </div>
                {{-- Keypoints --}}
                {{-- <h1 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
            Summary
        </h1> --}}
                @foreach ($page->components as $component)
                    @switch($component->type)
                        @case('keypoints')
                            <div
                                class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 grid-flow-row auto-cols-auto gap-6 border border-2 border-red-500 rounded-md justify-center justify-items-center items-center  p-2 keypoints">
                                @foreach ($component->snippets as $snippet)
                                    <div class="relative object-contain keypoint-container"
                                        data-keypointid="{{ $snippet->id }}" data-keypointuuid='{{ $snippet->uuid }}'
                                        data-componentid='{{ $component->id }}' id="keypoint_{{ $snippet->uuid }}">
                                        {!! $snippet->content !!}
                                    </div>
                                @endforeach

                            </div>
                        @break

                        @case('snippets')
                            <div class="nonclinical-title text-xl font-semibold text-center my-4 text-gray-700">Some extra
                                Information
                                for you</div>
                            <div class="w-full border border-2 border-blue-500 rounded-md p-2 snippets">
                                @foreach ($component->snippets as $snippet)
                                    <div data-snippetid="{{ $snippet->id }}" data-snippetuuid='{{ $snippet->uuid }}'
                                        data-componentid='{{ $component->id }}'>
                                        {!! $snippet->content !!}
                                    </div>
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
    </div>
    {!! $page->footer !!}
</body>
@stack('after-styles')
@stack('after-scripts')
@vite('resources/js/public/public_view.js')

@vite('resources/js/common/tailwind_classes.js')
@vite('resources/js/public/public_analytics.js')

</html>
