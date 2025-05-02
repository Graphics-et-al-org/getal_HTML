<?php

namespace App\Http\Controllers\Media;

use App\Http\Controllers\Controller;
use App\Jobs\AI\SubmitSvgForProcesing;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Clipart\Clipart;
use Intervention\Image\ImageManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Clipart\ClipartColourway;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\Clipart\ClipartColourwayColour;
use App\Models\Media\Media;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    //

    /**
     * Display a listing of the clipart
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $id = null)
    {

        // if (isset($request['tags'])) {
        //     if (!$request->has('search')) {
        //         Session::forget('admin_clipart_search');
        //     }
        //     session(['admin_clipart_page' => 1]);
        //     session(['admin_clipart_tags' => $request['tags']]);
        // }


        // if ($request->has('search')) {
        //     if (!$request->has('tags')) {
        //         Session::forget('admin_clipart_tags');
        //     }
        //     session(['admin_clipart_page' => 1]);
        //     session(['admin_clipart_search' => $request['search']]);
        // }


        // if (isset($request['page'])) {
        //     session(['admin_clipart_page' => $request['page']]);
        //     $currentPage = session('admin_clipart_page');
        //     Paginator::currentPageResolver(function () use ($currentPage) {
        //         return $currentPage;
        //     });
        // }

        // if (Session::has('admin_clipart_search')) {
        //     $request['search'] = session('admin_clipart_search');
        // }

        // if (Session::has('admin_clipart_tags')) {
        //     $request['tags'] = session('admin_clipart_tags');
        // }

        // $clipart = Clipart::when($request->has('tags'), function ($query) use ($request) {
        //     $query->whereHas('tags', function ($q) use ($request) {
        //         $q->whereIn('tags.id', $request['tags']);
        //     });
        // })
        //     ->when($request->has('search'), function ($query) use ($request) {
        //         $query->where(function ($q) use ($request) {
        //             $q->where('name', 'like', "%{$request['search']}%")
        //                 ->orWhere('description', 'LIKE', "%{$request['search']}%");
        //         });
        //     })
        $media = Media::paginate(10);

        $tags = Tag::all();


        return view('backend.media.index')

            ->with('media', $media);
    }


    /**
     * Show the form for creating a clipart
     */
    public function create()
    {
        //$tags = Tag::all();
        $colourway_colours = ClipartColourwayColour::all();

        return view('backend.media.new');
        //->with('tags', $tags);
    }



    /**
     * Store a newly created media in storage
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function backend_store(Request $request)
    {
        dd($request->all());
        // save clipart metadata
        $clipart = new Clipart();
        $clipart->name = $request->name;
        $clipart->owner_id = Auth::user()->id;
        $clipart->created_by = Auth::user()->id;
        $clipart->description = $request->description;
        $clipart->preferred_description = $request->preferred_description;
        $clipart->fallback_description = $request->fallback_description;
        $clipart->type =  $request->type_radio;
        if (isset($request['preferred']) && ($request['preferred'] == 'true')) {
            $clipart->preferred =  true;
        }
        if (isset($request['fallback']) && ($request['fallback'] == 'true')) {
            $clipart->fallback =  true;
        }
        $clipart->save();
        // sync tags
        if (isset($request['tags'])) {
            $tags = [];
            foreach ($request['tags'] as $item) {
                if (!(Tag::where('id', $item)->exists())) {
                    $new = Tag::updateOrCreate(['text' => $item]);
                    $tags[] = $new->id;
                } else {
                    $tags[] = $item;
                }
            }
            $clipart->tags()->sync($tags);
        }
        // save files
        // first filter out the colourways into an array
        $files = array_filter($request->all(), function ($key) {
            return strpos($key, 'colourway_') !== false;
        }, ARRAY_FILTER_USE_KEY);

        // then save them as data
        foreach ($files as $key => $file) {
            $colourway = new ClipartColourway();
            $colourway_id = explode('_', $key)[1];
            $colourway->clipart_id = $clipart->id;
            $colourway->colour_id = ClipartColourwayColour::find($colourway_id)->id;
            $colourway->data = $file->get();
            $colourway->save();
            // save the thumbnail
            if (ClipartColourwayColour::find($colourway_id)->name == 'baseline') {

                $url = env('SVG_PROCESS_URL', false);
                if ($url) {
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($url, [
                        'svg' =>  $colourway->data,
                        'name' => $clipart->name,
                        'description' => $clipart->description,
                        'tags' => $clipart->tags->pluck('text')->join(','),
                    ]);
                    if ($response->successful()) {  //

                        $data = $response->json();
                        //  thumbnail
                        $manager = new ImageManager(new Driver());
                        $clipart->thumb = $manager->read($data['png_base64'])->toPng();
                        //  other parameters
                        $clipart->gpt4_description = $data['gpt4_description'];
                        $clipart->clip_image_embedding_b64 = $data['clip_image_embedding_b64'];
                        $clipart->bert_text_embedding_b64 = $data['bert_text_embedding_b64'];
                        $clipart->save();
                    }
                }
            }
        }

        // make a thumbnail
        session()->flash('flash_success', 'Created Media Successfully');
        return redirect()->route('admin.media.index');
    }


    /**
     * Show the edit view for a clipart
     * @param $id
     *
     * @return mixed
     */
    public function edit(Request $request, $id)
    {
        $clipart = Clipart::find($id);
        $colourway_colours = ClipartColourwayColour::all();

        return view('backend.media.edit')
            ->with('clipart', $clipart)
            ->with('colourway_colours', $colourway_colours);
    }

    /**
     * updates a clipart entry
     *
     * @param Request $request
     * @param Clipart $clipart
     *
     * @return mixed
     * @throws \Throwable
     * @throws \App\Exceptions\GeneralException
     */
    public function update(Request $request, $id)
    {
        // new clipart entry
        // dd($request->all());
        $clipart = Clipart::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request['name'],
                'description' => $request['description'],
                'preferred_description' => $request['preferred_description'],
                'fallback_description' => $request['fallback_description'],
                // 'citations' => $request['citations'],
                'type' => $request['type'] ? $request['type'] : 'svg',
                'preferred' => (isset($request['preferred']) && ($request['preferred'] == 'true')) ? true : null,
                'fallback' => (isset($request['fallback']) && ($request['fallback'] == 'true')) ? true : null,
            ]

        );

        // tags
        if (isset($request['tags'])) {
            $tags = [];
            foreach ($request['tags'] as $tag) {
                if (!(Tag::where('id', $tag)->exists())) {
                    if (strlen($tag) > 0) {
                        $tags[] = Tag::updateOrCreate(['text' => $tag])->id;
                    }
                } else {
                    $tags[] = $tag;
                }
            }
            $clipart->tags()->sync($tags);
        }


        // save files
        // first filter out the colourways into an array
        $files = array_filter($request->all(), function ($key) {
            return strpos($key, 'colourway_') !== false;
        }, ARRAY_FILTER_USE_KEY);
        // $baseline_colour_id = ClipartColourwayColour::where('name', '=', 'baseline')->first()->id;
        // then save them as data
        foreach ($files as $key => $file) {
            $colourway = new ClipartColourway();
            $colourway_id = explode('_', $key)[1];
            $colourway->clipart_id = $clipart->id;
            $colourway->colour_id = ClipartColourwayColour::find($colourway_id)->id;
            $colourway->data = $file->get();
            $colourway->save();
        }
        SubmitSvgForProcesing::dispatch($clipart)->onConnection('database')->onQueue('svgprocess');

        session()->flash('flash_success', 'Updated Clipart Successfully');
        return redirect()->route('admin.clipart.index');
    }



    public
    function destroy(Request $request)
    {
        $clipart = Clipart::find($request['id']);
        // clean up the colourways
        $clipart->colourways()->delete();
        // clean up tags
        $clipart->tags()->detach();
        // remove clipart
        $clipart->delete();

        return redirect()->route('admin.clipart.index')->withFlashSuccess('Clipart deleted success!');
    }

    // @TODO does the user have the ability to retrive this clipart?
    public function thumb($id,  $size = 200, $colour = 'baseline')
    {
        // dd($colour);
        $colour_id = ClipartColourwayColour::where('name', '=', $colour)->first()->id;

        $clipart = Clipart::find($id);

        //  dd($clipart);

        $data = $clipart->colourways->where('colour_id', '=', $colour_id)->first()->data; // ?? Storage::get('/public/questionmark.svg');

        $manager = new ImageManager(new Driver());
        if ($colour == 'baseline') {
            if (!$clipart->thumb) {
                //dd($clipart->colourways->where('colour_id', '=', $colour_id)->first());
                $data = $clipart->colourways->where('colour_id', '=', $colour_id)->first()->data; // ?? Storage::get('/public/questionmark.svg');
                $xml = simplexml_load_string($data);
                $xml[0]['height'] = $size;
                $xml[0]['width'] = $size;
                $domxml = dom_import_simplexml($xml);
                $content_typestr = 'image/svg+xml';
                $response = new \Illuminate\Http\Response($domxml->ownerDocument->saveXML($domxml->ownerDocument->documentElement), '200');
                $response->header("Content-Type", $content_typestr);
                return $response;
            } else {

                $image = $manager->read($clipart->thumb);
                // resize image
                $image->cover($size, $size);
                // create response and add encoded image data
                $response = new \Illuminate\Http\Response($image->toPng(), '200');
                // set content-type
                $response->header('Content-Type', 'image/png');

                // output
                return $response;
            }
        } else {
            $xml = simplexml_load_string($data);
            $xml[0]['height'] = 200;
            $xml[0]['width'] = 200;
            $domxml = dom_import_simplexml($xml);
            $content_typestr = 'image/svg+xml';
            $response = new \Illuminate\Http\Response($domxml->ownerDocument->saveXML($domxml->ownerDocument->documentElement), '200');
            $response->header("Content-Type", $content_typestr);
            return $response;
        }
    }

    // seach by tags or text, returning a json array
    public function searchByTagsAndText(Request $request)
    {
        $clipart = Clipart::when($request->has('tags'), function ($query) use ($request) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.text', $request['tags']);
            });
        })->when($request->has('search'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request['search']}%")
                    ->orWhere('description', 'LIKE', "%{$request['search']}%");
            });
        })->when($request->has('preferred'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('preferred', '=', true);
            });
        })->when($request->has('fallback'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('fallback', '=', true);
            });
        })->get();

        $colour_id = ClipartColourwayColour::where('name', '=', 'baseline')->first()->id;
        //  $baseline = $clipart->colourways->where('colour_id', '=', $colour_id)->first(); // ?? Storage::get('/public/questionmark.svg');

        $output = [];
        // preload baseline ID
        $clipart->each(function ($item, $key) use ($colour_id, &$output) {
            //   dd($colour_id);
            $baseline = $item->colourways->where('colour_id', '=', $colour_id)->first();

            // @TODO make baseline path
            $appendobj = array(
                'id' => $item->id,
                'name' => $item->name,
                'preferred' => $item->preferred ? 'true' : '',
                'fallback' => $item->fallback ? 'true' : '',
                'description' => $item->description,
                'preferred_description' => $item->preferred_description,
                'fallback_description' => $item->fallback_description,
                'gpt4_description' => $item->gpt4_description,
                'bert_text_embedding_b64' => $item->bert_text_embedding_b64,
                'clip_image_embedding_b64' => $item->clip_image_embedding_b64,
                'tags' => $item->tags->pluck('text')->join(','),
                'colourways_ids' => $item->colourways->pluck('id')->join(',')
            );
            if ($baseline) {
                $appendobj['baseline_id'] = $baseline->id ?? null;
                $appendobj['baseline_path'] = $baseline->path() ?? null;
            }
            $output[] = $appendobj;
        });

        // dd($output);
        return response()->json($output, 200);
    }

    // store from Tinymce
    public function tinymce_store(Request $request)
    {

        $file = $request->file('file');
    //    dd($file->mimeType);
        $size = $file->getSize();
        $extension = $file->extension();
        //dd($file->getClientOriginalName());
        $storagePath = $file->storePubliclyAs('media', $file->hashName(), 'public');
        if ($storagePath) {
            $media = new Media([
                'uuid' => (string) Str::uuid(),
                'name' => $file->getClientOriginalName(),
                'type' => $extension,
                'path' => Storage::url($storagePath),
                'location' => $storagePath,
                'size' => $size,
                'created_by' => Auth::user()->id,
            ]);
            $media->save();
            $mime     = File::mimeType(Storage::disk('public')->path('/') . $media->location);
           // dd($mime);
            return array(
                'uuid' => $media->uuid,
                'location' => route('public.media.stream', $media->uuid),
                'mime' => $mime
            );
            //  dd($media);

        }
        // save clipart metadata
    }

    public function stream(Request $request, $uuid)
    {
        $media = Media::where('uuid', $uuid)->first();
        //  dd($media->path);
        $path = Storage::disk('public')->path('/') . $media->location;
        // $file = Storage::disk('public')->path('/') . $media->location;
        //  dd($file);
        if ($media) {
            $size     = File::size($path);
            $mime     = File::mimeType($path);
            // dd($mime);
            $start    = 0;
            $length   = $size;
            $status   = 200;

            $headers  = [
                'Content-Type'  => $mime,
                'Accept-Ranges' => 'bytes',
            ];

            // 2. Handle HTTP Range if present
            if ($request->headers->has('Range')) {
                // e.g. "Range: bytes=1000-"
                list(, $range) = explode('=', $request->header('Range'), 2);
                list($start, $end) = explode('-', $range, 2) + [1 => ''];
                $start = intval($start);

                // if no end is given, set to last byte
                $end   = $end === '' ? $size - 1 : intval($end);
                $end   = min($end, $size - 1);

                $length = $end - $start + 1;
                $status = 206; // Partial Content

                $headers['Content-Range']  = "bytes {$start}-{$end}/{$size}";
                $headers['Content-Length'] = $length;
            } else {
                $headers['Content-Length'] = $size;
            }

            // 3. Create the streamed response
            $stream = function () use ($path, $start, $length) {
                $handle   = fopen($path, 'rb');
                fseek($handle, $start);

                $remaining = $length;
                while ($remaining > 0 && ! feof($handle)) {
                    // read in 8KB chunks
                    $read = min(8192, $remaining);
                    echo fread($handle, $read);
                    flush();
                    $remaining -= $read;
                }

                fclose($handle);
            };

            return new StreamedResponse($stream, $status, $headers);
        } else {
            abort(404);
        }
    }
}
