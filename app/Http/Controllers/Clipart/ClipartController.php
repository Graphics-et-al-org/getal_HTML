<?php

namespace App\Http\Controllers\Clipart;

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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

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

    public function baseline($id){
        return response(Clipart::find($id)->baseline()->data, 200)
        ->header('Content-Type', 'image/svg+xml');
        
    }
    /**
     * Show the form for creating a clipart
     */
    public function create()
    {
        $tags = Tag::all();
        $colourway_colours = ClipartColourwayColour::all();

        return view('backend.clipart.new')
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
        //   dd($request->all());
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
        session()->flash('flash_success', 'Created Clipart Successfully');
        return redirect()->route('admin.clipart.index');
    }

    /**
     * Import a zip file containing properly structured and named files for import.
     * Each clipart is described by a csv or xlsx file containing metadata
     * and SVG files named with the base filename contained in the metadata file plus '_<colour>.svg' where <colour> is one of '0', 'Grey', 'Blue', 'Red', 'Green', 'Purple', 'Yellow', 'Outline'
     * @param Request $request
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public
    function bulkImport(Request $request)
    {
        // clean up any previous folders

        foreach (Storage::disk('local')->directories('import') as $old_dir) {
            Storage::deleteDirectory($old_dir);
        }

        // $file = $request->zipfile;
        // Store the file in the desired location
        $file = $request->file('zipfile');
        $path = $file->storeAs('upload', $file->getClientOriginalName(), 'local');

        //dd(Storage::disk('local'));



        //dd($path);
        if (!$path) {
            return '-1';
        }

        // make a temp folder name
        $foldername = 'import/' . uniqid();
        //$zip = Madzipper::make($file->getPathname());

        // $zip->extractTo(Storage::disk('local')->path($foldername));
        $zip = new \ZipArchive();

        if ($zip->open($file->getPathname()) !== TRUE) {
            session()->flash('flash_danger', "Upload failed, can't open zip file");
            return redirect()->route('admin.clipart.index');
        }

        $zip->extractTo(Storage::disk('local')->path($foldername));

        // go through the metadata files
        // list all files
        $files = Storage::files($foldername);
        //find metadata files- they're either CSV or .xlsx
        $metadata_files = array_filter($files, function ($k) {
            return Str::endsWith($k, ['.csv', 'xlsx']);
        });
        foreach ($metadata_files as $metadata_file) {
            // this will be a CSV
            // open the metadata file
            if (($handle = fopen(Storage::path($metadata_file), "r")) !== FALSE) {
                $clipart_data = ['owner_id' => Auth::user()->id, 'type' => 'svg'];
                $tags = [];
                $filename = '';
                //if it's an XLSX file
                if (Str::endsWith($metadata_file, ['xlsx'])) {
                    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(Storage::path($metadata_file));
                    $worksheet = $spreadsheet->getActiveSheet();
                    // Get the highest row and column numbers referenced in the worksheet
                    $highestRow = $worksheet->getHighestRow(); // e.g. 10
                    $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
                    $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                    for ($row = 1; $row <= $highestRow; ++$row) {
                        $value = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        switch ($value) {
                            case 'name':
                                if (!$worksheet->getCellByColumnAndRow(2, $row)->getValue()) {
                                    Storage::delete($files);
                                    session()->flash('flash_danger', "Metadata file {$metadata_file} has empty name field. Aborting upload");
                                    return redirect()->route('admin.clipart.index');
                                }
                                $clipart_data['name'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                break;
                            case 'filename':
                                $filename = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                break;
                            case 'description':
                                $clipart_data['description'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                                break;
                            case 'tags':
                                $tags = [];
                                for ($col = 2; $col <= $highestColumnIndex; ++$col) {
                                    if (strlen($worksheet->getCellByColumnAndRow($col, $row)->getValue()) > 0) {
                                        $tags[] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                                    }
                                }
                                // dd($tags);
                                break;
                            case 'labs':
                                //@TODO implement  multiple lab assignments- right now we're doing it manually
                                // comma separated
                                //
                                break;
                                // case 'citations':
                                //     $citationsdata = [];
                                //     for ($col = 2; $col <= $highestColumnIndex; ++$col) {
                                //         if (strlen($worksheet->getCellByColumnAndRow($col, $row)->getValue()) > 0) {
                                //             $citationsdata[] = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                                //         }
                                //     }
                                //     // build a nice HTML citation string to display
                                //     $clipart_data['citations'] = "<ul><li>";
                                //     $clipart_data['citations'] .= implode("</li><li>", $citationsdata);
                                //     $clipart_data['citations'] .= "</li></ul>";
                                //     $clipart_data['citations'] = utf8_encode($clipart_data['citations']);
                                //     break;

                            default:
                                break;
                        }
                    }
                    // else it's a CSV filr
                } elseif (Str::endsWith($metadata_file, ['csv'])) {
                    while (($data = fgetcsv($handle)) !== FALSE) {
                        // account for different encoding, thanks Mac heads!
                        $data[0] = iconv(mb_detect_encoding($data[0]), mb_detect_encoding("teststr") . "//IGNORE", $data[0]);
                        // the first data cell is the header
                        switch ($data[0]) {
                            case "name":
                                if (!isset($data[1])) {
                                    Storage::delete($files);
                                    session()->flash('flash_danger', "Metadata file {$metadata_file} has empty name field. Aborting upload");
                                    return redirect()->route('admin.clipart.index');
                                }
                                $clipart_data['name'] = $data[1];
                                $clipart_data['description'] = $data[1];
                                break;
                            case 'filename':
                                // this is critical
                                if (!isset($data[1])) {
                                    Storage::delete($files);
                                    session()->flash('flash_danger', "Metadata file {$metadata_file} has empty filename field. Aborting upload");
                                    return redirect()->route('admin.clipart.index');
                                }
                                $filename = $data[1];
                                break;
                            case 'description':
                                if (isset($data[1])) {
                                    $clipart_data['description'] = $data[1] ?? 'No description';
                                }

                                break;
                            case 'preferred_description':
                                if (isset($data[1])) {
                                    $clipart_data['preferred_description'] = $data[1] ?? 'No description';
                                }

                                break;
                            case 'fallback_description':
                                if (isset($data[1])) {
                                    $clipart_data['preferred_description'] = $data[1] ?? 'No description';
                                }

                                break;
                            case 'tags':
                                $tags = $data;
                                //  remove the first element, it's the header
                                array_shift($tags);
                                break;
                            case 'preferred':

                                $clipart_data['preferred'] = isset($data[1]) ? (strtolower($data[1]) == 'true') : false;
                                break;
                            case 'fallback':

                                $clipart_data['fallback'] = isset($data[1]) ? (strtolower($data[1]) == 'true') : false;
                                break;
                            case 'labs':
                                //@TODO implement  multiple lab assignments- right now we're doing it manually
                                // comma separated
                                //
                                break;

                            default:
                                break;
                        }
                    }
                }

                // dd($tags);
                // make the clipart entry (or update if there's something with this name already)
                try {
                    // dd($clipart_data);
                    $clipart = Clipart::updateOrCreate(['name' => $clipart_data['name']], $clipart_data);
                    //sync tags

                    $tagsSyncArr = [];

                    foreach ($tags as $tag) {
                        if (strlen($tag) > 0) {
                            $tagsSyncArr[] = Tag::firstOrCreate(['text' => $tag])->id;
                        }
                    }
                    $clipart->tags()->sync($tagsSyncArr);


                    // make the colourways
                    // find the files
                    $colours = ClipartColourwayColour::all();


                    foreach ($colours as $colour) {
                        // find the file
                        $name = $colour->name;
                        if ($colour->name == 'baseline') {
                            $name = '0';
                        }
                        if (Storage::disk('local')->exists($foldername . DIRECTORY_SEPARATOR . $filename . '_' . $name . '.svg')) {
                            ClipartColourway::updateOrCreate(['clipart_id' => $clipart->id, 'colour_id' => $colour->id], ['data' => Storage::disk('local')->get($foldername . DIRECTORY_SEPARATOR . $filename . '_' . $name . '.svg'),]);
                        }
                    }

                    SubmitSvgForProcesing::dispatch($clipart)->onConnection('database')->onQueue('svgprocess');
                } catch (\Exception $e) {
                    // do nothing
                    Storage::delete($files);
                    session()->flash('flash_danger', 'Clipart uploaded failed:' . $e->getMessage());
                    //  dd($e->getMessage() . ' on clipart ' . json_encode($clipart_data));
                    return redirect()->route('admin.clipart.index');
                } catch (\Exception $e) {
                    // do nothing
                    Storage::delete($files);
                    session()->flash('flash_danger', 'Clipart uploaded failed:' . $e->getMessage());
                    //  dd($e->getMessage() . ' on clipart ' . json_encode($clipart_data));
                    return redirect()->route('admin.clipart.index');
                }
            }
        }

        // clean up
        Storage::delete($files);

        session()->flash('flash_success', 'Clipart created success!');
        return redirect()->route('admin.clipart.index')->withFlashSuccess('Clipart created success!');

        //        return redirect()->route('admin.clipart.index')->withFlashSuccess('Clipart uploaded success!');
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

        return view('backend.clipart.edit')
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

    /**
     * Gets the AI
     *
     * @param Request $request
     * @param Clipart $clipart
     *
     * @return mixed
     * @throws \Throwable
     * @throws \App\Exceptions\GeneralException
     */
    public function update_ai_params(Request $request, $id)
    {
        return;
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

    // Upload the JSON string used as a
    public function uploadJsonToCache()
    {
        $clipart = Clipart::all();

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

        $fileName = 'cache.json';

        Storage::disk('azure')->put($fileName, json_encode($output));
        return response()->json([
            'status' => 0
        ], 200);
    }

    // refresh *all* AI metedata for clipart. Takes a while.
    public function refreshAllAiMetadata()
    {
        $clipart = Clipart::all();
        foreach ($clipart as $item) {
            // dd($item);
            SubmitSvgForProcesing::dispatch($item)->onConnection('database')->onQueue('svgprocess');
        }
        return response()->json([
            'status' => 0
        ], 200);
    }

    // process clipart entries where metadata hasn't been created
    public function processPendingAiMetadata()
    {
        // clean up existing jobs
        DB::table('jobs')->where('queue', 'svgprocess')->delete();
        // process all
        $clipart = Clipart::where('gpt4_description', null)->get();
        // dd($clipart->count());
        foreach ($clipart as $item) {
            SubmitSvgForProcesing::dispatch($item)->onConnection('database')->onQueue('svgprocess');
        }
        return response()->json([
            'status' => 0
        ], 200);
    }
}
