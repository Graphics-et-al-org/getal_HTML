<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page\Page;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


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
        $page->content = $request->content?? '';
        $page->html = $request->html?? '';
        $page->css = $request->css?? '';
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
