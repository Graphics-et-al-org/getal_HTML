<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Compiled\CompiledPage;
use App\Models\Page\Page;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DiDom\Document;
use DiDom\Element;

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
    public function clinician_view($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        // insert controls to keypoints
        $html = $page->content;
        // $document = new Document($html);
        // $keypoints = $document->find('.keypoint');
        // $components = $document->find('.component');

        //   dd($components);
        //         $deleteButtonAttributes = ['class' => 'absolute top-0 right-0 deletebutton', 'data-delete' => 'true', 'type' => 'button'];
        //         $deleteButton = new Element('button', '', $deleteButtonAttributes);

        //         $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
        //    <path stroke="red" d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
        //    <path stroke="red" stroke-width="2"  d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
        //  </svg>';

        // Create a new Document instance to parse the SVG
        //         $svgDocument = new Document($svgContent);
        //         //    dd($svgDocument->find('svg'));
        //         $svgElement = $svgDocument->find('svg');

        //         // Append the parsed SVG element to the div
        //         $deleteButton->appendChild($svgElement);
        //         foreach ($keypoints as $keypoint) {
        //             $keypoint->appendChild($deleteButton);
        //         }

        //         foreach ($components as $component) {
        //             $component->appendChild($deleteButton);
        //         }

        //         $addKeypointButtonAttributes = ['style' => 'cursor:pointer', 'type' => 'button', 'id'=>'addKeypointButton'];

        //         $addKeypointButton = new Element('button', '', $addKeypointButtonAttributes);

        //         // add an add keypont control\
        //         $keypointContent = '<div role="button" tabindex="0"
        //   class="self-auto relative grid w-48 min-h-48 border border-solid border-2 border-gray-500 rounded-md addbutton "
        //   onclick="openAddKeypointModal()">
        //   <div class="col-span-full m-0 p-2">
        //     <div
        //       class="h-32 w-full border border-solid border-2 border-red-500 rounded-md mb-2 flex items-center justify-center">
        //       <svg xmlns="http://www.w3.org/2000/svg" viewBox="-4 -4 24 24" width="128"
        //         height="128" fill="#b0bec5" class="bi bi-plus-lg">
        //         <path fill-rule="evenodd"
        //           d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2">
        //         </path>
        //       </svg>
        //     </div>
        //     <div
        //       class="min-h-12 w-full border border-solid border-2 border-red-500 rounded-md text-center">
        //       Add keypoint
        //     </div>
        //   </div>
        // </div>';

        //         // Create a new Document instance to parse the SVG
        //         $addKeypointDocument = new Document($keypointContent);

        //         $addKeypointElement = $addKeypointDocument->find('div')[0];

        //         $addKeypointButton->appendChild($addKeypointElement);

        // //dd($addKeypointButton->html());
        //         $keypointContainer = $document->find('.keypoints')[0];

        //         $keypointContainer->appendChild($addKeypointButton);

        //         //@TODO add controls to page
        //         $pageData = json_decode($page->data);

        //         $used_icons = array_map(function ($item) {
        //             return $item->best_image;
        //         }, $pageData->paired_images);

        return view('frontend.page.clinician_view', ['page' => $page]);
    }


    // add a keypoint to the data from the clinician interface
    public function add_keypoint(Request $request, $uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();


        return response()->json(['status' => '0']);
    }

    // add a keypoint to the data from the clinician interface
    public function remove_keypoint(Request $request, $uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();

        return response()->json(['status' => '0']);
    }

    // update the model from the clinician view
    public function clinican_update(Request $request)
    {
        return $request;
    }

    // approve the model from the clinician view
    public function clinician_approve($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->first();
        //   dd($page);
        $page->released_at = Carbon::now();
        $page->save();
        return response()->json(['status' => '0']);
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
}
