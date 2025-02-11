<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use Illuminate\Http\Request;

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
        return $page->content;
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
}
