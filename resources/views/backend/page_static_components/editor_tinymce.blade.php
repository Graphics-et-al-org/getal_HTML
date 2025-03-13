@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Page static component editor'))

@section('content')
    <div class="w-100 mb-2">
        {{ isset($component->id) ? 'Update' : 'New' }} page static component
    </div>
    <div class="w-100">
        <form id="storeForm"
            action="{{ isset($component->id) ? route('admin.page_static_component.update', $component->id) : route('admin.page_static_component.store') }}"
            method="POST">
            @if (isset($component))
                {{ method_field('PATCH') }}
            @endif
            @csrf
            <input type="hidden" name="id" value="{{ $component->id ?? '-1' }}" />
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Label</label>
                    <input type="text" name="label" id="label"
                        value="{{ $component->label ?? 'New static component' }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
                <div class="sm:col-span-2">
                    <label for="description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Write a description...">{{ $component->description ?? '' }}</textarea>
                </div>
                <div class="col-span-full flex">
                    <div class="mr-2">
                        <label for="tags" class="block text-sm/6 font-medium text-gray-900">Tags</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="off" multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="col-span-full flex">
                    <div class="flex-1">
                        <label for="weight" class="block text-sm/6 font-medium text-gray-900">Weighting (order in
                            page)</label>
                        <div class="mt-2 flex items-center gap-x-3">
                            <input type="number" name="weight" value="{{   isset($component)?$component->weight ?? '0':'0' }}"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                        </div>
                    </div>
                </div>
                <div class="col-span-full flex">
                    <div class="flex-1">
                        <div class="pretty p-svg p-curve p-smooth p-bigger">
                            <input type="checkbox" name="keypoint" value="true" {{  isset($component)?$component->keypoint?"checked":"":"" }}/>
                            <div class="state p-info">
                                <svg class="svg svg-icon" viewBox="0 0 20 20">
                                    <path
                                        d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                        style="stroke: white;fill:white;"></path>
                                </svg>
                                <label>Keypoint?</label>
                            </div>
                        </div>

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
        <a href="{{ route('admin.page_static_components.index') }}" type="button"
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"><-
                Back</a>
                <button type="button" onclick="window.save()"
                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Save</button>
    </footer>

@endsection


@section('modals')

@endsection

@push('after-scripts')
    <script>
        const component_id = {{ $component->id ?? -1 }}
        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";



        var tags = [
            @if (isset($component))
                @foreach ($component->tags as $tag)
                    {
                        value: "{{ $tag->id }}",
                        text: "{{ $tag->text }}"
                    },
                @endforeach
            @endif
        ]

        var editor;
    </script>

    @vite('resources/js/backend/page_static_components/builder_tinymce.js')

    {{-- CSS goes *after* js --}}
    @vite('resources/css/backend/template_builder/builder.css')
@endpush
