@extends('backend.layouts.app')

@section('title', __('Template management'))

@section('content')

<div><h5 class="text-xl dark:text-white">Templates admin</h5></div>
<div class="inline-flex rounded-md shadow-sm m-2">

    <a href="{{ route('admin.template.create') }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
      New
    </a>

  </div>
  <div class="col m-2">


    <form action="{{ route('admin.templates.index') }}" class="m-0" method="GET">

        <div class="flex">
            <div class="flex-initial min-w-80  mr-2">
                <div class="w-full">
                    <label for="tags">Tags</label>
                </div>
                <div class="w-full">
                    <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="on" multiple>
                        <option value="">None</option>
                    </select>
                </div>
            </div>


            <div class="flex-initial min-w-80">
                <div class="w-full">
                    <label for="search">Search label/description</label>
                </div>

                <input type="text" name="search" id="search" value = "{{ session('admin_templates_search') }}"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                    placeholder="name">

            </div>

            <div class="flex-initial flex items-end ml-2">
                <button type="submit"
                    class="text-green-700  mb-0 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800">
                    Search
                </button>
            </div>
        </div>
    </form>
</div>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg m-2">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                   Label
                </th>
                <th scope="col" class="px-6 py-3">
                    Description
                 </th>
                 <th scope="col" class="px-6 py-3">
                    Tags
                 </th>
                <th scope="col" class="px-6 py-3">
                    Owner
                </th>
                <th scope="col" class="px-6 py-3">
                    Team
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
                <th  class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $template->label }}
                </th>
                <td  class="px-6 py-4 ">
                    {{ $template->description }}
                </td>
                <td  class="px-6 py-4 ">
                    {{ $template->tags?$template->tags->implode('text', ', '):"" }}
                </td>
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
                    <br/>
                    <a href="{{ route('admin.template.destroy', $template->id) }}"
                        class="font-medium text-red-600 dark:text-blue-500 hover:underline">Delete</a>
                </td>
            </tr>
           @endforeach
        </tbody>
    </table>
</div>


@endsection
@push('after-scripts')
    <script>
        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
        var tags = [
            @if (session('admin_templates_tags'))

                @foreach (session('admin_templates_tags') as $tag)
                    {

                        value: "{{ $tag }}",
                        text: "{{ App\Models\Tag::find($tag)->text }}"
                    },
                @endforeach
            @endif
        ]
    </script>
    {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
    @vite('resources/js/backend/clipart/index.js')
@endpush
