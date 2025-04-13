@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
    <style>
        .cm-editor {
            min-height: 400px;
            width: 100%
        }

        /* .ts-control {
                    min-width: 300px;
                } */
    </style>
@endpush

@section('title', __('Template component editor'))

@section('content')
    <div class="w-full mb-2 text-lg">
        {{ $component->label ?? 'New Template component' }}
    </div>
    <div class="w-100">
        <form id="storeForm"
            @if (isset($component->id)) action="{{ route('admin.template.component.update', $component->id) }}"
          @else
          action="{{ route('admin.template.component.store') }}" @endif
            method="POST">
            @if (isset($component->id))
                @method('PATCH')
            @endif

            @csrf
            <input type="hidden" name="id" value="{{ $component->id ?? '-1' }}" />
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="label" class="block mb-2 font-medium text-gray-900 dark:text-white">Label</label>
                    <input type="text" name="label" id="label" value="{{ $component->label ?? 'New Template Component' }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>

                <div class="sm:col-span-2">
                    <label for="description"
                        class="block mb-2  font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Write a description...">{{ $component->description ?? '' }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block mb-2  font-medium text-gray-900 dark:text-white">Type</label>
                    <div class="col-md-10">
                        <div class="form-check form-control-lg">
                            <div class="pretty p-default p-curve  p-smooth p-bigger">
                                <input type="radio" name="component_type" value="keypoints"
                                    @if (isset($component)) {{ $component->type == 'keypoints' ? 'checked' : '' }} @endif />
                                <div class="state  p-info-o">
                                    <label>Keypoint container</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-control-lg">
                            <div class="form-check form-control-lg">
                                <div class="pretty p-default p-curve  p-smooth p-bigger">
                                    <input type="radio" name="component_type" value="snippets"
                                        @if (isset($component)) {{ $component->type == 'snippets' ? 'checked' : '' }} @endif />
                                    <div class="state  p-info-o">
                                        <label>Snippets container</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-control-lg">
                            <div class="form-check form-control-lg">
                                <div class="pretty p-default p-curve  p-smooth p-bigger">
                                    <input type="radio" name="component_type" value="html"
                                        @if (isset($component)) {{ $component->type == 'html' ? 'checked' : '' }} @endif />
                                    <div class="state  p-info-o">
                                        <label>HTML container</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-full flex">
                    <div class="mr-2 min-w-80">
                        <label for="tags" class="block  font-medium text-gray-900">Tags</label>
                        <div class="mt-2  items-center gap-x-3 w-full ">
                            <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="off" multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-span-full block  font-medium text-gray-900">
                    Restrictions
                </div>
                <div class="col-span-full flex">
                    <div class="mr-2 min-w-80">
                        <label for="teams" class="block text-sm/6 font-medium text-gray-900">Restrict to team(s)</label>
                        <div class="mt-2 items-center gap-x-3  w-full ">
                            <select id="teams" name="teams[]" data-placeholder="Teams" autocomplete="off" multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                    <div class="min-w-80">
                        <label for="users" class="block text-sm/6 font-medium text-gray-900">Restrict to user(s)</label>
                        <div class="mt-2 mr-2  items-center gap-x-3 w-full ">
                            <select id="users" name="users[]" data-placeholder="Users" autocomplete="off" multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                    <div class="min-w-80">
                        <label for="projects" class="block text-sm/6 font-medium text-gray-900">Restrict to
                            project(s)</label>
                        <div class="mt-2 items-center gap-x-3 w-full ">
                            <select id="projects" name="projects[]" data-placeholder="Projects" autocomplete="off"
                                multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <label class="block mb-2 font-medium text-gray-900 dark:text-white">Content</label>
    <div class="w-100 mb-2">
        {{-- Tinymce editor --}}
        <div id="tinymce_content">
        </div>
    </div>

    <footer class="fixed bottom-0 left-0 w-full bg-gray-200">
        <a href="{{ route('admin.template.components.index') }}" type="button"
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"><-
                Back</a>
                <button type="button" onclick="window.save()"
                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Save</button>

    </footer>
@endsection
@section('modals')
    <!-- Extra Large Modal -->
    <div id="html-editor-modal" aria-hidden="true" tabindex="-1"
        class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-full max-h-full">
        <div class="relative w-full max-w-7xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                <!-- Modal header -->
                <div
                    class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                    <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                        Edit html
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div id="codemirror-container" class="p-4 md:p-5 space-y-4 min-h-full flex overflow-auto">

                </div>
                <!-- Modal footer -->
                <div
                    class="flex items-center p-4 md:p-5 space-x-3 rtl:space-x-reverse border-t border-gray-200 rounded-b dark:border-gray-600">
                    <button type="button" onclick="window.closeModal()"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Close</button>

                </div>
            </div>
        </div>
</div> @endsection
@push('after-scripts')
    <script>
        const component_id = {{ $component->id ?? -1 }}
        const baseurl = '{{ URL::to('/') }}';


        var editor;
        var tags = [
            @if (isset($component))
                @if (isset($component->tags))
                    @foreach ($component->tags as $tag)
                        {
                            value: "{{ $tag->id }}",
                            text: "{{ $tag->text }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var users = [
            @if (isset($component))
                @if (isset($component->users))
                    @foreach ($component->users as $user)
                        {
                            value: "{{ $user->id }}",
                            text: "{{ $user->name }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var teams = [
            @if (isset($component))
                @if (isset($component->teams))
                    @foreach ($component->teams as $team)
                        {
                            value: "{{ $team->id }}",
                            text: "{{ $team->display_name }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var projects = [
            @if (isset($component))
                @if (isset($component->projects))
                    @foreach ($component->projects as $project)
                        {
                            value: "{{ $project->id }}",
                            text: "{{ $project->label }}"
                        },
                    @endforeach
                @endif
            @endif
        ]



        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
    </script>
    {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
    @vite('resources/js/backend/template_components/builder_tinymce.js')

    {{-- CSS goes *after* js --}}
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush
