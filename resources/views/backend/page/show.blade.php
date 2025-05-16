@extends('frontend.layouts.clinician_view_layout')

@section('title', 'Clinician View')

@section('content')
<main class="p-4 h-auto pt-20">
     <!-- Google Translate bar (from snippet) -->

    <div class="container mx-auto">
        {{-- Render the HTML --}}
        {!! $html
         !!}
    </div>
    <div class="translate">
        <div id="google_translate_element"></div>
      </div>
  </main>
{{-- Interactivity --}}
@push('after-scripts')
 <!-- Google Translate JS -->

@vite('resources/js/frontend/clinician_page/clinician_page.js')
@endpush
@endsection
