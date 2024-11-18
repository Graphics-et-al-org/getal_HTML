@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    @vite('resources/css/backend/template_builder/builder.css')
@endpush

@section('title', __('Template editor'))

@section('content')
    <div id="builder-container">
        <div class="panel__top">
            <div class="panel__basic-actions"></div>
        </div>
        <div class="editor-row">
        <div class="editor-canvas">
            <div id="gjs">...</div>
        </div>
        </div>
        <div id="blocks"></div>
    </div>


@endsection

@push('after-scripts')
    @vite('resources/js/backend/template_builder/builder.js')
    {{-- CSS goes *after* js --}}
    @vite('resources/css/backend/template_builder/builder.css')
@endpush
