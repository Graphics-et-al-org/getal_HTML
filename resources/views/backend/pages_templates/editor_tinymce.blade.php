@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Template editor'))

@section('content')
    <div class="w-100 mb-2">
        New template
    </div>
    <div class="w-100">
        <form id="storeForm"
            @if (isset($page->id)) action="{{ route('admin.template.update', $page->id) }}"
          @else
          action="{{ route('admin.template.store') }}" @endif
            method="POST">
            @if (isset($page->id))
                @method('PATCH')
            @endif

            @csrf
            <input type="hidden" name="id" value="{{ $page->id ?? '-1' }}" />
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Label</label>
                    <input type="text" name="label" id="label" value="{{ $page->label ?? 'New Template' }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Write a description...">{{ $page->description ?? '' }}</textarea>
                </div>
                <div class="col-span-full">
                    <label for="tags" class="block text-sm/6 font-medium text-gray-900">Tags</label>
                    <div class="mt-2 flex items-center gap-x-3">
                        <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="off" multiple>
                            <option value="">None</option>
                        </select>
                    </div>
                </div>
        </form>
    </div>
    <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Content</label>
    <div class="w-100 mb-16">
        {{-- Tinymce editor --}}
        <div id="tinymce">
        </div>
    </div>
    <footer class="fixed bottom-0 left-0 z-20 w-full bg-gray-200">
        <button type="button"
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"><-
                Back</button>
                <button type="button" onclick="window.save()"
                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Save</button>
    </footer>
    @endsection @section('modals') @endsection @push('after-scripts')
    <script>
        const page_id = {{ $page->id ?? -1 }}
        const baseurl = '{{ URL::to('/') }}';


        var editor;
        var tags = [
            @if (isset($page))
                @foreach ($page->tags as $tag)
                    {
                        value: "{{ $tag->id }}",
                        text: "{{ $tag->text }}"
                    },
                @endforeach
            @endif
        ]

      

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
    </script>
    {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
    @vite('resources/js/backend/template_builder/builder_tinymce.js')

    {{-- CSS goes *after* js --}}
    @vite('resources/css/backend/template_builder/builder.css')
@endpush
