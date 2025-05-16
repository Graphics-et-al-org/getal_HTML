@extends('frontend.layouts.app')

@section(__('Dashboard'))
    @push('before-styles')
        <style>
            #hybridInput {
                width: 100%;
                height: 200px;
                padding: 10px;
                font-size: 16px;
                border: 2px dashed #aaa;
                background: #f9f9f9;
                resize: vertical;
            }

            #hybridInput.dragover {
                border-color: #28a745;
                background: #e6ffee;


            }

            .preview-item {
                margin-top: 10px;
                padding: 8px;
                border: 1px dashed #ccc;
                background: #f8f8f8;
                border-radius: 6px;
            }
        </style>

        @section('content')
            <div class="pt-16  overflow-auto bg-white ">
                <div class="mx-10 mt-2 border border-2 border-gray-500 rounded-md">
                    <div>
                        <h5 class="ml-2 mb-2 text-xl dark:text-white">Generate a diagram from your notes</h5>
                    </div>
                    <div class="border-b border-gray-900/10 pb-12 mx-2 z-10">
                        <form id="input-form" enctype="multipart/form-data">
                            {{-- <textarea id="hybridInput" name="textContent" placeholder="Type or paste your text here. Or drop a file to upload..."></textarea>
                            <div id="previewContainer"></div> --}}
                            <div id="hybrid-file-input"
                                class="border p-2 rounded w-full cursor-text bg-gray-100 hover:bg-gray-200 transition relative "
                                role="presentation">
                                <!-- Textarea for text input -->
                                <textarea id="doctorTextInput" class="w-full h-32 p-2 bg-transparent focus:outline-none cursor-text resize-none rounded"
                                    placeholder="Enter text or drop a file..."></textarea>
                                <div id="previewContainer" class="hidden flex items-center w-full border-2 border-red-500 p-2">
                                    <div class="shrink"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5" />
                                            <path
                                                d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z" />
                                        </svg></div>
                                    <div id="previewLabel" class="shrink"></div>
                                    <div class="shrink"><button type="button" id="removeFileButton"
                                            class="bg-white rounded-md p-1 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                                            <span class="sr-only">Close menu</span>
                                            <!-- Smaller icon: h-4 w-4 instead of h-6 w-6 -->
                                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button></div>
                                </div>
                                <!-- File Size Error Message -->
                                {{-- {#if fileError}
                                  <p class="text-red-500 text-sm mt-1">{fileError}</p>
                                {/if} --}}

                                <!-- Loading Spinner -->

                                <div class="absolute right-3 top-3 hidden file-loading-feedback">
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


                                <!-- Hidden file input -->
                                <input type="file" id="fileInput" class="hidden" />

                                <!-- Browse Button -->
                                <button id="open-filepicker-button"
                                    class="absolute bottom-2 right-2 bg-blue-500 text-white px-4 py-2 rounded file-loading-feedback">

                                    Browse

                                </button>
                            </div>

                            <br>
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div class="flex items-center justify-between ">
                                    <h3 class="text-xl my-2 font-semibold text-gray-900 dark:text-white">
                                        Add from our database
                                    </h3>

                                </div>
                                <!-- Modal body -->
                                <div class="p-4 md:p-5">

                                    <div class="flex-initial min-w-80 mb-4">
                                        <input type="text" name="search" id="categorysearch"
                                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                            placeholder="Type to search...">

                                    </div>
                                    {{-- Selection here --}}
                                    <div class="min-w-80 mb-4">
                                        <ul class="w-full flex " id="categorieslist">
                                        </ul>
                                    </div>
                                    {{-- <div class="min-w-80 mb-4 bg-gray-100 border border-solid border-2 border-gray-500 rounded-md flex"
                                        id="selected_categories">
                                        <div id="placeholder" class="w-full">No items selected</div>

                                    </div> --}}
                                    <div class="space-y-4">

                                    </div>
                                </div>

                                <div>
                                    <button id="submitButton"
                                        class="w-full disabled:text-gray-500 text-white bg-blue-700 disabled:bg-blue-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                        type="submit">Generate</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>


        @endsection

        @push('after-scripts')
            <script>
                const baseurl = '{{ URL::to('/') }}';
            </script>
            @vite('resources/js/frontend/dashboard/dashboard.js')

        @endpush
