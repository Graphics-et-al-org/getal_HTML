<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Helpers\Global\QRImageWithLogo;
use App\Http\Controllers\Controller;
use App\Models\Analytics\CompiledSnippetEvent;
use App\Models\Clipart\ClipartColourway;
use App\Models\Page\Compiled\CompiledPage;
use App\Models\Page\Compiled\CompiledPageComponent;
use App\Models\Page\Compiled\CompiledPageSnippet;
use App\Models\Page\Snippet;
use App\Models\Page\SnippetsCollection;
use Illuminate\Support\Str;
use Carbon\Carbon;
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;
use DiDom\Document;
use DiDom\Element;
use Firebase\JWT\JWT;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CompiledPagesController extends Controller
{


    // read the page
    public function read(Request $request)
    {
        dd($request);
    }

    // store the page
    public function update(Request $request)
    {
        dd($request);
    }




    // get the css
    public function css($id)
    {
        $page = CompiledPage::findOrFail($id);
        return $page->css;
    }

    // show the clinician view
    //@TODO authorisation
    public function clinician_view($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();

        return view('frontend.page.clinician_view', ['page' => $page]);
    }


    // add a keypoint to the data from the clinician interface
    public function add_keypoint(Request $request, $uuid)
    {
        // dd($request);
        // find the page
        // validate the data
        $component = CompiledPageComponent::find($request['component']);

        if ($component && ($component->page->uuid == $uuid)) {
            $page = CompiledPage::where('uuid', $uuid)->first();
            // Derive the template from the component
            $template = Snippet::find(CompiledPageComponent::find($request['component'])->snippets->first()->from_template_id);
            // get the paired image from the uuid (trying to hide the id from the public)
            $paired_image = ClipartColourway::where('uuid', $request['image_uuid'])->first()->clipart;
            // derive the order
            $order = CompiledPageComponent::find($request['component'])->snippets->last()->order;
            // dd($order);

            //$paired_image = ClipartColourway::where('uuid', $request['image_uuid'])->first()->clipart->id;

            $paired_image_id = -1;

            if ($paired_image) {
                $img_path = ClipartColourway::where('uuid', $request['image_uuid'])->first()->path();
                $paired_image_id = $paired_image->id;
            }
            // Search and replace text
            $content = str_ireplace(["{{image_src}}", "{{text}}"], [$img_path, $request['keypoint_text']], $template->content);

            // create the new snippet
            $keypoint = CompiledPageSnippet::create([
                'uuid' => Str::uuid()->toString(),
                'compiled_page_components_id' => $request['component'],
                'paired_image_id' => $paired_image_id,
                'from_template_id' => $template->id,
                'type' => 'keypoint',
                'content' => $content,
                'order' => $order + 1,
            ]);


            // save
            $keypoint->save();

            // event
            CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'create',
                'old_value' => '',
                'new_value' =>  $keypoint->content,
                'snippet_id' => $keypoint->id,
            ]);
            // add keypoint in teh background here, will be fakesied in the view
            return response()->json(['status' => '0', 'id' => $keypoint->id, 'uuid' => $keypoint->uuid]);
        }

        return response()->json(['status' => '1']);
    }

    // add a keypoint to the data from the clinician interface
    public function remove_keypoint($uuid, $keypoint_uuid)
    {
        // remove the keypoint from the page
        //dd($keypoint_uuid);
        // @TODO does this page have a keypoint
        $page = CompiledPage::where('uuid', $uuid)->first();
        // remove the keypoint from the page
        $keypoint = CompiledPageSnippet::where('uuid', $keypoint_uuid)->first();
        if ($keypoint) {
            $keypoint->delete();
            // log the action
            CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'remove',
                'old_value' => '',
                'new_value' => '',
                'snippet_id' => $keypoint->id,
            ]);
            return response()->json(['status' => '0']);
        }
        return response()->json(['status' => '1']);
    }

    //update a keypoint to the data from the clinician interface
    public function update_keypoint(Request $request, $uuid, $keypoint_uuid)
    {
        // remove the keypoint from the page
       // dd($keypoint_uuid);
        // @TODO does this page have a keypoint
        $page = CompiledPage::where('uuid', $uuid)->first();
        // remove the keypoint from the page
        $keypoint = CompiledPageSnippet::where('uuid', $keypoint_uuid)->first();
        if ($keypoint) {
            $keypoint->update();
            // log the action
            CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'remove',
                'old_value' => '',
                'new_value' => '',
                'snippet_id' => $keypoint->id,
            ]);
            return response()->json(['status' => '0']);
        }
        return response()->json(['status' => '1']);
    }


    // reorder the keypoints
    public function reorder_keypoints(Request $request, $uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        // get keypoints for the page, record new order
        return response()->json(['status' => '1']);
    }

    // add a keypoint to the data from the clinician interface
    public function add_collections(Request $request, $uuid)
    {
        //   dd($request);
        // find the page
        // validate the data
        // get collections
        $collections = json_decode($request['collections']);
        $returned_data = [];

        foreach ($collections as $collection_uuid) {
            // get the category
            $collection = SnippetsCollection::where('uuid', $collection_uuid)->first();
           // dd($collection->snippets);
            if ($collection) {
                // get the snippets in the category
                $snippets = $collection->snippets;
                // get the next order
                $order = CompiledPageComponent::find($request['compiled_page_components_id'])->snippets->last()->order;
             //   dd($order  );
                foreach ($snippets as $snippet) {
                    // add the snippet to the page
                    $order++;
                    $pageSnippet = new CompiledPageSnippet(
                        [
                            'uuid' => Str::uuid()->toString(),
                            'type' => 'snippet',
                            'order' => $order,
                            'content' => $snippet->content,
                            'compiled_page_components_id' =>  $request['compiled_page_components_id'],
                            'from_template_id' => $snippet->id,
                        ]
                    );
                    // replace the image src
                    $pageSnippet->save();
                    $returned_data[] = [
                        'id' => $pageSnippet->id,
                        'uuid' => $pageSnippet->uuid,
                        'content' => $pageSnippet->content,
                        //'order' => $pageSnippet->order,
                        //'type' => $pageSnippet->type,
                    ];
                }
            }
        }

        return response()->json(['status' => '0', 'data' => $returned_data]);
    }


        // reorder the keypoints
    public function reorder_snippets(Request $request, $uuid)
    {
                 //$snippets = $snippet->snippets;
            foreach ($snippets as $key => $snippet) {
                $snippet->order = $request['order'][$key];
                $snippet->save();
            }
             // get keypoints for the page, record new order
        return response()->json(['status' => '1']);
    }

        // reorder the keypoints
    public function remove_snippet(Request $request, $uuid)
    {
        $snippet = CompiledPageSnippet::where('uuid', $uuid)->first();
        $page = $snippet->component->page;
        if($snippet) {
             CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'remove',
                'old_value' => '',
                'new_value' => '',
                'snippet_id' => $snippet->id,
            ]);
            $snippet->delete();
            // log the action

        }
        // get keypoints for the page, record new order
        return response()->json(['status' => '1']);
    }
    // update the page from the clinician view
    //@TODO authorisation, redirect
    public function summary_update(Request $request, $uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        //   dd($page);
        $page->released_at = Carbon::now();
        $page->save();
        return response()->json(['status' => '0']);
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
        $analyticsToken = $this->generateAnalyticsApiToken(route('public.page.public.show', $page->uuid));
        if (isset($page->released_at)) {
            return view('public.page.public_view', ['page' => $page, 'auth_token' => $analyticsToken]);
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

    // private function to generate an analytics token. An attempt at preventing tomfoolery with analytics events
    private function generateAnalyticsApiToken($pageUrl)
    {
        $secret =  env('JWT_SECRET', null);
        if ($secret) {
            $issuedAt = time();
            $expirationTime = $issuedAt + 86400; // 1 day
            $payload = [
                'url' => $pageUrl,
                'iat' => $issuedAt,
                'exp' => $expirationTime
            ];
            return JWT::encode($payload, $secret, 'HS256');
        }
        return false;
    }
}
