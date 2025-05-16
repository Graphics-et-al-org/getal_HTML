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

@section('title', __('Page component collection editor'))

@section('content')
    <div class="w-100 mb-2">
        {{ isset($collection->id) ? 'Update' : 'New' }} page component collection
    </div>
    <div class=" pr-2">
        <form id="storeForm"
            action="{{ isset($collection->id) ? route('admin.snippet_collection.update', $collection->id) : route('admin.snippet_collection.store') }}"
            method="POST">
            @if (isset($collection->id))
                {{ method_field('PATCH') }}
            @endif
            @csrf
            <input type="hidden" name="id" value="{{ $collection->id ?? '-1' }}" />
            <div class="grid gap-4 mb-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="label" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Label</label>
                    <input type="text" name="label" id="label"
                        value="{{ $collection->label ?? 'New component category' }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
                <div class="sm:col-span-2">
                    <label for="description"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                    <textarea id="description" name="description" rows="5"
                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Write a description...">{{ $collection->description ?? '' }}</textarea>
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
                    <div class="min-w-80">
                        <label for="projects" class="block text-sm/6 font-medium text-gray-900">Restrict to project(s)</label>
                        <div class="mt-2 items-center gap-x-3 w-full ">
                            <select id="projects" name="projects[]" data-placeholder="projects" autocomplete="off" multiple>
                                <option value="">None</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-span-full flex">
                    <div class="mr-2 min-w-lg">
                        <label for="components" class="block text-sm/6 font-medium text-gray-900">Components</label>
                        <div class="flex w-full">
                            <div class="mt-2 items-center gap-x-3 w-80 ">
                                <select id="components">
                                    <option value="">None</option>
                                </select>
                            </div>
                            <button type="button" onclick="window.addRow(document.getElementById('components').value)"
                                class="mt-2 ml-2 text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-plus-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                    <path
                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                                </svg>&nbsp;Add</button>

                        </div>
                    </div>

                </div>
                <div class="col-span-full flex">
                    <div class="mr-2 w-full">
                        <label for="components" class="block text-sm/6 font-medium text-gray-900">Order</label>
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400"
                            id="list_table">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        Label
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Description
                                    </th>

                                    <th scope="col" class="px-6 py-3">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table_body">
                                @if (isset($collection->snippets))
                                    @foreach ($collection->snippets as $snippet)
                                        <tr data-id="{{ $snippet->id }}"
                                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                            <th scope="row"
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $snippet->label }}
                                            </th>
                                            <td class="px-6 py-4 ">
                                                {{ $snippet->description }}
                                            </td>

                                            <td class="px-6 py-4">
                                                <button type="button" onclick="window.deleteRow( {{ $snippet->id }})"
                                                    class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
                                                        <path
                                                            d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z" />
                                                        <path
                                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                                    </svg></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
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
        const category_id = {{ $collection->id ?? -1 }}
        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";


        var users = [
            @if (isset($collection))
                @if (isset($collection->users))
                    @foreach ($collection->users as $user)
                        {
                            value: "{{ $user->id }}",
                            text: "{{ $user->name }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var teams = [
            @if (isset($collection))
                @if (isset($collection->teams))
                    @foreach ($collection->teams as $team)
                        {
                            value: "{{ $team->id }}",
                            text: "{{ $team->display_name }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var projects = [
            @if (isset($collection))
                @if (isset($collection->projects))
                    @foreach ($collection->projects as $project)
                        {
                            value: "{{ $project->id }}",
                            text: "{{ $project->label }}"
                        },
                    @endforeach
                @endif
            @endif
        ]

        var editor;
    </script>

    @vite('resources/js/backend/snippets_collection/edit.js')

    {{-- CSS goes *after* js --}}
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush
