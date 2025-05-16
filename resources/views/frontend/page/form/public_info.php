<div class="relative p-12 w-full max-w-md max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Diagram details
            </h3>
            <button type="button"
                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                onclick="window.closePublicDetailsModal()">
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                </svg>
                <span class="sr-only">Close modal</span>
            </button>
        </div>
        <!-- Modal body -->
        <div class="w-full p-4 border border-solid border-2 border-purple-500">
            <div class="grid grid-cols-2 grid-rows-2 gap-2 p-2 w-full border border-solid border-2 border-blue-500">
                <!-- Clinician URL -->
                <div class="self-auto relative grid w-48 min-h-48 border border-solid border-2 border-gray-500 rounded-md ">
                    <div class="col-span-full m-0 m-0 p-2">
                        <div
                            class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 text-center">
                            <svg id="symbols" xmlns="http://www.w3.org/2000/svg" viewBox="0 100 800 800">
                                <defs>
                                    <style>
                                        .cls-1,
                                        .cls-2 {
                                            stroke-width: 0px;
                                        }

                                        .cls-1,
                                        .cls-3 {
                                            fill: #000;
                                        }

                                        .cls-2 {
                                            fill: #fff;
                                        }

                                        .cls-3,
                                        .cls-4 {
                                            stroke: #000;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-width: 10px;
                                        }

                                        .cls-4 {
                                            fill: none;
                                        }
                                    </style>
                                </defs>
                                <g id="doctor">
                                    <g>
                                        <path class="cls-1" d="M403.39,347.81c-64.89,0-117.67-52.79-117.67-117.67s52.79-117.67,117.67-117.67,117.67,52.79,117.67,117.67-52.79,117.67-117.67,117.67Z" />
                                        <path class="cls-1" d="M403.39,112.96c31.3,0,60.72,12.19,82.85,34.32,22.13,22.13,34.32,51.56,34.32,82.86s-12.19,60.72-34.32,82.86c-22.13,22.13-51.56,34.32-82.85,34.32s-60.72-12.19-82.86-34.32c-22.13-22.13-34.32-51.56-34.32-82.86s12.19-60.72,34.32-82.86c22.13-22.13,51.56-34.32,82.86-34.32M403.39,111.96c-65.27,0-118.17,52.91-118.17,118.17s52.91,118.17,118.17,118.17,118.17-52.91,118.17-118.17-52.91-118.17-118.17-118.17h0Z" />
                                    </g>
                                    <g>
                                        <path class="cls-2" d="M247.19,676.76l.66-180.97c.17-46.88,20.77-104.57,66.41-127.75,7.39-3.75,15.42-6.42,23.89-7.94,20.62,8.59,42.32,12.94,64.53,12.94,1.4,0,2.81-.02,4.24-.05,19.85-.51,39.29-4.51,57.81-11.9,11,2.93,21.32,7.31,30.7,13.05,21.85,13.35,39.92,33.31,52.25,57.71,11.33,22.43,17.46,47.48,17.73,72.45l1.85,172.46H247.19Z" />
                                        <path class="cls-1" d="M337.61,365.3c20.81,8.47,42.68,12.76,65.06,12.76,1.45,0,2.92-.02,4.36-.06,19.89-.51,39.37-4.42,57.96-11.64,9.94,2.8,19.29,6.84,27.81,12.05,21.06,12.86,38.48,32.12,50.39,55.7,10.99,21.75,16.93,46.04,17.19,70.25l.95,88.6.84,78.81H252.21l.39-106.26.25-69.69c.17-45.36,19.92-101.08,63.67-123.31,6.54-3.32,13.62-5.74,21.09-7.21M338.74,354.93c-6.74,1.11-16.37,3.39-26.75,8.66-46.74,23.74-68.96,82.05-69.14,132.19-.23,61.99-.45,123.99-.68,185.98h330.12c-.63-59.17-1.27-118.34-1.9-177.51-.53-49.42-24.05-104.87-72.37-134.38-12.88-7.87-25.05-11.86-33.66-14.03-12.46,5.12-32.41,11.52-57.59,12.16-1.38.04-2.75.05-4.11.05-28.77,0-51.22-7.7-63.93-13.12h0Z" />
                                    </g>
                                    <circle class="cls-1" cx="472.65" cy="445.96" r="33.94" />
                                    <circle class="cls-2" cx="472.65" cy="445.96" r="20.08" />
                                    <path class="cls-4" d="M361.94,561.04h19.12c.81-5.33,9.69-70.04-30.84-100.08-5.59-4.14-13.33-8.53-23.8-11.36-11.41,2.64-19.67,7.19-25.43,11.36-41.15,29.83-31.31,96.07-30.66,100.08,6.17-.04,12.34-.08,18.51-.12" />
                                    <path class="cls-1" d="M334.16,449.68h-16.61v-88.18c.13-.05.32-.11.56-.19,1.03-.35,2.96-.99,5.48-1.69,4.46-1.24,7.88-1.85,8.59-1.97.81-.14,1.49-.25,1.95-.32,0,30.79.02,61.57.03,92.36Z" />
                                    <path class="cls-1" d="M464.34,422.23h16.61v-60.88c-2.83-1.15-5.89-2.28-9.17-3.34-2.56-.83-5.04-1.55-7.41-2.17,0,22.13-.02,44.26-.03,66.39Z" />
                                    <path class="cls-3" d="M340.6,365.7l62.93,75.6,62.65-75.27c-12.24,5.47-35.1,13.77-64.74,13.32-27.58-.42-48.88-8.23-60.84-13.65Z" />
                                    <line class="cls-3" x1="403.39" y1="425.87" x2="403.39" y2="667.62" />
                                    <line class="cls-4" x1="472.65" y1="547.81" x2="472.65" y2="637.29" />
                                    <line class="cls-4" x1="517.39" y1="592.55" x2="427.91" y2="592.55" />
                                </g>
                            </svg>
                        </div>
                        <div
                            class="min-h-12 w-full border border-solid border-2 border-red-500  rounded-md text-center"
                            data-field="keypoint-text">Copy clinician URL</div>
                    </div>
                </div>
                <div
                    class="self-auto relative grid w-48 min-h-48 border border-solid border-2 border-gray-500 rounded-md ">
                    <div class="col-span-full m-0 m-0 p-2">
                        <div
                            class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 text-center">


                            <svg id="specialistpatientcomm" xmlns="http://www.w3.org/2000/svg" viewBox="0 100 800 800">
                                <defs>
                                    <style>
                                        .cls-1,
                                        .cls-2 {
                                            stroke: #000;
                                            stroke-linecap: round;
                                            stroke-linejoin: round;
                                            stroke-width: 15px;
                                        }

                                        .cls-2 {
                                            fill: none;
                                        }
                                    </style>
                                </defs>
                                <path class="cls-2" d="M451.16,524c.67-21.24,2.02-38.66,3.19-51.08,1.71-18.05,2.76-22.56,5.09-27.57,1.88-4.05,8.2-16.18,35.4-31.14,12.84-7.07,33.13-16.3,60.51-21.29,5.55,5.74,13.49,13.68,23.46,22.77,20.09,18.31,25.33,20.23,30.36,20.01,3.46-.15,7.95-1.37,23.46-15.87,10.95-10.23,19.43-19.65,25.53-26.91,25.06,4.67,44.1,12.85,56.58,19.32,18.19,9.43,34.55,18.2,41.4,36.02,1.46,3.8,2.15,7.12,3.22,20.47,1.07,13.28,2.17,32.15,2.12,55.46" />
                                <circle class="cls-2" cx="677.12" cy="477.43" r="18.97" />
                                <path class="cls-2" d="M700.68,405.63c-1.41,7.29-3.33,15.3-5.96,23.84-2.77,8.99-5.87,17.05-8.97,24.15" />
                                <path class="cls-2" d="M539.48,508.13c-10.4,5.88-16.14,5.02-19.32,3.45-15.74-7.8-16.03-56.87,2.76-65.55,18.84-8.69,56.06,23.56,51.75,40.71-.83,3.31-3.65,7.81-13.11,11.73" />
                                <path class="cls-2" d="M522.92,446.03c-2.32-4.2-4.87-9.76-6.73-16.56-2.55-9.32-2.85-17.49-2.59-23.29" />
                                <path class="cls-2" d="M587.93,341.48c.31.51,7.29,11.48,20.39,11.27,12.63-.2,19.23-10.59,19.64-11.27" />
                                <path class="cls-2" d="M693.82,310.53c-.47,2.17-3.16,13.09-13.33,16.85-2.03.76-4.54,1.29-7.09,1.36-10.58,30.4-34.14,59.91-65.52,59.91s-54.96-29.87-65.38-59.89c-2.75-.01-5.48-.54-7.67-1.37-10.17-3.77-12.85-14.69-13.33-16.85-1.75-8.15.05-20.42,7.29-23.01,2.37-.84,4.84-.47,7.21.54.42-12.65,3.62-30.54,16.96-46.48,3.51-4.19,23.08-26.64,54.92-26.33,32.72.31,52.18,24.43,56.49,30.17,11.24,14.96,14.43,30.97,15.11,42.58,2.32-.96,4.73-1.3,7.05-.47,7.24,2.59,9.04,14.86,7.29,23.01Z" />
                                <path class="cls-1" d="M36.15,523.27c2.17-23.65,3.37-40.85,3.19-51.08-.07-3.77-.2-13.3,3.88-24.66.37-1.02.76-1.96,1.21-2.91,1.88-4.05,8.2-16.18,35.4-31.14,12.84-7.07,33.13-16.3,60.51-21.29.78,5.34,3.28,17.1,12.83,27.25,14.7,15.63,34.89,15.55,40.99,15.52,6.88-.03,19.34-.08,30.94-8.8,15.76-11.84,17.74-30.48,18.05-33.98,25.06,4.67,44.1,12.85,56.58,19.32,18.19,9.43,34.55,18.2,41.4,36.02.29.75.54,1.48.78,2.27,2.59,8.65,2.49,15.67,2.44,18.2-.14,7.81.78,27.7,2.12,55.46-103.44-.06-206.87-.12-310.31-.17Z" />
                                <path class="cls-2" d="M172.92,340.75c.31.51,7.29,11.48,20.39,11.27,12.63-.2,19.23-10.59,19.64-11.27" />
                                <path class="cls-2" d="M278.81,309.8c-.47,2.17-3.16,13.09-13.33,16.85-2.03.76-4.54,1.29-7.09,1.36-10.58,30.4-34.14,59.91-65.52,59.91s-54.96-29.87-65.38-59.89c-2.75-.01-5.48-.54-7.67-1.37-10.17-3.77-12.85-14.69-13.33-16.85-1.75-8.15.05-20.42,7.29-23.01,2.37-.84,4.84-.47,7.21.54.42-12.65,3.62-30.54,16.96-46.48,3.51-4.19,23.08-26.64,54.92-26.33,32.72.31,52.18,24.43,56.49,30.17,11.24,14.96,14.43,30.97,15.11,42.58,2.32-.96,4.73-1.3,7.05-.47,7.24,2.59,9.04,14.86,7.29,23.01Z" />
                                <path class="cls-2" d="M458.43,338.04c-.43,3.59-.32,8.52,2.18,13.26,5.15,9.81,18.45,15.04,33.07,12.39-5.05,2.43-15.41,6.5-28.34,4.94-4.94-.6-12.12-1.47-18.89-6.83-8.97-7.1-11.3-17.39-11.89-22.92h-72.09c-.59,5.53-2.92,15.82-11.88,22.92-6.78,5.35-13.95,6.23-18.89,6.83-12.93,1.56-23.29-2.51-28.34-4.94,14.6,2.65,27.92-2.58,33.07-12.39,2.65-5.04,2.61-10.29,2.09-13.92.01-.03.02-.07.03-.1-20.27-5.43-35.19-23.92-35.19-45.89v-31.09c0-26.23,21.27-47.49,47.49-47.49h98.64c26.24,0,47.49,21.27,47.49,47.49v31.09c0,23.16-16.58,42.46-38.53,46.65Z" />
                                <line class="cls-2" x1="342.15" y1="258.36" x2="451.16" y2="258.36" />
                                <line class="cls-2" x1="342.15" y1="292.86" x2="451.16" y2="292.86" />
                            </svg>
                        </div>
                        <div
                            class="min-h-12 w-full border border-solid border-2 border-red-500  rounded-md text-center"
                            data-field="keypoint-text">Copy patient URL</div>
                    </div>
                </div>
                <div
                    class="self-auto relative grid w-48 min-h-48 border border-solid border-2 border-gray-500 rounded-md ">
                    <div class="col-span-full m-0 m-0 p-2">
                        <div
                            class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-printer object-contain h-32" viewBox="0 0 16 16">
                                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1" />
                                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1" />
                            </svg>
                        </div>
                        <div
                            class="min-h-12 w-full border border-solid border-2 border-red-500  rounded-md text-center"
                            data-field="keypoint-text">Print version</div>
                    </div>
                </div>
                <div
                    class="self-auto relative grid w-48 min-h-48 border border-solid border-2 border-gray-500 rounded-md ">
                    <div class="col-span-full m-0 m-0 p-2">
                        <div
                            class="h-32 w-full border border-solid border-2 border-red-500  rounded-md mb-2 flex items-center justify-center">


                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-qr-code h-32" viewBox="0 0 16 16">
                                <path d="M2 2h2v2H2z" />
                                <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z" />
                                <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z" />
                                <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z" />
                                <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z" />
                            </svg>
                        </div>
                        <div
                            class="min-h-12 w-full border border-solid border-2 border-red-500  rounded-md text-center"
                            data-field="keypoint-text">Patient QR code</div>
                    </div>
                </div>
            </div>
            <div class=" content-center text-center w-full border border-solid border-2 border-purple-500">

                <input type="text" id="patient_contact" name="patient_contact"
                    class="bg-gray-50 border border-gray-300 text-gray-900 px-2 my-4 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Enter phone number or email" required>
                <button role="button" type="button" onclick="window.sendPublicInfo()"
                    class="w-full mb-2 disabled:text-gray-500 text-white bg-green-500 disabled:bg-blue-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Send to patient</button>

                <div class="space-y-4">
                    <div>
                        <button role="button" type="button" onclick="window.closePublicDetailsModal()"
                            class=" disabled:text-gray-500 text-white bg-blue-700 disabled:bg-blue-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Close</button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
