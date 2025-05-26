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
    const sessionid = "{{ Session::getId() }}"
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


<body class="antialiased bg-gray-50 text-gray-800 md:p-5 font-sans static" style="font-family: 'Inter', sans-serif;">
    {{-- Widget wrapper --}}
    <div id="translate-drawer" class="fixed sm:bottom-auto sm:bottom-2 md:top-1 right-1 z-50 flex items-center">
        <!-- 2. Handle button (always visible) -->
        <button data-tippy-content="Translate" id="translate-toggle"
            class="
      w-12 h-12
      bg-white border border-gray-300 shadow-lg
      rounded-full
      flex items-center justify-center
      focus:outline-none
    "
            aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-translate" viewBox="0 0 16 16">
                <path
                    d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z" />
                <path
                    d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31" />
            </svg>
        </button>

        <!-- 3. Sliding panel wrapper -->
        <div id="translate-panel" class="overflow-hidden transition-all duration-300 ease-out w-0">
            <!-- 4. Actual panel content -->
            <div
                class="
        w-64            /* full width when open */
        bg-white border border-gray-300 shadow-lg
        rounded-lg p-3 ml-2
      ">
                <div id="google_translate_element"></div>
            </div>
        </div>
    </div>
    {{-- <div class="fixed  md:bottom-auto  md:top-2 sm:bottom-2 right-2 z-50 bg-white border border-gray-300 shadow-lg rounded-lg flex items-center gap-3 p-3 "
                id="google_translate_wrapper">
                <!-- Translate widget -->
                <div id="google_translate_element" class="inline-block"></div>
            </div> --}}
    {!! $page->header !!}
    <div class="mx-auto bg-white rounded-lg shadow-md md:p-6">
        <main class="md:p-4 h-auto ">



            <div class="container mx-auto">
                {{-- Title --}}
                @if ($page->title)
                    <div class="text-center mb-6 border-b border-gray-300">
                        <h1 id="title"
                            class="text-3xl font-semibold px-2 py-1 focus:rounded-md  focus:border-2 focus:outline-none focus:border-blue-500 focus:border">
                            {{ $page->title }}
                        </h1>
                    </div>
                @endif
                {{-- summary --}}
                @if ($page->summary)
                    <div class=" items-center mb-4 ">
                        <div id="summary"
                            class="w-full p-4 text-xl border-2 border-gray-300 rounded-md focus:outline-none focus:border-blue-500 resize-none">
                            {{ $page->summary }}</div>

                    </div>
                @endif

                {{-- Content --}}
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
                                        data-componentid='{{ $component->id }}' class="snippet-container">
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
<script>
    const toggle = document.getElementById('translate-toggle')
    const panel = document.getElementById('translate-panel')
    const icon = document.getElementById('translate-icon')
    let open = false

    toggle.addEventListener('click', () => {
        open = !open
        toggle.setAttribute('aria-expanded', open)

        if (open) {
            panel.classList.replace('w-0', 'w-64')
            icon.classList.add('rotate-180')
        } else {
            panel.classList.replace('w-64', 'w-0')
            icon.classList.remove('rotate-180')
        }
    })
</script>
@vite('resources/js/public/public_view.js')

@vite('resources/js/common/tailwind_classes.js')
@vite('resources/js/public/public_analytics.js')

</html>
