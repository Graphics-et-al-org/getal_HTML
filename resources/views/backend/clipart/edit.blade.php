@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Template editor'))

@section('content')
{{-- Content goes here --}}
@endsection


@section('modals')
{{-- Modals here --}}
@endsection

@push('after-scripts')
    <script>
        const clip = {{ $page->id }}
        const baseurl = '{{ URL::to('/') }}';
        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
    </script>
@endpush
