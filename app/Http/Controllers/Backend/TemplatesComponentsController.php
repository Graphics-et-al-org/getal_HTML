<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Page\Page;
use App\Models\Page\PageTemplate;
use App\Models\Page\PageTemplateComponent;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class TemplatesComponentsController extends Controller
{
    //
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    // show the index
    public function index(Request $request)
    {
        $components = PageTemplateComponent::when($request->has('tags'), function ($query) use ($request) {
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
        return view('backend.pages_template_components.index')
            ->with('components', $components);
    }

    // show the editor
    public function edit($id)
    {
        $component = PageTemplateComponent::findOr($id, function () {
            return view('backend.pages_template_components.index');
        });
        return view('backend.pages_template_components.editor_tinymce')
            ->with('component', $component);
    }

    public function store(Request $request)
    {
        // dd($request);
        $component = new PageTemplateComponent();
        $component->uuid = (string) Str::uuid();
        $component->user_id = Auth::user()->id;
        $component->label = $request->label;
        $component->description = $request->description ?? '';
        $component->content = $request->content;
        $component->type = $request->component_type ?? 'html';
        $component->save();

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
        $component->tags()->sync($tags);
        // sync users
        $component->users()->sync($request->users ?? []);
        // sync teams
        $component->teams()->sync($request->teams ?? []);
        // sync projects
        $component->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.template.components.index');;
    }

    // store the page
    public function create(Request $request)
    {
        return view('backend.pages_template_components.editor_tinymce');
    }



    // store the page
    public function update($id, Request $request)
    {

        $component = PageTemplateComponent::find($id);
        $component->label = $request->label;
        $component->description = $request->description ?? '';
        $component->content = $request->content;
        $component->type = $request->component_type ?? 'html';
        $component->save();

        // $component->html = $request->html ?? '';
        // $component->css = $request->css ?? '';
        $component->save();

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
        $component->tags()->sync($tags);
        // sync users
        $component->users()->sync($request->users ?? []);
        // sync teams
        $component->teams()->sync($request->teams ?? []);
        // sync projects
        $component->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.template.components.index');;
    }


    // get the data
    public function data($id)
    {
        $component = PageTemplateComponent::findOrFail($id);
        // dd($component);

        return ['content' => $component->content ?? "Create content here"];
    }


    // get the data
    public function metadata($id)
    {
        $component = PageTemplateComponent::findOrFail($id);
        // dd($component);

        return ['id' => $component->id, 'label' => $component->label, 'description' => $component->description, 'type' => $component->type, 'content' => $component->content ?? "Content not yet set"];
    }



    // destroy the page
    public function destroy($id, Request $request)
    {
        $component = PageTemplateComponent::findOrFail($id);

        // Detach all tags
        $component->tags()->detach();

        // Delete the page
        $component->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return ['status' => 'success'];
    }

    // search
    public function search(Request $request)
    {
        // dd($request->all());
        if ($request->q) {
            $components =  PageTemplateComponent::where('label', 'like', "%{$request->q}%")->orWhere('description', 'LIKE', "%{$request->q}%")->take(20)->get();
        } else {
            $components = PageTemplateComponent::take(50)->get();
        }
        $components->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label];
        });
        return response()->json($components);
    }
}
