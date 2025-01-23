<div class="relative p-4 w-full max-w-md max-h-full">
    <!-- Modal content -->
    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
        <!-- Modal header -->
        <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                Upload Bulk Clipart
            </h3>
            <button type="button"
                class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                data-modal-hide="addbulkclipartdialog">
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
            <form class="space-y-4" action="{{ route('admin.clipart.bulkimport') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Upload
                        file</label>
                    <input required
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        aria-describedby="user_avatar_help" id="zipfile" name="zipfile" type="file">
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-300">Upload a zip file
                        containing assets and metadata</div>

                </div>

                <button type="submit"
                    class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Upload...</button>

            </form>
        </div>
    </div>
</div>
{{-- <div class="row ">
    <div class="col">
        <div class="form-group">
            {{ html()->label('Zip')->for('zipfile')->attribute('style', 'color:blue; font-weight:bold') }}
            {{ html()->file('zipfile')->class('form-control') }}
        </div>
    </div>

</div>



<div class="row">
    <div class="col">
        <div class="form-group">
            {!!form_submit('Create','btn-block btn btn-success ') !!}
        </div>
    </div>
</div><!--row--> --}}
