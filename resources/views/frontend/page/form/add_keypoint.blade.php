<div class="relative p-4 w-full max-w-md max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Add keypoint
            </h3>
            <button type="button"
                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                onclick="window.closeAddKeypointModal()">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <!-- Modal body -->
        <div class="p-4 md:p-5">
            <div class="space-y-4">

                <div>
                    <div class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 text-center">
                        <img id="keypoint_image" class="object-contain w-full h-full "
                            src="{{ asset('static/img/questionmark.svg') }}" alt="">
                        <div id="keypoint_image_waiting" class="object-contain w-full h-full hidden" alt="">
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

                    <div class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 text-center">
                        <textarea id="keypoint_text" rows="4" cols="50"
                            class="w-full h-full border border-solid border-2 border-red-500  rounded-md mb-2 text-center"
                            placeholder="Keypoint Text"></textarea>
                    </div>
                    <div class="mb-2">
                        <button role="button" type="button" onclick="window.getKeypointIcon()"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Get icon</button>


                    </div>

                    <button role="button" type="button" onclick="window.addKeypoint()"
                        class="w-full disabled:text-gray-500 text-white bg-blue-700 disabled:bg-blue-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

