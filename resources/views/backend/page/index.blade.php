@extends('backend.layouts.app')

@section('title', __('Generated Pages'))

@section('content')

    <div>
        <h5 class="text-xl dark:text-white">Generated pages admin</h5>
    </div>
    <div class="flex rounded-md shadow-sm w-100">

    </div>
    <div class="col m-2">

    </div>

    {{ $pages->onEachSide(5)->links() }}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-2">
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
                        Generated at
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Made public
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $item)
                    <tr
                        class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <td scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            <a href="{{ route('frontend.page.clinician_view', $item->uuid) }}">{{ $item->label }}</a>
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->created_at }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $item->released_at??'Not released' }}
                        </td>
                        <td class="px-6 py-4">

                            <a href="{{ route('admin.page.destroy', $item->id) }}"
                                class="font-medium text-red-600 dark:text-blue-500 hover:underline">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $pages->onEachSide(5)->links() }}
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
