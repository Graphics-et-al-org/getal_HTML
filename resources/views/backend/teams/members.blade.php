@extends('backend.layouts.app_nosidebar')

@section('title', __('Team members'))

@section('content')
    <div class="container mx-auto p-4 ">

        <h1 class="text-2xl font-bold mb-4">Team members for {{ $team->name }}</h1>
        <div class="inline-flex rounded-md shadow-sm w-100">
            <a href="#" data-modal-toggle="addmembersdialog" data-modal-target="addmembersdialog"
                class="px-4 py-2 m-4 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
                Add
            </a>
        </div>
        {{ $members->onEachSide(5)->links() }}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Roles in team
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($members as $member)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td scope="row" class="px-6 py-4">
                                {{ $member->name }}

                            </td>
                            <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                @foreach ($member->roles()->wherePivot('team_id', $team->id)->get() as $role)
                                <span
                                    class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-blue-500 rounded-full dark:bg-blue-600">
                                    {{ $role->name }}
                                </span>

                            @endforeach
                            </td>

                            <td class="px-6 py-4">

                                <a href="{{ route('admin.teams.members.remove', ['id' => $team->id, 'userid' => $member->id]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Remove</a>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
@endsection

@push('after-scripts')
    <script>
        const baseurl = '{{ URL::to('/') }}';
    </script>
    @vite('resources/js/backend/team/members.js')
@endpush

@section('modals')
<div id="addmembersdialog" role="dialog" tabindex="-1" aria-hidden="true"
class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
@include('backend.teams.form.addmembers')

</div>

@endsection
