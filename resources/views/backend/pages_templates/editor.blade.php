@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Template editor'))

@section('content')
<div class="panel__top">
    <div class="panel__basic-actions"></div>
  </div>
  <div id="gjs">
    <div className="container">
      <div className="row">
        <div className="col-md-6 ">This is a template!</div>
        <div className="col-md-6 ">
          <p>We'll refine this later</p>
        </div>
      </div>
    </div>
  </div>
  <div id="blocks"></div>

  <footer class="fixed bottom-0 left-0 z-20 w-full bg-gray-200">
        <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700"><- Back</button>
    <button type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Save</button>

    </span>

@endsection

@push('after-scripts')
<script>
    const page_id = {{ $page->id }}
    const baseurl = '{{ URL::to('/') }}';
    var page_pages =
    [
        @foreach($page->pagePages as $page_page)
        '{{ $page_page->uuid }}'
        ,
        @endforeach
    ];

    const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
    </script>
       {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
    @vite('resources/js/backend/template_builder/builder.js')

    {{-- CSS goes *after* js --}}
    @vite('resources/css/backend/template_builder/builder.css')
@endpush
