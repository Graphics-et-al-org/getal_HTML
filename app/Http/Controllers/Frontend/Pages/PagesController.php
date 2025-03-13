<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use Illuminate\Http\Request;
use DiDom\Document;
use DiDom\Element;

class PagesController extends Controller
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

    // get the data
    public function data($id)
    {
        $page = Page::findOrFail($id);
        return ['content' => $page->content ?? "Create content here"];
    }

    // get the html
    public function html($id)
    {
        $page = Page::findOrFail($id);
        return $page->html;
    }

    // get the css
    public function css($id)
    {
        $page = Page::findOrFail($id);
        return $page->css;
    }

    // show the clinician view
    public function clinician_view($uuid)
    {
        $page = Page::where('uuid', $uuid)->first();
        // insert controls to keypoints
        $html = $page->content;
        $document = new Document($html);
        $keypoints = $document->find('.keypoint');
        $deleteButtonAttributes = ['class' => 'absolute top-0 right-0 deletebutton', 'data-delete' => 'true', 'type' => 'button'];
        $closeButton = new Element('button', '', $deleteButtonAttributes);
        $svgContent = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square" viewBox="0 0 16 16">
   <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
   <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
 </svg>';

        // Create a new Document instance to parse the SVG
        $svgDocument = new Document($svgContent);
    //    dd($svgDocument->find('svg'));
        $svgElement = $svgDocument->find('svg');

        // Append the parsed SVG element to the div
        $closeButton->appendChild($svgElement);
        foreach ($keypoints as $keypoint) {
            $keypoint->appendChild($closeButton);
        }
        //@TODO add controls to page

        return view('frontend.page.clinician_view', ['page' => $page, 'html' => $document->html()]);
    }

    // update the model from the clinician view
    public function clinican_update(Request $request)
    {


        return $request;
    }

    // approve the model from the clinician view
    public function clinican_approve(Request $request)
    {
        return $request;
    }


    public function public_view($uuid)
    {
        $page = Page::where('uuid', $uuid)->first();
        return view('public.page.public_view', ['page' => $page]);
    }
}
