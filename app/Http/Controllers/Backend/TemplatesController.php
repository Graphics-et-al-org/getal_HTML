<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use Illuminate\Http\Request;

class TemplatesController extends Controller
{
    //
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    // show the index
    public function index()
    {
        // get templates
       $templates = Page::where('is_template', '1')->get();
       //dd($templates);
        return view('backend.pages_templates.index')
        ->with('templates', $templates);
    }

    // show the editor
    public function edit($id)
    {
        $page = Page::findOr($id, function(){
            return view('backend.pages_templates.index');

        });
        return view('backend.pages_templates.editor')
        ->with('page', $page);
    }

    // store the page
    public function create(Request $request)
    {
        dd($request);
    }

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

    // destroy the page
    public function destroy(Request $request)
    {
        dd($request);
    }
}
