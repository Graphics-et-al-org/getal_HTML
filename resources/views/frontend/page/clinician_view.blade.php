@extends('frontend.layouts.clinician_view_layout')

@section('title')
    Clinician view
@endsection


@section('header')
    {!! $page->header !!}
@endsection

@push('after-styles')
    <style>
        button>* {
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <main class="p-4 h-auto">

        <div class="container mx-auto">
            @if ($page->title)
                {{-- Title --}}
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
                        <div
                            class="w-full grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 grid-flow-row auto-cols-auto gap-6 border border-2 border-red-500 rounded-md justify-center justify-items-center items-center  p-2 keypoints">
                            @foreach ($component->snippets as $snippet)
                                <div class="relative object-contain keypoint-container" data-keypointid="{{ $snippet->id }}"
                                    data-keypointuuid='{{ $snippet->uuid }}' data-componentid='{{ $component->id }}'
                                    id="keypoint_{{ $snippet->uuid }}">
                                    {!! $snippet->content !!}
                                    {{-- Add remove button --}}
                                    <button type="button" class="remove-keypoint-button absolute top-1 right-1"
                                        data-tippy-content="Remove" data-uuid="{{ $snippet->uuid }}"
                                        onclick="window.deleteKeypoint(event)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#721c24"
                                            class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                                        </svg>
                                    </button>
                                    {{-- Get keypoint icon --}}
                                    <button
                                        class="hidden absolute top-3 left-3  border border-2 border-gray-500 rounded-md bg-slate-200 get-keypoint-image-btn"
                                        data-tippy-content="Generate icon" onclick="window.getKeypointIcon(event)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#721c24"
                                            class="bi bi-magic" viewBox="0 0 16 16">
                                            <path
                                                d="M9.5 2.672a.5.5 0 1 0 1 0V.843a.5.5 0 0 0-1 0zm4.5.035A.5.5 0 0 0 13.293 2L12 3.293a.5.5 0 1 0 .707.707zM7.293 4A.5.5 0 1 0 8 3.293L6.707 2A.5.5 0 0 0 6 2.707zm-.621 2.5a.5.5 0 1 0 0-1H4.843a.5.5 0 1 0 0 1zm8.485 0a.5.5 0 1 0 0-1h-1.829a.5.5 0 0 0 0 1zM13.293 10A.5.5 0 1 0 14 9.293L12.707 8a.5.5 0 1 0-.707.707zM9.5 11.157a.5.5 0 0 0 1 0V9.328a.5.5 0 0 0-1 0zm1.854-5.097a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L8.646 5.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0l1.293-1.293Zm-3 3a.5.5 0 0 0 0-.706l-.708-.708a.5.5 0 0 0-.707 0L.646 13.94a.5.5 0 0 0 0 .707l.708.708a.5.5 0 0 0 .707 0z" />
                                        </svg>
                                    </button>
                                    {{-- Feedback --}}
                                    <div class="object-contain w-full h-full absolute top-3 left-3 keypoint_image_waiting hidden"
                                        alt="">
                                        <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <style>
                                                .spinner_5nOS {
                                                    transform-origin: center;
                                                    animation: spinner_sEAn 1.5s infinite linear;
                                                }

                                                @keyframes spinner_sEAn {
                                                    100% {
                                                        transform: rotate(360deg);
                                                    }
                                                }
                                            </style>
                                            <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"
                                                opacity=".25" />
                                            <path
                                                d="M10.72,19.9a8,8,0,0,1-6.5-9.79A7.77,7.77,0,0,1,10.4,4.16a8,8,0,0,1,9.49,6.52A1.54,1.54,0,0,0,21.38,12h.13a1.37,1.37,0,0,0,1.38-1.54,11,11,0,1,0-12.7,12.39A1.54,1.54,0,0,0,12,21.34h0A1.47,1.47,0,0,0,10.72,19.9Z"
                                                class="spinner_5nOS" />
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                            {{-- New keypoint button --}}
                            <div data-type="keypoints" data-tippy-content="New Keypoint"
                                class="self-auto relative grid w-48 min-h-48 border border-dashed border-2 border-gray-500 rounded-md addbutton cursor-pointer"
                                onclick="window.addKeypoint()">
                                <div class="col-span-full m-0 p-2">
                                    <div
                                        class="h-32 w-full border border-solid border-2 border-pink-200 rounded-md mb-2 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="-4 -4 24 24" width="128" height="128"
                                            fill="#b0bec5" class="bi bi-plus-lg">
                                            <path fill-rule="evenodd"
                                                d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
                                            </path>
                                        </svg>
                                    </div>
                                    <div
                                        class="min-h-12 w-full border border-dashed border-2 border-red-200  rounded-md text-center">
                                        (Click to add new keypoint)
                                    </div>
                                </div>
                            </div>
                        </div>
                    @break

                    @case('snippets')
                        <div class="nonclinical-title text-xl font-semibold text-center mb-4 text-gray-700">Some extra Information
                            for you</div>
                        <div id="component_{{ $component->id }}" data-componentid='{{ $component->id }}'
                            class="w-full border border-2 border-blue-500 rounded-md p-2 snippets">

                            @foreach ($component->snippets as $snippet)
                                <div data-snippetid="{{ $snippet->id }}" data-snippetuuid='{{ $snippet->uuid }}'
                                    class="snippet-container relative">

                                    {!! $snippet->content !!}
                                    <button type="button" class="remove-keypoint-button absolute top-1 right-1"
                                        data-tippy-content="Remove" data-uuid="{{ $snippet->uuid }}"
                                        onclick="window.deleteSnippet(event)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#721c24"
                                            class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z" />
                                        </svg>
                                    </button>
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


            {{-- <div class="flex justify-center mt-4 w-full">
                <button id="addKeypointButton" type="button"
                    class="inline-flex w-full justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 border border-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:border-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    onclick="window.openAddCollectionModal()">
                    Add information from our database
                </button>
            </div> --}}

            <div class="text-center mt-8">
                <button id="openSnippetLibraryBtn" onclick="window.openAddCollectionModal()"
                    class="px-6 py-3 bg-blue-500 text-white rounded hover:bg-blue-600">
                    + Add Information from Our Database
                </button>
            </div>

            <button class="save-btn bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                onclick="window.showSternWarning()">
                Save and Generate Link
            </button>

        </div>
        {{-- Button to add snippets --}}


    </main>
    {{-- Interactivity --}}
    @push('after-scripts')
        <script>
            const uuid = '{{ $page->uuid ?? -1 }}';
            const baseurl = '{{ URL::to('/') }}';
            var used_images = [

            ]
        </script>
        @vite('resources/js/frontend/clinician_page/clinician_page.js')
    @endpush
@endsection

@section('footer')
    {!! $page->footer !!}
@endsection
@section('modals')
    <div id="addKeypointModal" role="dialog" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        @include('frontend.page.form.add_keypoint')
    </div>

    <div id="addCollectionModal" role="dialog" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        @include('frontend.page.form.add_collection')
    </div>

    <div id="publicDetailsModal" role="dialog" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        @include('frontend.page.form.public_info')
    </div>

    <!-- Loading Modal -->
    <div id="loading-modal" class="hidden fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
        role="alert" aria-live="assertive" aria-busy="true">
        <div class=" rounded-lg p-6 flex flex-col items-center gap-4">
            <!-- Spinner -->
            <svg width="64" height="64" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <style>
                    .spinner_5nOS {
                        transform-origin: center;
                        animation: spinner_sEAn 1.5s infinite linear;
                    }

                    @keyframes spinner_sEAn {
                        100% {
                            transform: rotate(360deg);
                        }
                    }
                </style>
                <path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
                <path
                    d="M10.72,19.9a8,8,0,0,1-6.5-9.79A7.77,7.77,0,0,1,10.4,4.16a8,8,0,0,1,9.49,6.52A1.54,1.54,0,0,0,21.38,12h.13a1.37,1.37,0,0,0,1.38-1.54,11,11,0,1,0-12.7,12.39A1.54,1.54,0,0,0,12,21.34h0A1.47,1.47,0,0,0,10.72,19.9Z"
                    class="spinner_5nOS" />
            </svg>
            <!-- Label -->

        </div>
    </div>
@endsection
