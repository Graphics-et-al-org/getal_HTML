<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Page\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class TemplatesController extends Controller
{
    //
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    // show the index
    public function index(Request $request)
    {
        // get templates
        if (isset($request['tags'])) {
            if (!$request->has('search')) {
                Session::forget('admin_templates_search');
            }
            session(['admin_templates_page' => 1]);
            session(['admin_templates_tags' => $request['tags']]);
        }


        if ($request->has('search')) {
            if (!$request->has('tags')) {
                Session::forget('admin_templates_tags');
            }
            session(['admin_templates_page' => 1]);
            session(['admin_templates_search' => $request['search']]);
        }


        if (isset($request['page'])) {
            session(['admin_templates_page' => $request['page']]);
            $currentPage = session('admin_templates_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_templates_search')) {
            $request['search'] = session('admin_templates_search');
        }

        if (Session::has('admin_templates_tags')) {
            $request['tags'] = session('admin_templates_tags');
        }

        $templates = Page::where('is_template', '1')
            ->when($request->has('tags'), function ($query) use ($request) {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->whereIn('tags.id', $request['tags']);
                });
            })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('label', 'like', "%{$request['search']}%")
                        ->orWhere('description', 'LIKE', "%{$request['search']}%");
                });
            })
            ->paginate(10);

        //dd($templates);
        return view('backend.pages_templates.index')
            ->with('templates', $templates);
    }

    // show the editor
    public function edit($id)
    {
        $page = Page::findOr($id, function () {
            return view('backend.pages_templates.index');
        });
        return view('backend.pages_templates.editor')
            ->with('page', $page);
    }

    public function store(Request $request)
    {
        // dd($request);
        $page = new Page();
        $page->uuid = (string) Str::uuid();
        $page->user_id = Auth::user()->id;
        $page->is_template = 1;
        $page->label = $request->label;
        $page->description = $request->description ?? '';
        $page->content = $request->content;
        $page->html = $request->html;
        $page->css = $request->css;
        $page->save();

        $tags = [];
        // add tags, making if necessary
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!Tag::find($tag)) {
                    $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
                    $tag = $tagModel->id;
                }
                $tags[] = $tag;
            }
        }
        // sync
        $page->tags()->sync($tags);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.templates.index');;
    }

    // store the page
    public function create(Request $request)
    {
        return view('backend.pages_templates.editor');
    }



    // store the page
    public function update($id, Request $request)
    {
        $page = Page::find($id);
        $page->user_id = Auth::user()->id;
        $page->is_template = 1;
        $page->label = $request->label;
        $page->description = $request->description ?? '';
        $page->content = $request->content ?? '';
        $page->html = $request->html ?? '';
        $page->css = $request->css ?? '';
        $page->save();

        $tags = [];
        // add tags, making if necessary
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!Tag::find($tag)) {
                    if (strlen($tag) > 0) {
                        $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
                        $tag = $tagModel->id;
                    }
                }
                $tags[] = $tag;
            }
        }
        // sync
        $page->tags()->sync($tags);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.templates.index');;
    }


    // get the data
    public function data($id)
    {
        $page = Page::findOrFail($id);
        return $page->content;
    }





    // destroy the page
    public function destroy($id, Request $request)
    {
        $page = Page::findOrFail($id);

        // Detach all tags
        $page->tags()->detach();

        // Delete the page
        $page->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.templates.index');
    }
}
