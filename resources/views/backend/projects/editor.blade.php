@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
    <style>
        .cm-editor {
            min-height: 600px;
            width: 100%
        }
    </style>
@endpush

@section('title', __('Project editor'))

@section('content')
    <div class="w-100 mb-2">
        {{ isset($project->id) ? 'Update' : 'New' }} project
    </div>
    <div class=" pr-2">
        <form id="storeForm"
            action="{{ isset($project->id) ? route('admin.projects.update', $project->id) : route('admin.projects.store') }}"
            method="POST">
            @if (isset($project->id))
                {{ method_field('PATCH') }}
            @endif
            @csrf
            <input type="hidden" name="id" value="{{ $project->id ?? '-1' }}" />
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Label</label>
                    <input type="text" name="label" id="label"
                        value="{{ $project->label ?? 'New project' }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
                <div class="sm:col-span-2">
                    <label for="description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Write a description...">{{ $project->description ?? '' }}</textarea>
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
                    <div class="mr-2 min-w-80">
                        <label for="users" class="block text-sm/6 font-medium text-gray-900">Restrict to user(s)</label>
                        <div class="mt-2 items-center gap-x-3 w-full ">
                            <select id="users" name="users[]" data-placeholder="Users" autocomplete="off" multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                </div>

        </form>
    </div>

    <footer class="fixed bottom-0 left-0 z-20 w-full bg-gray-200">
        <a href="{{ route('admin.snippets.index') }}" type="button"
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"><-
                Back</a>
                <button type="button" onclick="window.save()"
                    class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                    Save</button>
    </footer>

@endsection


@section('modals')
    <!-- Extra Large Modal -->

@endsection

@push('after-scripts')
    <script>
        const project_id = {{ $project->id ?? -1 }}
        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";


        var users = [
            @if (isset($project))
                @if (isset($project->users))
                    @foreach ($project->users as $user)
                        {
                            value: "{{ $user->id }}",
                            text: "{{ $user->name }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var teams = [
            @if (isset($project))
                @if (isset($project->teams))
                    @foreach ($project->teams as $team)
                        {
                            value: "{{ $team->id }}",
                            text: "{{ $team->display_name }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var editor;
    </script>

    @vite('resources/js/backend/project/edit.js')

@endpush
