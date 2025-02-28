@extends('backend.layouts.app_nosidebar')

@section('meta')
@endsection
@push('before-styles')
    {{-- @vite('resources/css/backend/template_builder/builder.css') --}}
@endpush

@section('title', __('Asset editor'))

@section('content')
    {{ Breadcrumbs::render() }}
    <div class="container mx-auto ">
        <form action="{{ route('admin.clipart.update', $clipart->id) }}" method="POST" enctype="multipart/form-data" id="updateForm"
            class="group">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-2 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Asset name</label>
                            <div class="mt-2">
                                <div class="flex items-center rounded-md bg-white  focus-within:outline-indigo-600">
                                    <input required type="text" name="name" id="name"
                                        value="{{ $clipart->name }}"
                                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"
                                        placeholder="name">
                                </div>

                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Preferred
                                description</label>
                            <div class="mt-2">
                                <textarea name="preferred_description" rows="3"  required
                                    class="bg-slate-200 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">{{ $clipart->preferred_description }}</textarea>
                            </div>
                            <p class="mt-3 text-sm/6 text-gray-600">Description used if the 'Preferred' flag is set .</p>
                        </div>

                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Fallback
                                Description</label>
                            <div class="mt-2">
                                <textarea name="fallback_description"  rows="3"  required
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">{{ $clipart->fallback_description }}</textarea>
                            </div>
                            <p class="mt-3 text-sm/6 text-gray-600">The description used if the 'Fallback' flag is set.</p>
                        </div>

                        <div class="col-span-full">
                            <label for="tags" class="block text-sm/6 font-medium text-gray-900">Tags</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <select id="tags" name="tags[]" data-placeholder="Tags" autocomplete="off" multiple>
                                    <option value="">None</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="type_radio" class="block text-sm/6 font-medium text-gray-900">Type</label>
                            <div class="col-md-10">
                                <div class="form-check form-control-lg">
                                    <div class="pretty p-default p-curve  p-smooth p-bigger">
                                        <input type="radio" name="type_radio" value="svg" {{  $clipart->type=='svg'?"checked":"" }} />
                                        <div class="state  p-info-o">
                                            <label>SVG</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-check form-control-lg">
                                    <div class="form-check form-control-lg">
                                        <div class="pretty p-default p-curve  p-smooth p-bigger">
                                            <input type="radio" name="type_radio" value="bodymovin" />
                                            <div class="state  p-info-o">
                                                <label>Animation</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-span-full border-t border-gray-600 ">
                            <h2 class="text-xl font-bold mt-2  w-full">GPT parameters</h2>
                        </div>
                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">Flags</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <div class="pretty p-svg p-curve p-smooth p-bigger">
                                    <input type="checkbox" name="preferred" value="true" {{  $clipart->preferred?"checked":"" }}/>
                                    <div class="state p-info">
                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                            <path
                                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                style="stroke: white;fill:white;"></path>
                                        </svg>
                                        <label>Preferred flag</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full">

                            <div class="mt-2 flex items-center gap-x-3">
                                <div class="pretty p-svg p-curve p-smooth p-bigger">
                                    <input type="checkbox" name="fallback" value="true" {{  $clipart->fallback?"checked":"" }}/>
                                    <div class="state p-info">
                                        <svg class="svg svg-icon" viewBox="0 0 20 20">
                                            <path
                                                d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z"
                                                style="stroke: white;fill:white;"></path>
                                        </svg>
                                        <label>Fallback flag</label>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-span-full">
                            <label for="description" class="block text-sm/6 font-medium text-gray-900">GPT 4 Generated
                                description (read only)</label>
                            <div class="mt-2">
                                <div id="gpt4_description"
                                    class="block w-full rounded-md bg-gray-100  py-16 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                    {{ $clipart->gpt4_description ?? 'not set, please generate' }}
                                </div>

                            </div>
                        </div>

                        <div class="col-span-full">
                            <div for="bert_text_embedding_b64" class="block text-sm/6 font-medium text-gray-900">BERT text
                                embedding</div>
                            <span class="mt-2 flex items-center">
                                {!! strlen($clipart->bert_text_embedding_b64)>0  ?'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#28a745" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                                  </svg>':'not set, please generate' !!}
                                {{-- <div class="flex items-center rounded-md bg-white  focus-within:outline-indigo-600">

                                    <div
                                        class="block w-full break-all rounded-md bg-gray-100  py-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        {{ $clipart->bert_text_embedding_b64 ?? 'not set, please generate' }}
                                    </div>

                                </div> --}}
                            </span>
                        </div>
                        <div class="col-span-full">
                            <div for="clip_image_embedding_b64" class="block text-sm/6 font-medium text-gray-900">CLIP
                                image embedding</div>
                            <div class="mt-2 flex items-center">
                                {!! strlen($clipart->clip_image_embedding_b64)>0  ?'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#28a745" class="bi bi-check-square-fill" viewBox="0 0 16 16">
                                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm10.03 4.97a.75.75 0 0 1 .011 1.05l-3.992 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.75.75 0 0 1 1.08-.022z"/>
                                  </svg>':'not set, please generate' !!}
                                {{-- <div class="flex items-center rounded-md bg-white  focus-within:outline-indigo-600">
                                    <div
                                        class="block w-full break-all rounded-md bg-gray-100  py-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                                        {{ $clipart->clip_image_embedding_b64 ?? 'not set, please generate' }}
                                    </div>

                                </div> --}}

                            </div>
                        </div>

                        <div class="col-span-full border-t border-gray-600 ">
                            <h2 class="text-xl font-bold ">Files</h2>
                        </div>

                        @foreach ($colourway_colours as $colourway_colour)
                            <div class="col-span-full">
                                <div class="row ">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="colourway_{{ $colourway_colour->id }}" class="block font-medium"
                                                style="color: {{ $colourway_colour->colour_code }}">{{ $colourway_colour->name }}</label>
                                            @if ($clipart->colourways->where('colour_id', $colourway_colour->id)->first())
                                                {{-- @if ($clipart->type == 'svg') --}}
                                                <img src="{{ $clipart->colourways->where('colour_id', $colourway_colour->id)->first()->path() }}"
                                                    alt="{{ $clipart->name . ' ' . $colourway_colour->name }}"
                                                    style="width: 50px; height: auto">
                                                {{-- @endif
                                                @if ($clipart->type == 'bodymovin')
                                                    <div class="bm" style="height: 100px; width: 100px"
                                                        data-id="{{ $clipart->id }}" data-colour="baseline"></div>
                                                @endif --}}
                                            @else
                                                <span class="text-muted"> (not set)</span>
                                            @endif
                                            <input
                                                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                                name="colourway_{{ $colourway_colour->id }}" type="file">
                                        </div>
                                    </div>

                                </div>

                            </div>
                        @endforeach


                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <a href="{{ route('admin.clipart.index') }}" type="button"
                            class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 ">Save</button>

                    </div>
        </form>
    </div>
@endsection


@section('modals')
    {{-- Modals here --}}
@endsection

@push('after-scripts')
    <script>
        const clip = {{ $clipart->id }};

        const baseurl = '{{ URL::to('/') }}';

        const tailwindcsspath = "{{ Vite::asset('resources/css/app.css') }}";
        var tags = [
            @if (isset($clipart))
                @foreach ($clipart->tags as $tag)
                    {
                        value: "{{ $tag->id }}",
                        text: "{{ $tag->text }}"
                    },
                @endforeach
            @endif
        ]
    </script>
    {{-- @vite('resources/js/backend/template_builder/blocks.js') --}}
    @vite('resources/js/backend/clipart/edit.js')
@endpush
