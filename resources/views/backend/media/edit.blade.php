@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Asset editor'))

@section('content')
    {{ Breadcrumbs::render() }}
    <div class="container mx-auto ">
        <form action="{{ route('admin.media.update', $media->id) }}" method="POST" enctype="multipart/form-data"
            id="updateForm" class="group">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label class="block text-sm/6 font-medium text-gray-900">Preview</label>
                            <div class="mt-2">
                                @if (Illuminate\Support\Str::contains($media->type, 'image'))
                                    <img src="{{ $media->path }}" alt="{{ $media->name }}" class="h-32 w-32 object-cover">
                                @endif
                                @if (Illuminate\Support\Str::contains($media->type, 'audio'))
                                    <audio controls>
                                        <source src="{{ $media->path }}" type="{{ $media->type }}">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif

                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Media filename</label>
                            <div class="mt-2">
                                <div class="flex items-center rounded-md bg-white  focus-within:outline-indigo-600">
                                    <input required type="text" name="name" id="name" value="{{ $media->name }}"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                        placeholder="name">
                                </div>

                            </div>
                        </div>
                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                            <div class="mt-2">
                                <textarea name="description" rows="3" required
                                    class="bg-slate-200 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">{{ $media->description }}</textarea>
                            </div>
                            <p class="mt-3 text-sm/6 text-gray-600">Description</p>
                        </div>
                        <div class="col-span-full">
                            <label for="tags" class="block text-sm/6 font-medium text-gray-900">Tags</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="off" multiple>
                                    <option value="">None</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-span-full border-t border-gray-600 mt-2">
                            <h2 class="text-l font-bold ">Files</h2>
                            <div class="col-span-full ">
                                <div class="row ">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="file" class="block font-medium">Update file</label>
                                            <input
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                name="file" type="file">
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>



                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <a href="{{ route('admin.media.index') }}" type="button"
                            class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 ">Save</button>

                    </div>
        </form>
    </div>
@endsection


@section('modals')
    {{-- Modals here --}}
@endsection

@push('after-scripts')
    <script>
        const clip = {{ $media->id }};

        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
        var tags = [
            @if (isset($media))
                @foreach ($media->tags as $tag)
                    {
                        value: "{{ $tag->id }}",
                        text: "{{ $tag->text }}"
                    },
                @endforeach
            @endif
        ]
    </script>
    {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
    @vite('resources/js/backend/media/edit.js')
@endpush
