@extends('backend.layouts.app')

@section('title', __('Template management'))

@section('content')
<div><h5 class="text-xl dark:text-white">Templates admin</h5></div>

<div class="inline-flex rounded-md shadow-sm">

    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
      New
    </a>

  </div>

<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                   Label
                </th>
                <th scope="col" class="px-6 py-3">
                    Owner
                </th>
                <th scope="col" class="px-6 py-3">
                    Group
                </th>
                <th scope="col" class="px-6 py-3">
                    Created at
                </th>
                <th scope="col" class="px-6 py-3">
                    Action
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($templates as $template )
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $template->label }}
                </th>
                <td class="px-6 py-4">
                    {{ $template->user->name }}
                </td>
                <td class="px-6 py-4">
                    {{ $template->group }}
                </td>
                <td class="px-6 py-4">
                    {{ $template->created_at }}
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.template.edit', $template->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                </td>
            </tr>
           @endforeach
        </tbody>
    </table>
</div>


@endsection
