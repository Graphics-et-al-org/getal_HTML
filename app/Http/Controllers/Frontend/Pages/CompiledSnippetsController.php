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

  // add a keypoint to the data from the clinician interface
    public function remove_snippet($uuid)
    {
        // remove the keypoint from the page
        //dd($keypoint_uuid);
        // @TODO does this page have a keypoin
        // remove the keypoint from the page
        $snippet = CompiledPageSnippet::where('uuid', $uuid)->first();

        if ($snippet) {
            $snippet_id = $snippet->id;
            $page = $snippet->component->page;
            $snippet->delete();
            // log the action
            CompiledSnippetEvent::create([
                'page_id' => $page->id,
                'action' => 'remove',
                'old_value' => '',
                'new_value' => '',
                'snippet_id' => $snippet_id,
            ]);
            return response()->json(['status' => '0']);
        }
        return response()->json(['status' => '1']);
    }



    // reorder the snippets
    public function reorder_snippets(Request $request)
    {

        foreach (explode(',', $request->order) as $index=>$order) {
            if ($order > 0) {
                $snippet = CompiledPageSnippet::find($order);
                if ($snippet) {
                    $snippet->update(['order'=>$index]);
                }
            }
        }
        // get keypoints for the page, record new order
        return response()->json(['status' => '0']);
    }
}
