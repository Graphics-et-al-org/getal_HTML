<div class="relative p-4 w-full max-w-md max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Add from our database
            </h3>
            <button type="button"
                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                onclick="window.closeAddCollectionModal()">
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

            <div class="flex-initial min-w-80 mb-4">
                <input type="text" name="search" id="collectionsearch" onkeyup="window.handleCollectionTextEntry()"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    placeholder="Type to search...">

            </div>
            {{-- Selection here --}}
            <div class="min-w-80 mb-4" >
                <ul class="w-full flex " id="collectionslist">
                </ul>
            </div>
            {{-- <div class="min-w-80 mb-4 bg-gray-100 border border-solid border-2 border-gray-500 rounded-md flex"
                id="selected_categories">
                <div id="placeholder" class="w-full">No items selected</div>

            </div> --}}
            <div class="space-y-4">
                <div>
                    <button role="button" type="button" onclick="window.addSelectedCollections()"
                        class="w-full text-white bg-blue-700 disabled:bg-blue-300 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>
