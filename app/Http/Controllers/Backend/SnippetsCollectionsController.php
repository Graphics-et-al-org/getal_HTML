<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Page\PageComponent;

use App\Models\Page\SnippetsCollection;

class SnippetsCollectionsController extends Controller
{
    public function index(Request $request)
    {
        // get components
        // get templates
        //dd($request->has('tags'));
        if ($request->has('tags')) {
            if (!$request->has('search')) {
                Session::forget('admin_page_component_category_search');
            }
            session(['admin_static_component_category_page' => 1]);
            session(['admin_static_component_category_tags' => $request->tags]);
        }


        if ($request->has('search')) {
            if (!$request->has('tags')) {
                Session::forget('admin_page_component_collection_tags');
            }
            session(['admin_page_component_category_page' => 1]);
            session(['admin_page_component_category_search' => $request['search']]);
        }


        if ($request->has('page')) {
            session(['admin_page_component_collection_page' => $request['page']]);
            $currentPage = session('admin_page_collection_category_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_page_collection_category_search')) {
            $request['search'] = session('admin_page_collection_category_search');
        }

        if (Session::has('admin_page_collection_category_tags')) {
            $request['tags'] = session('admin_page_collection_category_tags');
        }

        $collections = SnippetsCollection::when($request->has('tags'), function ($query) use ($request) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.id', $request['tags']);
            });
        })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('text', 'like', "%{$request['search']}%");
                });
            })
            ->paginate(10);


        return view('backend.snippets_collection.index')
            ->with('collections', $collections);
    }

    // show the editor
    public function edit($id)
    {

       $collection = SnippetsCollection::findOr($id, function () {
            return view('backend.snippets_collection.index');
        });
        return view('backend.snippets_collection.editor')
            ->with('collection',$collection);
    }


    // Show the creation page
    public function create(Request $request)
    {
        return view('backend.snippets_collection.editor');
    }

    public function store(Request $request)
    {
       $collection = new SnippetsCollection();
       $collection->label = $request->label;
       $collection->description = $request->description;
       $collection->uuid = (string) Str::uuid();
       $collection->save();

        // add tags, making if necessary
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!Tag::find($tag)) {
                    $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
                    $tag = $tagModel->id;
                }
               $collection->tags()->attach($tag);
            }
        }

        $components = explode(',', $request->components);
        $syncArr = [];
        foreach ($components as $index => $component) {
            $syncArr[$component] = ['order' => $index];
        }
       $collection->snippets()->sync($syncArr);


        // sync users
       $collection->users()->sync($request->users ?? []);
        // sync teams
       $collection->teams()->sync($request->teams ?? []);
        // sync projects
       $collection->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.snippet_collection.index');
    }

    // store the thing
    public function update($id, Request $request)
    {
         dd($request->all());
        // $components = explode(',', $request->components);
        // $syncArr = [];
        // foreach($components as $index=>$component){
        //     $syncArr[$component]=['order'=>$index];
        // }
        // dd($syncArr);
       $collection = SnippetsCollection::find($id);
       $collection->label = $request->label;
       $collection->description = $request->description;
       $collection->save();


        // $tags = [];
        // // add tags, making if necessary
        // if (isset($request->tags)) {
        //     foreach ($request->tags as $tag) {
        //         if (!Tag::find($tag)) {
        //             if (strlen($tag) > 0) {
        //                 $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
        //                 $tag = $tagModel->id;
        //             }
        //         }
        //         $tags[] = $tag;
        //     }
        // }
        // sync
        //$collection->tags()->sync($tags);
        $components = explode(',', $request->components);
        $syncArr = [];
        foreach ($components as $index => $component) {
            $syncArr[$component] = ['order' => $index];
        }
       $collection->snippets()->sync($syncArr);
        // sync users
       $collection->users()->sync($request->users ?? []);
        // sync teams
       $collection->teams()->sync($request->teams ?? []);
        // sync projects
       $collection->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Updated Successfully');
        return redirect()->route('admin.snippet_collection.index');;
    }

    // destroy the thing
    public function destroy($id, Request $request)
    {
       $collection = SnippetsCollection::findOrFail($id);

        // Detach all tags
        // $collection->tags()->detach();
        // sync users
       $collection->users()->detach();
        // sync teams
       $collection->teams()->detach();
        // sync projects
       $collection->projects()->detach();

        // Delete the page
       $collection->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.snippet_collection.index');
    }

    public function addFromUuids(Request $request)
    {
        // dd($request->all());
        $collections = SnippetsCollection::whereIn('uuid', explode(',',$request->uuids))->get();

        $componentsOutput = '';
        // tack the content on to the end
        foreach ($collections as $collection) {
            foreach ($collection->components as $component) {
                $componentsOutput .= $component->content;
            }
        }

        return response()->json(['status' => '0', 'html' => $componentsOutput], 200);

    }


    // search
    public function search(Request $request)
    {
        // dd($request->all());
        //    if ($request->q) {
        //       $categories = SnippetsCategory::where('label', 'like', "%{$request->q}%")->orWhere('description', 'LIKE', "%{$request->q}%")->take(20)->get();
        // } else {
        $collections = SnippetsCollection::take(50)->get();
        //   }
        $collections->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label, 'uuid' => $item->uuid];
        });
        return response()->json($collections);
    }
}
