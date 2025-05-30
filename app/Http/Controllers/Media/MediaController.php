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
use Intervention\Image\Drivers\Imagick\Driver;
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

        if (isset($request['tags'])) {
            if (!$request->has('search')) {
                Session::forget('admin_media_search');
            }
            session(['admin_media_page' => 1]);
            session(['admin_media_tags' => $request['tags']]);
        }


        if ($request->has('search')) {
            if (!$request->has('tags')) {
                Session::forget('admin_media_tags');
            }
            session(['admin_media_page' => 1]);
            session(['admin_media_search' => $request['search']]);
        }


        if (isset($request['page'])) {
            session(['admin_media_page' => $request['page']]);
        }

        $currentPage = session('admin_media_page');
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });

        if (Session::has('admin_media_search')) {
            $request['search'] = session('admin_media_search');
        }

        if (Session::has('admin_media_tags')) {
            $request['tags'] = session('admin_media_tags');
        }

        $media = Media::when($request->has('tags'), function ($query) use ($request) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.id', $request['tags']);
            });
        })->when($request->has('search'), function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request['search']}%")
                    ->orWhere('description', 'LIKE', "%{$request['search']}%");
            });
        })->paginate(10);


        $tags = Tag::all();


        return view('backend.media.index')
            ->with('tags', $tags)
            ->with('media', $media);
    }


    /**
     * Show the form for creating a clipart
     */
    public function create()
    {
        //$tags = Tag::all();
        //$colourway_colours = ClipartColourwayColour::all();

        return view('backend.media.new');
        //->with('tags', $tags);
    }



    /**
     * Store a newly created media in storage
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');
        //    dd($file->mimeType);
        $size = $file->getSize();
        // $extension = $file->extension();
        //dd($file->getClientOriginalName());
        $storagePath = $file->storePubliclyAs('media', $file->hashName(), 'public');

        if ($storagePath) {
            $media = new Media([
                'uuid' => (string) Str::uuid(),
                'name' => $file->getClientOriginalName(),
                'type' => File::mimeType(Storage::disk('public')->path('/') . $storagePath),
                'path' => Storage::url($storagePath),
                'location' => $storagePath,
                'description' => $request->input('description', ''),
                'size' => $size,
                'created_by' => Auth::user()->id,
            ]);

            $media->save();

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
                $media->tags()->sync($tags);
            }

            ///  make a thumb
            $manager = new ImageManager(new Driver());
            if (Str::contains($media->type, 'image')) {
                try {
                    $image = $manager->read(Storage::disk('public')->path('/') . $media->location);
                    // resize image
                    $image->cover(200, 200);
                    // create thumb path
                    $media->thumb = $image->toPng();
                    // update media with thumb path
                    //     $media->thumb =
                    $media->save();
                } catch (\Exception $e) {
                    // handle exception, maybe log it

                }
            }





            session()->flash('flash_success', 'Created media Successfully');
            return redirect()->route('admin.media.index');
        }
    }


    /**
     * Show the edit view for a clipart
     * @param $id
     *
     * @return mixed
     */
    public function edit(Request $request, $id)
    {
        $media = Media::find($id);


        return view('backend.media.edit')
            ->with('media', $media);
    }

    /**
     * updates a media entry
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

        $media = Media::find($id);
        if ($request->hasFile('file')) {
            // dd($request->file('file'));
            $file = $request->file('file');
            //    dd($file->mimeType);
            $size = $file->getSize();
            // $extension = $file->extension();
            //dd($file->getClientOriginalName());
            $storagePath = $file->storePubliclyAs('media', $file->hashName(), 'public');
        }

        $media->update([
            'name' => $request->input('name', $media->name),
            'type' => $request->hasFile('file') ? File::mimeType(Storage::disk('public')->path('/') . $storagePath) : $media->type,
            'description' => $request->input('description', $media->description),
            'path' => $request->hasFile('file') ? Storage::url($storagePath) : $media->path,
            'location' => $request->hasFile('file') ? $storagePath : $media->location,
            'size' => $request->hasFile('file') ? $size : $media->size,
            'updated_by' => Auth::user()->id,
        ]);

        $media->save();
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
            $media->tags()->sync($tags);
        }
        ///  make a thumb
        if ($request->hasFile('file')) {
            $manager = new ImageManager(new Driver());
            if (Str::contains($media->type, 'image')) {
                try {
                    $image = $manager->read(Storage::disk('public')->path('/') . $media->location);
                    // resize image
                    $image->cover(200, 200);
                    // create thumb path
                    $media->thumb = $image->toPng();
                    // update media with thumb path
                    //     $media->thumb =
                    $media->save();
                } catch (\Exception $e) {
                    // handle exception, maybe log it

                }
            }
        }
        session()->flash('flash_success', 'Updated media Successfully');
        return redirect()->route('admin.media.index');
    }



    public
    function destroy($uuid)
    {
        //  dd($uuid);
        $media = Media::where('uuid', $uuid)->first();
        // clean up tags
        $media->tags()->detach();
        // delete file
        // remove clipart
        $media->delete();
        return ['status' => 'success'];
        //return redirect()->route('admin.media.index')->withFlashSuccess('Media deleted success!');
    }

    // @TODO does the user have the ability to retrive this clipart?
    public function thumb($uuid,  $size = 200)
    {

        $media = Media::where('uuid', $uuid)->first();
        if ($media->thumb) {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($media->thumb);
            // resize image
            $image->cover($size, $size);
            // create response and add encoded image data
            $response = new \Illuminate\Http\Response($image->toPng(), 200);
            // set content-type
            $response->header('Content-Type', 'image/png');

            // output
            return $response;
            // $image = Storage::disk('public')->get($media->thumb);
            // $response = new \Illuminate\Http\Response($image, '200');
            // $response->header('Content-Type', 'image/png');
            // return $response;
        } else {
            return response()->json(['error' => 'No thumbnail available'], 404);
        }
    }

    // seach by tags or text, returning a json array
    public function searchByTagsAndText(Request $request)
    {
        dd($request->all());
        return response()->json($output, 200);
    }

    // store some media from Tinymce
    public function tinymce_store(Request $request)
    {

        $file = $request->file('file');
        //    dd($file->mimeType);
        $size = $file->getSize();
        // $extension = $file->extension();
        //dd($file->getClientOriginalName());
        $storagePath = $file->storePubliclyAs('media', $file->hashName(), 'public');

        if ($storagePath) {
            $media = new Media([
                'uuid' => (string) Str::uuid(),
                'name' => $file->getClientOriginalName(),
                'type' => File::mimeType(Storage::disk('public')->path('/') . $storagePath),
                'path' => Storage::url($storagePath),
                'location' => $storagePath,
                'size' => $size,
                'created_by' => Auth::user()->id,
            ]);
            $media->save();
            // $mime     = File::mimeType(Storage::disk('public')->path('/') . $media->location);
            //dd($mime);
            return array(
                'uuid' => $media->uuid,
                'location' => str_starts_with($media->type, 'audio') ? route('public.media.stream', $media->uuid) : route('public.media.show', $media->uuid),
                'mime' => $media->type
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
            $mime     = $media->type; //File::mimeType($path);
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

    public function show(Request $request, $uuid)
    {
        $media = Media::where('uuid', $uuid)->first();
        //  dd($media->path);
        $path = Storage::disk('public')->path('/') . $media->location;
        // $file = Storage::disk('public')->path('/') . $media->location;
        //  dd($file);
        if ($media) {
            $file = Storage::disk('public')->get($media->location);

            $response = new \Illuminate\Http\Response($file, '200');
            $response->header("Content-Type", $media->type);
            return $response;
        } else {
            abort(404);
        }
    }
}
