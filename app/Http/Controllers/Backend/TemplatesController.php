<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Page\Page;
use App\Models\Page\PageTemplate;
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

        $templates = PageTemplate
            ::when($request->has('tags'), function ($query) use ($request) {
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
        $template = PageTemplate::findOr($id, function () {
            return view('backend.pages_templates.index');
        });
        return view('backend.pages_templates.editor_tinymce')
            ->with('template', $template);
    }

    public function store(Request $request)
    {
        // dd($request);
        $template = new PageTemplate();
        $template->uuid = (string) Str::uuid();
        $template->user_id = Auth::user()->id;
        //$template->is_template = 1;
        $template->label = $request->label;
        $template->description = $request->description ?? '';
        $template->header = $request->header ?? '';
        $template->footer = $request->footer ?? '';
        $template->css = $request->css ?? '';
       // $template->content = $request->content;
        $template->template_type = $request->template_type ?? 'summary';
        // $template->html = $request->html;
        // $template->css = $request->css;
        $template->save();

        $components = explode(',', $request->components);
        $syncArr = [];
        foreach ($components as $index => $component) {
            $syncArr[$component] = ['order' => $index];
        }
        $template->page_templates_components()->sync($syncArr);

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
        $template->tags()->sync($tags);
        // sync users
        $template->users()->sync($request->users ?? []);
        // sync teams
        $template->teams()->sync($request->teams ?? []);
        // sync projects
        $template->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.templates.index');;
    }

    // create the page
    public function create(Request $request)
    {
        return view('backend.pages_templates.editor_tinymce');
    }



    // store the page
    public function update($id, Request $request)
    {

        $template = PageTemplate::find($id);
        $template->user_id = Auth::user()->id;

        $template->label = $request->label;
        $template->description = $request->description ?? '';
        $template->header = $request->header ?? '';
        $template->footer = $request->footer ?? '';
        $template->css = $request->css ?? '';
       // $template->content = $request->content;
        $template->template_type = $request->template_type ?? 'summary';
        // $template->html = $request->html ?? '';
        // $template->css = $request->css ?? '';
        $template->save();

        $components = explode(',', $request->components);
        $syncArr = [];
        foreach ($components as $index => $component) {
            $syncArr[$component] = ['order' => $index];
        }
        $template->page_templates_components()->sync($syncArr);

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
        $template->tags()->sync($tags);
        // sync users
        $template->users()->sync($request->users ?? []);
        // sync teams
        $template->teams()->sync($request->teams ?? []);
        // sync projects
        $template->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.templates.index');;
    }


    // get the data
    public function data($id)
    {
        $template = PageTemplate::findOrFail($id);
        // dd($template);

        return ['header' => $template->header ?? "Create header here",
        'footer'=> $template->footer ?? "Create footer here",
     ];
    }



    // destroy the page
    public function destroy($id, Request $request)
    {
        $template = PageTemplate::findOrFail($id);

        // Detach all tags
        $template->tags()->detach();

        // Delete the page
        $template->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return ['status' => 'success'];
    }
}
