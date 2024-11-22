<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page\PagePage;
use Illuminate\Http\Request;

class PagesPagesController extends Controller
{

    // create the page
    public function create(Request $request)
    {
        dd($request);
    }

    // read the page
    public function read(Request $request)
    {
        $pagepage = PagePage::where('uuid', $request['uuid'])->firstOr(function () {
            abort('404', 'Pagepage not found');
        });
        return $pagepage->content;
        // $returnVal = json_decode($pagepage->content);

        // $returnVal->{"gjs-components"} = json_decode($returnVal->{"gjs-components"});
        // $returnVal->{"gjs-styles"} = json_decode($returnVal->{"gjs-styles"});
        // return $returnVal;
    }

    // store the page
    public function update(Request $request)
    {
        $pagepage = PagePage::where('uuid', $request['id'])->firstOr(function () {
            abort('404', 'Pagepage not found');
        });
        $pagepage->content = json_encode($request['data']);
        $pagepage->save();
        response()->json(['status' => 'success'], 200);

    }

    // destroy the page
    public function destroy(Request $request)
    {
        dd($request);
    }
}
