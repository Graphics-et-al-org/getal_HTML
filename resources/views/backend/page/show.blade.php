@extends('frontend.layouts.clinician_view_layout')

@section('title', 'Clinician View')

@section('content')
<main class="p-4 h-auto pt-20">
    <div class="container mx-auto">
        {{-- Render the HTML --}}
        {!! $html
         !!}
    </div>
  </main>
{{-- Interactivity --}}
@push('after-scripts')
@vite('resources/js/frontend/clinician_page/clinician_page.js')
@endpush
@endsection
