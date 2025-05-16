<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Helpers\Global\QRImageWithLogo;
use App\Http\Controllers\Controller;
use App\Models\Analytics\CompiledSnippetEvent;
use App\Models\Clipart\Clipart;
use App\Models\Clipart\ClipartColourway;
use App\Models\Page\Compiled\CompiledPage;
use App\Models\Page\Compiled\CompiledPageComponent;
use App\Models\Page\Compiled\CompiledPageSnippet;
use App\Models\Page\Snippet;
use Illuminate\Support\Str;
use Carbon\Carbon;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use DiDom\Document;
use DiDom\Element;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CompiledSnippetsController extends Controller
{




    // add a keypoint to the data from the clinician interface
    public function remove_keypoint($uuid)
    {
        // remove the keypoint from the page
        //dd($keypoint_uuid);
        // @TODO does this page have a keypoin
        // remove the keypoint from the page
        $keypoint = CompiledPageSnippet::where('uuid', $uuid)->first();

        if ($keypoint) {
            $keypoint_id = $keypoint->id;
            $page = $keypoint->component->page;
            $keypoint->delete();
            // log the action
            CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'remove',
                'old_value' => '',
                'new_value' => '',
                'snippet_id' => $keypoint_id,
            ]);
            return response()->json(['status' => '0']);
        }
        return response()->json(['status' => '1']);
    }

    // update a keypoint to the data from the clinician interface
    public function update_keypoint(Request $request, $uuid)
    {
        // remove the keypoint from the page
        // dd($request);
        // @TODO does this page have a keypoint
        // remove the keypoint from the page
        $keypoint = CompiledPageSnippet::where('uuid', $uuid)->first();
        // dd($keypoint->component->page);
        if ($keypoint) {
            $page = $keypoint->component->page;
            // rebuild the keypoint
            $template = Snippet::find($keypoint->from_template_id);
            // get the paired image from the uuid (trying to hide the id from the public)

            if ($keypoint->paired_image_id > 0) {
                $img_path = Clipart::find($keypoint->paired_image_id)->baseline()->path();
                //$keypointSnippet->paired_image_id = $value['best_image'];
            } else {
                $img_path = 'https://picsum.photos/200';
            }
            $oldcontent =  $keypoint->content;
            // Search and replace text
            $newcontent = str_ireplace(["{{image_src}}", "{{text}}"], [$img_path, $request['keypoint_text']], $template->content);
            $keypoint->content = $newcontent;
            $keypoint->save();

            // log the action
            CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'update',
                'old_value' => $oldcontent,
                'new_value' => $newcontent,
                'snippet_id' => $keypoint->id,
            ]);
            return response()->json(['status' => '0']);
        }
        return response()->json(['status' => '1']);
    }


    // reorder the keypoints
    public function reorder_keypoints(Request $request)
    {

        foreach (explode(',', $request->order) as $index=>$order) {
            if ($order > 0) {
                $keypoint = CompiledPageSnippet::find($order);
                if ($keypoint) {
                    $keypoint->update(['order'=>$index]);
                }
            }
        }
        // get keypoints for the page, record new order
        return response()->json(['status' => '0']);
    }

    // update the page from the clinician view
    public function clinican_update_page(Request $request)
    {
        return $request;
    }

    // approve the model from the clinician view
    //@TODO authorisation, redirect
    public function clinician_approve($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        //   dd($page);
        $page->released_at = Carbon::now();
        $page->save();
        return response()->json(['status' => '0']);
    }


    // show the share view- this is where a clinician selects the sharing options
    //@TODO authorisation
    public function share_view($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        return view('frontend.page.share_view', ['page' => $page]);
    }

    // a public view of the page
    public function public_view($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        if (isset($page->released_at)) {
            return view('public.page.public_view', ['page' => $page]);
        }
        abort(404);
    }

    public function getQRcode(Request $request, $uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        $manager = new ImageManager(new Driver());
        // dd($board->lab->lab_icon);
        if ($page) {
            // is the board allowed to be public?
            if (isset($page->released_at)) {
                // dd(public_path ('/static/img/Graphics-et-al-transparent.png'));
                $image = $manager->read(public_path('/static/img/Graphics-et-al-transparent.png'))->contain(50, 50);

                $options = new QROptions();

                $options->returnResource     = true;
                $options->scale               = 5;
                $options->outputBase64        = false;
                $options->eccLevel            = EccLevel::H;
                $options->addLogoSpace        = true;
                $options->logoSpaceWidth      = 10;
                $options->logoSpaceHeight     = 10;
                $qrcode = new QRCode($options);
                $qrcode->addByteSegment(route('public.page.public.show', $page->uuid));

                $qrOutputInterface = new QRImageWithLogo($options, ($qrcode)->getQRMatrix());
                //  dd($qrOutputInterface->dump());
                if (isset($request['download']) && ($request['download'] == '1')) {
                    $headers = [
                        'Content-Type' => 'image/png',
                        'Content-Disposition' => 'attachment; filename=' . $page->label . '_qrcode.png',
                    ];
                    $qrcode = $manager->read($qrOutputInterface->dump())->place($image, 'center', 0, 0, 50)->toPng();
                    return response()->stream(function () use ($qrcode) {
                        echo $qrcode;
                    }, 200, $headers);
                } else {
                    //dd($image);
                    //$qrcode = $manager->read($qrOutputInterface->dump(null, $image))->toPng();
                    $response = new \Illuminate\Http\Response($manager->read($qrOutputInterface->dump())->place($image, 'center', 0, 0, 50)->toPng(), '200');
                    //$response = \Illuminate\Http\Response::make($image->encode('png'));
                    // set content-type
                    $response->header('Content-Type', 'image/png');
                    // output
                    return $response;
                }
            } else {
                abort(403, 'Page not released.');
            }
        }
    }

    // send a text/email/whatever
    public function notify_public(Request $request, $uuid)
    {
        abort(501, 'Not implemented yet.');
    }
}
