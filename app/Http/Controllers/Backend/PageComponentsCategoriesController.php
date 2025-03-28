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
use App\Models\Page\PageComponentCategory;

class PageComponentsCategoriesController extends Controller
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

        $categories = PageComponentCategory::when($request->has('tags'), function ($query) use ($request) {
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


        return view('backend.page_component_category.index')
            ->with('categories', $categories);
    }

    // show the editor
    public function edit($id)
    {

        $category = PageComponentCategory::findOr($id, function () {
            return view('backend.page_component_category.index');
        });
        return view('backend.page_component_category.editor')
            ->with('category', $category);
    }


    // Show the creation page
    public function create(Request $request)
    {
        return view('backend.page_component_category.editor');
    }

    public function store(Request $request)
    {
        $category = new PageComponentCategory();
        $category->label = $request->label;
        $category->description = $request->description;
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
        foreach($components as $index=>$component){
            $syncArr[$component]=['order'=>$index];
        }
        $category->components()->sync($syncArr);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.page_component_category.index');
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
        $category = PageComponentCategory::find($id);
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
        foreach($components as $index=>$component){
            $syncArr[$component]=['order'=>$index];
        }
        $category->components()->sync($syncArr);

        session()->flash('flash_success', 'Updated Successfully');
        return redirect()->route('admin.page_component_category.index');;
    }

    // destroy the thing
    public function destroy($id, Request $request)
    {
        $component = PageComponent::findOrFail($id);

        // Detach all tags
        $component->tags()->detach();

        // Delete the page
        $component->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.page_component_category.index');
    }

    // search
    public function search(Request $request)
    {
        // dd($request->all());
        if ($request->q) {
            $categories = PageComponentCategory::where('label', 'like', "%{$request->q}%")->orWhere('description', 'LIKE', "%{$request->q}%")->take(20)->get();
        } else {
            $categories = PageComponentCategory::take(50)->get();
        }
        $categories->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label];
        });
        return response()->json($categories);
    }
}
