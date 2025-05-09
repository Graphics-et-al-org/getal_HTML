@extends('backend.layouts.app_nosidebar')

@section('title', __('Create Local User'))

@section('content')
{{ Breadcrumbs::render() }}
    <div class="container mx-auto p-4 ">
        <h1 class="text-2xl font-bold mb-4">Create user</h1>
        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="group">
            @csrf

            <form>
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="John" required />
                    </div>


                </div>
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email
                        address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="john.doe@company.com" required />
                </div>
                <div class="mb-6">
                    <label for="type_radio" class="block text-sm/6 font-medium text-gray-900">Type</label>
                    <div class="col-md-10">
                        <div class="form-check form-control-lg">
                            <div class="pretty p-default p-curve  p-smooth p-bigger">
                                <input type="radio" name="type_radio" value="local" />
                                <div class="state  p-info-o">
                                    <label>Local</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-check form-control-lg">
                            <div class="form-check form-control-lg">
                                <div class="pretty p-default p-curve  p-smooth p-bigger">
                                    <input type="radio" name="type_radio" value="provider" />
                                    <div class="state  p-info-o">
                                        <label>Provider</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="grid gap-6 mb-6 md:grid-cols-2 hidden providerfield">
                    <div>
                        <label for="provider" class="block mb-2 text-sm font-medium ">Provider</label>
                        <input type="text" id="provider" name="provider" value="auth0" readonly
                            class="text-gray-400 bg-gray-100 border border-gray-300  text-sm rounded-lg  block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white "
                            placeholder="Provider"  />
                    </div>
                </div>
                <div class="grid gap-6 mb-6 md:grid-cols-2 hidden providerfield">
                    <div>
                        <label for="provider_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Provider ID</label>
                        <input type="text" id="provider_id" name="provider_id" value="{{ old('provider_id') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Auth0 user ID"  />
                    </div>
                </div>

                <div class="mb-6 hidden localfield">
                    <label for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" id="password" name="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="•••••••••" />
                </div>
                <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User team(s)</span>
                <div class="flex flex-wrap justify-start mb-4">
                    <select id="teams" multiple name="teams[]" data-placeholder="Select teams for this user..."
                        autocomplete="on" class="block appearance-none w-full">
                        <option value="">None</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->getKey() }}"
                                {{ in_array($team->getKey(), old('teams') ?? []) ? 'selected' : '' }}>
                                {{ $team->display_name ?? $team->name }}</option>
                        @endforeach
                    </select>
                </div>
                  <span class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Global roles</span>
                <div class="flex flex-wrap justify-start mb-4">
                    <select id="roles" multiple name="roles[]" data-placeholder="Select roles for this user..." autocomplete="on" class="block appearance-none w-full">
                        <option value="">None</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->getKey() }}" {{ in_array($role->getKey(), old('roles') ?? []) ? 'selected' : '' }}>
                                {{ $role->display_name ?? $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                    <a href="{{ route('admin.users.index') }}" type="button" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
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
