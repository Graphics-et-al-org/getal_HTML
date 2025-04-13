<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page\Compiled\Page;
use App\Models\Tag;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class PagesController extends Controller
{
    //
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    // show the index
    public function index(Request $request)
    {
        // get templates
        // if (isset($request['tags'])) {
        //     if (!$request->has('search')) {
        //         Session::forget('admin_pages_search');
        //     }
        //     session(['admin_pages_page' => 1]);
        //     session(['admin_pages_tags' => $request['tags']]);
        // }


        if (isset($request['page'])) {
            session(['admin_pages_page' => $request['page']]);
            $currentPage = session('admin_pages_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_pages_search')) {
            $request['search'] = session('admin_pages_search');
        }


        $pages = Page::all()

            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('label', 'like', "%{$request['search']}%")
                        ->orWhere('description', 'LIKE', "%{$request['search']}%");
                });
            })
            ->paginate(10);

        //dd($templates);
        return view('backend.page.index')
            ->with('pages', $pages);
    }

    // show the editor
    public function edit($id)
    {
        $page = Page::findOr($id, function () {
            return view('backend.pages_templates.index');
        });
        return view('backend.pages_templates.editor_tinymce')
            ->with('page', $page);
    }






    // // store the page
    // public function update($id, Request $request)
    // {
    //     $page = Page::find($id);
    //     $page->user_id = Auth::user()->id;
    //     $page->label = $request->label;
    //     $page->description = $request->description ?? '';
    //     $page->content = $request->content ?? '';
    //    // $page->html = $request->html ?? '';
    //    // $page->css = $request->css ?? '';
    //     $page->save();

    //     $tags = [];
    //     // add tags, making if necessary
    //     if (isset($request->tags)) {
    //         foreach ($request->tags as $tag) {
    //             if (!Tag::find($tag)) {
    //                 if (strlen($tag) > 0) {
    //                     $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
    //                     $tag = $tagModel->id;
    //                 }
    //             }
    //             $tags[] = $tag;
    //         }
    //     }
    //     // sync
    //     $page->tags()->sync($tags);

    //     session()->flash('flash_success', 'updated Successfully');
    //     return redirect()->route('admin.page.index');
    // }


    // get the data
    // public function data($id)
    // {
    //     $page = Page::findOrFail($id);
    //  //   dd(['content' => $page->content ?? "Create content here"]);
    //     return response()->json(['content' => $page->content ?? "Create content here"]);
    // }





    // destroy the page
    public function destroy($id, Request $request)
    {
        $page = Page::findOrFail($id);

        // Detach all tags
        $page->tags()->detach();

        // Delete the page
        $page->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.pages.index');
    }
}
