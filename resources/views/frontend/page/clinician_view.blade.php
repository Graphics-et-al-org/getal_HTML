@extends('frontend.layouts.clinician_view_layout')

@section('title', 'Clinician View')

@section('content')





    <main class="p-4 h-auto pt-20">
        {{-- Widget wrapper --}}
        <div class="container mx-auto">
            {{-- Render the HTML --}}
            {!! $html !!}
            <div class="flex justify-center mt-4 w-full">
                <button id="addKeypointButton" type="button"
                    class="inline-flex w-full justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 border border-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:border-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    onclick="window.openAddCollectionModal()">
                    Add information from our database
                </button>
            </div>
            <div class="flex justify-center mt-4 w-full">
                <button id="addKeypointButton" type="button"
                    class="inline-flex w-full justify-center items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 border border-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:border-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                    onclick="window.showWarning()">
                    Save and generate link
                </button>
            </div>
        </div>
        {{-- Button to add snippets --}}


    </main>
    {{-- Interactivity --}}
    @push('after-scripts')
        <script>
            const uuid = '{{ $page->uuid ?? -1 }}';
            const baseurl = '{{ URL::to('/') }}';
            var used_images = [
                @foreach ($used_icons as $used_icon)
                    {{ $used_icon }},
                @endforeach
            ]
        </script>
        @vite('resources/js/frontend/clinician_page/clinician_page.js')
    @endpush
@endsection
@section('modals')
    <div id="addKeypointModal" role="dialog" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        @include('frontend.page.form.add_keypoint')
    </div>

    <div id="addCollectionModal" role="dialog" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        @include('frontend.page.form.add_collection')
    </div>

    <div id="publicDetailsModal" role="dialog" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    @include('frontend.page.form.public_info')
</div>
@endsection
