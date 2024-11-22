<?php

namespace App\Http\Controllers\Frontend\Pages;

use App\Http\Controllers\Controller;
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

}
