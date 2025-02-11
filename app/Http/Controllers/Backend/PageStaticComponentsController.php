<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page\PageStaticComponent;
use App\Models\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PageStaticComponentsController extends Controller
{
    public function index()
    {
        // get components
        $components = PageStaticComponent::all();
        return view('backend.page_static_components.index')
            ->with('components', $components);
    }

    // show the editor
    public function edit($id)
    {

        $component = PageStaticComponent::findOr($id, function () {
            return view('backend.page_static_components.index');
        });
        return view('backend.page_static_components.editor')
            ->with('component', $component);
    }


    // Show the creation page
    public function create(Request $request)
    {
        return view('backend.page_static_components.editor');
    }

    public function store(Request $request)
    {
        $component = new PageStaticComponent();
        $component->uuid =(string) Str::uuid();
        $component->user_id = Auth::user()->id;
        $component->label = $request['name'];
        $component->description = $request->description??'';
        $component->content = $request['content'];
        $component->save();

        // add tags, making if necessary
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!Tag::find($tag)) {
                    $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
                    $tag = $tagModel->id;
                }
                $component->tags()->attach($tag);
            }
        }

         session()->flash('flash_success', 'Created Successfully');
         return redirect()->route('admin.page_static_components.index');;
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

    // get the data for a snippet
    public function data($id)
    {
        $component = PageStaticComponent::findOrFail($id);
        return $component->content;
    }

     // get the html for a snippet
     public function html($id)
     {
         $component = PageStaticComponent::findOrFail($id);
         return $component->html;
     }

      // get the css for a snippet
      public function css($id)
      {
          $component = PageStaticComponent::findOrFail($id);
          return $component->css;
      }
}
