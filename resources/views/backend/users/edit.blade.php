@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Edit user'))

@section('content')
{{ Breadcrumbs::render() }}
    <div class="container mx-auto p-4 ">
        <h1 class="text-2xl font-bold mb-4">Update user</h1>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="group">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <form>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="first_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" id="first_name" name="name" value="{{ $user->name }}"
                            {{ isset($user->provider) ? 'disabled' : '' }}
                            class="disabled:bg-slate-100 disabled:text-slate-500 disabled:border-slate-200 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="John" required />
                    </div>


                </div>
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                        address</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}"
                        {{ isset($user->provider) ? 'disabled' : '' }}
                        class="disabled:bg-slate-100 disabled:text-slate-500 disabled:border-slate-200 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="john.doe@company.com" required />
                </div>
                @if (!isset($user->provider))
                    <div class="mb-6">
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" id="password" name="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="•••••••••" />
                    </div>
                @else
                <div class="grid gap-6 mb-6 md:grid-cols-2  providerfield">
                    <div>
                        <label for="provider" class="block mb-2 text-sm font-medium ">Provider</label>
                        <input type="text" id="provider" name="provider" value="{{ $user->provider }}" readonly
                            class="text-gray-400 bg-gray-100 border border-gray-300  text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                            placeholder="Provider"  />
                    </div>
                </div>
                <div class="grid gap-6 mb-6 md:grid-cols-2  providerfield">
                    <div>
                        <label for="provider_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provider ID</label>
                        <input type="text" id="provider_id" name="provider_id" value="{{ $user->provider_id }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Auth0 user ID"  />
                    </div>
                </div>
                @endif
                <span class="block mb-2 text-sm font-medium">Global roles</span>
                <div class="flex flex-wrap justify-start mb-4">

                    <select id="roles" multiple name="roles[]" data-placeholder="Select roles for this user..."
                        autocomplete="on" class="block appearance-none w-full">
                        <option value="">None</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->getKey() }}"
                                {{ in_array($role->getKey(), $user->roles?$user->roles->pluck('id')->toArray() : []) ? 'selected' : '' }}>
                                {{ $role->display_name ?? $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="block mb-2 text-sm font-medium">Teams</span>
                <div class="flex flex-wrap justify-start mb-4">
                    <select id="teams" multiple name="teams[]" data-placeholder="Select teams for this user..."
                        autocomplete="on" class="block appearance-none w-full">
                        <option value="">None</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->getKey() }}"
                                {{ in_array($team->getKey(), $user->teams?$user->teams->pluck('id')->toArray() : []) ? 'selected' : '' }}>
                                {{ $team->display_name ?? $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="block mb-2 text-sm font-medium">Projects</span>
                <div class="flex flex-wrap justify-start mb-4">
                    <select id="projects" multiple name="projects[]" data-placeholder="Select projects for this user..."
                        autocomplete="on" class="block appearance-none w-full">
                        <option value="">None</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->getKey() }}"
                                {{ in_array($project->getKey(),$user->projects->pluck('id')->toArray() ?? []) ? 'selected' : '' }}>
                                {{ $project->label ?? $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                <a href="{{ route('admin.users.index') }}" type="button"
                    class="text-sm/6 font-semibold text-gray-900">Cancel</a>
            </form>

        </form>
    </div>
@endsection


@section('modals')
    {{-- Modals here --}}
@endsection

@push('after-scripts')
    <script>
        const user = {{ $user->id }}
        const baseurl = '{{ URL::to('/') }}';
        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
    </script>
    @vite('resources/js/backend/user/create.js')
@endpush
