@extends('backend.layouts.app')

@section('title', __('Asset management'))

@section('content')

    <div>
        <h5 class="text-xl dark:text-white">Media admin</h5>
    </div>
    <div class="flex rounded-md shadow-sm w-100">
        <a href="{{ route('admin.media.create') }}"
            class="px-4 py-2 m-4 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-800 dark:border-gray-700 dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-blue-500 dark:focus:text-white">
            New
        </a>

    </div>
    <div class="col m-2">


        <form action="{{ route('admin.media.index') }}" method="GET">

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

                    <input type="text" name="search" id="search" value = "{{ session('admin_media_search') }}"
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

        {{ $media->onEachSide(5)->links() }}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-2">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Preview
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Filename
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
                            Created at
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($media as $item)
                        <tr
                            class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <td scope="row" class="px-6 py-4">
                                @if ($item->thumb)
                                    <img src="{{ route('admin.media.thumb', [$item->uuid, '100']) }}"
                                        class="w-20 h-20 object-cover rounded-lg" alt="{{ $item->name }}">
                                @endif
                                  @if (Illuminate\Support\Str::contains($item->type, 'audio'))
                                    <audio controls>
                                        <source src="{{ $item->path }}" type="{{ $item->type }}">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif

                            </td>
                            <td scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $item->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->description }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->tags->implode('text', ', ') }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->type }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $item->created_at }}
                            </td>
                            <td class="px-6 py-4">
                                 <a href="#" onclick="window.copyText('{{ $item->path }}')"
                                    class="font-medium text-green-600 dark:text-green-500 hover:underline">Copy link</a>
                                <br />
                                <a href="{{ route('admin.media.edit', $item->id) }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                                <br />
                                <a href="#" onclick="window.deleteMedia('{{ $item->uuid }}')"
                                    class="font-medium text-red-600 dark:text-blue-500 hover:underline">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $media->onEachSide(5)->links() }}
        </div>
    @endsection

    @section('modals')

    @endsection

    @push('after-scripts')
        <script>
            const baseurl = '{{ URL::to('/') }}';

            const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
            var tags = [
                @if (session('admin_media_tags'))

                    @foreach (session('admin_media_tags') as $tag)
                        {

                            value: "{{ $tag }}",
                            text: "{{ App\Models\Tag::find($tag)->text }}"
                        },
                    @endforeach
                @endif
            ]
        </script>
        {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
        @vite('resources/js/backend/media/index.js')
    @endpush
