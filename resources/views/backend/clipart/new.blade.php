@extends('backend.layouts.app_nosidebar')

@section('title', __('Create Asset'))

@section('content')
    <div class="container mx-auto p-4 ">
        <h1 class="text-2xl font-bold mb-4">Create Asset</h1>
        <form action="{{ route('admin.clipart.store') }}" method="POST" enctype="multipart/form-data" class="group">
            @csrf
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Asset name</label>
                            <div class="mt-2">
                                <div class="flex items-center rounded-md bg-white  focus-within:outline-indigo-600">
                                    <input required type="text" name="name" id="name"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                        placeholder="name">
                                </div>

                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                            <div class="mt-2">
                                <textarea name="description" id="description" rows="3"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"></textarea>
                            </div>
                            <p class="mt-3 text-sm/6 text-gray-600">Write a few sentences about the asset.</p>
                        </div>

                        <div class="col-span-full">
                            <label for="tags" class="block text-sm/6 font-medium text-gray-900">Tags</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="off" multiple>
                                    <option value="">None</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="type_radio" class="block text-sm/6 font-medium text-gray-900">Type</label>
                            <div class="col-md-10">
                                <div class="form-check form-control-lg">
                                    <input class="form-check-input " type="radio" name="type_radio" id="exampleRadios1"
                                        value="svg" checked>
                                    <label class="form-check-label" for="exampleRadios1">
                                        SVG
                                    </label>
                                </div>
                                <div class="form-check form-control-lg">
                                    <input class="form-check-input " type="radio" name="type_radio" id="exampleRadios1"
                                        value="bodymovin">
                                    <label class="form-check-label" for="exampleRadios1">
                                        Animation
                                    </label>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold ">Files</h2>
                        @foreach ($colourway_colours as $colourway_colour)
                            <div class="col-span-full">
                                <div class="row ">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="colourway_{{ $colourway_colour->id }}" class="block font-medium"
                                                style="color: {{ $colourway_colour->colour_code }}">{{ $colourway_colour->name }}</label>
                                            <input required
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                name="colourway_{{ $colourway_colour->id }}" type="file">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        @endforeach


                    </div>




                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <a href="{{ route('admin.clipart.index') }}" type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 " >Save</button>
                    </div>
        </form>
    </div>
@endsection

@push('after-scripts')
    <script>
        const baseurl = '{{ URL::to('/') }}';
    </script>

    @vite('resources/js/backend/clipart/create.js')
@endpush
