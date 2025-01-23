@extends('backend.layouts.app')

@section('title', __('Team management'))

@section('content')

    <div>
        <h5 class="text-xl dark:text-white">Teams admin</h5>
    </div>
    <div class="inline-flex rounded-md shadow-sm w-100">
        <a href="{{ route('admin.teams.new') }}"
            class="px-4 py-2 m-4 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            New
        </a>
    </div>
    {{ $teams->onEachSide(5)->links() }}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Team name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Team display name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Members count

                    </th>

                    <th scope="col" class="px-6 py-3">
                        Created at
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Last updated
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teams as $team)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row" class="px-6 py-4">
                            {{ $team->name }}
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $team->display_name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $team->users->count() }}
                        </td>
                        <td class="px-6 py-4">
                            {{$team->created_at }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $team->updated_at }}
                        </td>
                        <td class="px-6 py-4">

                            <a href="{{ route('admin.teams.edit', $team) }}"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Edit</a>
                                <a href="{{ route('admin.teams.membership', $team) }}"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-600">Membership</a>
                            <form action="{{ route('admin.teams.destroy', $team) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">Delete</button>
                            </form>


                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection

@section('modals')


@endsection
