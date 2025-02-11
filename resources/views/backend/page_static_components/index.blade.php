@extends('backend.layouts.app')

@section('title', __('Page static components management'))

@section('content')

<div><h5 class="text-xl dark:text-white">Page static components admin</h5></div>
<div class="inline-flex rounded-md shadow-sm">

    <a href="{{ route('admin.page_static_component.create') }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
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
                    Tags
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
            @foreach ($components as $component )
            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $component->label }}
                </th>
                <td class="px-6 py-4">
                    {{ $component->user->name }}
                </td>
                <td class="px-6 py-4">
                    {{ $component->tags->implode('text',', ') }}
                </td>
                <td class="px-6 py-4">
                    {{ $component->created_at }}
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.page_static_component.edit', $component->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                    <br/>
                    <a href="{{ route('admin.page_static_component.edit', $component->id) }}" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</a>
                </td>
            </tr>
           @endforeach
        </tbody>
    </table>
</div>
</div>

@endsection
