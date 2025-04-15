<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page\Compiled\CompiledPage;
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


        $pages = CompiledPage::when($request->has('search'), function ($query) use ($request) {
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








    // destroy the page
    public function destroy($id, Request $request)
    {
        $page = CompiledPage::findOrFail($id);

        // Detach all tags
        $page->tags()->detach();

        // Delete the page
        $page->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.pages.index');
    }
}
