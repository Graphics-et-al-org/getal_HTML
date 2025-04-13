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

use App\Models\Page\SnippetsCategory;

class SnippetsCategoriesController extends Controller
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
                Session::forget('admin_page_component_category_tags');
            }
            session(['admin_page_component_category_page' => 1]);
            session(['admin_page_component_category_search' => $request['search']]);
        }


        if ($request->has('page')) {
            session(['admin_page_component_category_page' => $request['page']]);
            $currentPage = session('admin_page_component_category_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_page_component_category_search')) {
            $request['search'] = session('admin_page_component_category_search');
        }

        if (Session::has('admin_page_component_category_tags')) {
            $request['tags'] = session('admin_page_component_category_tags');
        }

        $categories = SnippetsCategory::when($request->has('tags'), function ($query) use ($request) {
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


        return view('backend.snippets_category.index')
            ->with('categories', $categories);
    }

    // show the editor
    public function edit($id)
    {

        $category = SnippetsCategory::findOr($id, function () {
            return view('backend.snippets_category.index');
        });
        return view('backend.snippets_category.editor')
            ->with('category', $category);
    }


    // Show the creation page
    public function create(Request $request)
    {
        return view('backend.snippets_category.editor');
    }

    public function store(Request $request)
    {
        $category = new SnippetsCategory();
        $category->label = $request->label;
        $category->description = $request->description;
        $category->uuid = (string) Str::uuid();
        $category->save();

        // add tags, making if necessary
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!Tag::find($tag)) {
                    $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
                    $tag = $tagModel->id;
                }
                $category->tags()->attach($tag);
            }
        }

        $components = explode(',', $request->components);
        $syncArr = [];
        foreach ($components as $index => $component) {
            $syncArr[$component] = ['order' => $index];
        }
        $category->snippets()->sync($syncArr);


        // sync users
        $category->users()->sync($request->users ?? []);
        // sync teams
        $category->teams()->sync($request->teams ?? []);
        // sync projects
        $category->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.snippets_category.index');
    }

    // store the thing
    public function update($id, Request $request)
    {
        // dd($request->components);
        // $components = explode(',', $request->components);
        // $syncArr = [];
        // foreach($components as $index=>$component){
        //     $syncArr[$component]=['order'=>$index];
        // }
        // dd($syncArr);
        $category = SnippetsCategory::find($id);
        $category->label = $request->label;
        $category->description = $request->description;
        $category->save();


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
        // $category->tags()->sync($tags);
        $components = explode(',', $request->components);
        $syncArr = [];
        foreach ($components as $index => $component) {
            $syncArr[$component] = ['order' => $index];
        }
        $category->snippets()->sync($syncArr);

        // sync users
        $category->users()->sync($request->users ?? []);
        // sync teams
        $category->teams()->sync($request->teams ?? []);
        // sync projects
        $category->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Updated Successfully');
        return redirect()->route('admin.snippet_category.index');;
    }

    // destroy the thing
    public function destroy($id, Request $request)
    {
        $category = SnippetsCategory::findOrFail($id);

        // Detach all tags
        //  $category->tags()->detach();
        // sync users
        $category->users()->detach();
        // sync teams
        $category->teams()->detach();
        // sync projects
        $category->projects()->detach();

        // Delete the page
        $category->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.snippets_category.index');
    }

    public function addFromUuids(Request $request)
    {
        // dd($request->all());
        $categories = SnippetsCategory::whereIn('uuid', explode(',',$request->uuids))->get();

        $componentsOutput = '';
        // tack the content on to the end
        foreach ($categories as $category) {
            foreach ($category->components as $component) {
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
        $categories = SnippetsCategory::take(50)->get();
        //   }
        $categories->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label, 'uuid' => $item->uuid];
        });
        return response()->json($categories);
    }
}
