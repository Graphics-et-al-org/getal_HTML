<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Project;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Page\PageComponent;
use App\Models\Page\PageComponentCategory;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        // get components
        // get templates
        //dd($request->has('tags'));
        if ($request->has('tags')) {
            if (!$request->has('search')) {
                Session::forget('admin_projects_search');
            }
            session(['admin_projects_page' => 1]);
            session(['admin_projects_tags' => $request->tags]);
        }


        if ($request->has('search')) {
            if (!$request->has('tags')) {
                Session::forget('admin_projects_tags');
            }
            session(['admin_projects_page' => 1]);
            session(['admin_projects_search' => $request['search']]);
        }


        if ($request->has('page')) {
            session(['admin_projects_page' => $request['page']]);
            $currentPage = session('admin_projects_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_projects_search')) {
            $request['search'] = session('admin_projects_search');
        }

        if (Session::has('admin_projects_tags')) {
            $request['tags'] = session('admin_projects_tags');
        }

        $projects = Project::when($request->has('tags'), function ($query) use ($request) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->whereIn('tags.id', $request['tags']);
            });
        })
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('label', 'like', "%{$request['search']}%")
                        ->orWhere('description', 'like', "%{$request['search']}%");
                });
            })
            ->paginate(10);


        return view('backend.projects.index')
            ->with('projects', $projects);
    }

    // show the editor
    public function edit($id)
    {

        $project = Project::findOr($id, function () {
            return view('backend.projects.index');
        });
        return view('backend.projects.editor')
            ->with('project', $project);
    }


    // Show the creation page
    public function new(Request $request)
    {
        return view('backend.projects.editor');
    }

    public function store(Request $request)
    {
        $project = new Project();
        $project->label = $request->label;
        $project->description = $request->description;
        $project->save();

        // add tags, making if necessary
        // if (isset($request->tags)) {
        //     foreach ($request->tags as $tag) {
        //         if (!Tag::find($tag)) {
        //             $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
        //             $tag = $tagModel->id;
        //         }
        //         $project->tags()->attach($tag);
        //     }
        // }

        $project->users()->sync($request->users ?? []);
        // sync teams
        $project->teams()->sync($request->teams ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.projects.index');
    }

    // store the thing
    public function update($id, Request $request)
    {

        $project = Project::find($id);
        $project->label = $request->label;
        $project->description = $request->description;
        $project->save();


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


        $project->users()->sync($request->users ?? []);
        // sync teams
        $project->teams()->sync($request->teams ?? []);

        session()->flash('flash_success', 'Updated Successfully');
        return redirect()->route('admin.projects.index');;
    }

    // destroy the thing
    public function destroy($id, Request $request)
    {
        $project = Project::findOrFail($id);

        // // Detach all tags
        // $project->tags()->detach();

        $project->users()->detach();
        // sync teams
        $project->teams()->detach();

        // Delete the page
        $project->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.projects.index');
    }

    // search
    public function search(Request $request)
    {
        // dd($request->all());
        if ($request->q) {
            $projects = Project::where('label', 'like', "%{$request->q}%")->orWhere('description', 'LIKE', "%{$request->q}%")->take(20)->get();
        } else {
            $projects = Project::take(50)->get();
        }
        $projects->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label];
        });
        return response()->json($projects);
    }
}
