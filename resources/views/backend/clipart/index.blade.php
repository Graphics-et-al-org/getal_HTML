@extends('backend.layouts.app')

@section('title', __('Asset management'))

@section('content')

    <div>
        <h5 class="text-xl dark:text-white">Clipart admin</h5>
    </div>
    <div class="inline-flex rounded-md shadow-sm w-100">
        <a href="{{ route('admin.clipart.create') }}"
            class="px-4 py-2 m-4 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            New
        </a>
        <a href="#" data-modal-toggle="addbulkclipartdialog" data-modal-target="addbulkclipartdialog"
            class="px-4 py-2 m-4 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            Bulk upload
        </a>
    </div>
    <div class="col m-2">


        <form action="{{ route('admin.clipart.index') }}" method="GET">

            <div class="flex">
                <div class="flex-initial min-w-80 ml-2 mr-2">
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

                    <input type="text" name="search" id="search" value = "{{ session('admin_clipart_search') }}"
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

    {{ $clipart->onEachSide(5)->links() }}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-2">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Thumb
                    </th>
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
                        Type
                    </th>
                    <th scope="col" class="px-6 py-3">
                        GPT
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Owner
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
                @foreach ($clipart as $item)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row" class="px-6 py-4">
                            <img src="{{ route('frontend.clipart.thumb', [$item->id, 100, null]) }}"></img>
                        </td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $item->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->preferred_description }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->tags->implode('text', ', ') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->type }}
                        </td>
                        <td class="px-6 py-4">
                            {!! ((strlen($item->bert_text_embedding_b64)>0)&&(strlen($item->clip_image_embedding_b64)>0)&&(strlen($item->gpt4_description)>0))?'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#28a745" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                                <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                              </svg>':'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#dc3545" class="bi bi-x-square-fill" viewBox="0 0 16 16">
  <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708"/>
</svg>' !!}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->owner->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->created_at }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.clipart.edit', $item->id) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                            <br />
                            <a href="{{ route('admin.clipart.destroy', $item->id) }}"
                                class="font-medium text-red-600 dark:text-blue-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $clipart->onEachSide(5)->links() }}
    </div>
@endsection

@section('modals')
    <div id="addbulkclipartdialog" role="dialog" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        @include('backend.clipart.form.bulkclipart')

    </div>
@endsection

@push('after-scripts')
    <script>
        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
        var tags = [
            @if (session('admin_clipart_tags'))

                @foreach (session('admin_clipart_tags') as $tag)
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
