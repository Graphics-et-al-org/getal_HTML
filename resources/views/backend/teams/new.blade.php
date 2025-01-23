@extends('backend.layouts.app_nosidebar')

@section('title', __('Create Team'))

@section('content')
    <div class="container mx-auto p-4 ">
        <h1 class="text-2xl font-bold mb-4">Create team</h1>
        <form action="{{ route('admin.teams.store') }}" method="POST" enctype="multipart/form-data" class="group">
            @csrf

            <form>
                <div class=" mb-2">
                    <div>
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name (must be unique, used for API)</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="My team" required />
                    </div>

                </div>
                <div class=" mb-2">
                    <div>
                        <label for="display_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Display name</label>
                        <input type="text" id="display_name" name="display_name" value="{{ old('display_name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="My team display name" required />
                    </div>

                </div>
                <div class="mb-6">
                    <div class="col-span-full">
                        <label for="about" class="block text-sm/6 font-medium text-gray-900">Description</label>
                        <div class="mt-2">
                            <textarea name="description" id="description" rows="3" placeholder="Write a few sentences about the team."
                                class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                        </div>

                    </div>
                </div>
                <div class="mb-6">
                    <button type="submit"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    <a href="{{ route('admin.teams.index') }}" type="button"
                        class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                </div>
            </form>

        </form>
    </div>
@endsection

@push('after-scripts')
    <script>
        const baseurl = '{{ URL::to('/') }}';
    </script>
    @vite('resources/js/backend/user/create.js')
@endpush
