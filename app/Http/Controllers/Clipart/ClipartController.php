<?php

namespace App\Http\Controllers\Clipart;

use App\Http\Controllers\Controller;
use App\Models\Clipart\Clipart;
use App\Models\Clipart\ClipartColourway;
use App\Models\Clipart\ClipartColourwayColour;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ClipartController extends Controller
{
    //

    /**
     * Display a listing of the clipart
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $id = null)
    {

        if (isset($request['tags'])) {
            if (!$request->has('search')) {
                Session::forget('admin_clipart_search');
            }
            session(['admin_clipart_page' => 1]);
            session(['admin_clipart_tags' => $request['tags']]);
        }


        if ($request->has('search')) {
            if (!$request->has('tags')) {
                Session::forget('admin_clipart_tags');
            }
            session(['admin_clipart_page' => 1]);
            session(['admin_clipart_search' => $request['search']]);
        }


        if (isset($request['page'])) {
            session(['admin_clipart_page' => $request['page']]);
            $currentPage = session('admin_clipart_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_clipart_search')) {
            $request['search'] = session('admin_clipart_search');
        }

        if (Session::has('admin_clipart_tags')) {
            $request['tags'] = session('admin_clipart_tags');
        }

        $clipart = Clipart::when($request->has('tags'), function ($query) use ($request) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.id', $request['tags']);
            });
        })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request['search']}%")
                        ->orWhere('description', 'LIKE', "%{$request['search']}%");
                });
            })
            ->paginate(10);

        // preload baseline ID
        $clipart->each(function ($item, $key) {
            $item->id_baseline = $item->baseline_id;
        });
        $tags = Tag::all();


        return view('backend.clipart.index')
            ->with('tags', $tags)
            ->with('clipart', $clipart);
    }

    /**
     * Show the form for creating a clipart
     */
    public function create()
    {
        $tags = Tag::all();
        $colourway_colours = ClipartColourwayColour::all();

        return view('backend.clipart.create')
            ->with('colourway_colours', $colourway_colours)
            ->with('tags', $tags);
    }

    /**
     * Store a newly created clipart in storage
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // save clipart metadata
        $clipart = new Clipart();
        $clipart->name = $request->name;
        $clipart->owner_id = Auth::user()->id;
        $clipart->created_by = Auth::user()->id;
        $clipart->description = $request->description;
        $clipart->type =  $request->type_radio;
        $clipart->save();
        // sync tags
        $clipart->tags()->sync($request->tags);
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

                $url = env('CAIRO_URL', "http://127.0.0.1:5000/convert");

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'X-Second' => 'bar'
                ])->post($url, [
                    'svg' =>  $colourway->data
                ]);
                if ($response->successful()) {  //
                   // dd($response->json()['png_base64']);
                    $manager = new ImageManager(new Driver());
                    $clipart->thumb = $manager->read($response->json()['png_base64'])->toPng();
                    $clipart->save();
                }
            }
        }

        // make a thumbnail
        session()->flash('flash_success', 'Created Clipart Successfully');
        return redirect()->route('admin.clipart.index');
    }

    // @TODO does the user have the ability to retrive this clipart?
    public function thumb($id,  $size = 200, $colour = 'baseline')
    {
        // dd($colour);
        $colour_id = ClipartColourwayColour::where('name', '=', $colour)->first()->id;

        $clipart = Clipart::find($id);

      //  dd($clipart);

        $data = $clipart->colourways->where('colour_id', '=', $colour_id)->first()->data;// ?? Storage::get('/public/questionmark.svg');

        $manager = new ImageManager(new Driver());
        if ($colour == 'baseline') {
            if (!$clipart->thumb) {
                //dd($clipart->colourways->where('colour_id', '=', $colour_id)->first());
                $data = $clipart->colourways->where('colour_id', '=', $colour_id)->first()->data;// ?? Storage::get('/public/questionmark.svg');
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
}
