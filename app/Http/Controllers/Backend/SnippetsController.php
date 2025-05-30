<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Page\Snippet;

class SnippetsController extends Controller
{
    public function index(Request $request)
    {
        // get components
        // get templates
        //dd($request->has('tags'));
        if ($request->has('tags')) {
            if (!$request->has('search')) {
                Session::forget('admin_static_component_search');
            }
            session(['admin_static_component_page' => 1]);
            session(['admin_static_component_tags' => $request->tags]);
        }


        if ($request->has('search')) {
            if (!$request->has('tags')) {
                Session::forget('admin_static_component_tags');
            }
            session(['admin_static_component_page' => 1]);
            session(['admin_static_component_search' => $request['search']]);
        }


        if ($request->has('page')) {
            session(['admin_static_component_page' => $request['page']]);
            $currentPage = session('admin_static_component_page');
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
        }

        if (Session::has('admin_static_component_search')) {
            $request['search'] = session('admin_static_component_search');
        }

        if (Session::has('admin_static_component_tags')) {
            $request['tags'] = session('admin_static_component_tags');
        }

        $snippets = Snippet::when($request->has('tags'), function ($query) use ($request) {
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


        return view('backend.snippets.index')
            ->with('snippets', $snippets);
    }

    // show the editor
    public function edit($id)
    {

        $snippet = Snippet::findOr($id, function () {
            return view('backend.snippets.index');
        });
        // dd($snippet);
        return view('backend.snippets.editor_tinymce')
            ->with('snippet', $snippet);
    }


    // Show the creation page
    public function create(Request $request)
    {
        return view('backend.snippets.editor_tinymce');
    }

    public function store(Request $request)
    {
        $snippet = new Snippet();
        $snippet->uuid = (string) Str::uuid();
        $snippet->user_id = Auth::user()->id;
        $snippet->label = $request->label;
        $snippet->description = $request->description ?? '';
        $snippet->content = $request->content ?? '';
        $snippet->html = $request->html ?? '';
        $snippet->css = $request->css ?? '';
        $snippet->weight = $request->weight ?? 0;
        $snippet->keypoint = $request->keypoint ?? null;
        $snippet->snippet_template = $request->snippet_template ?? null;
        $snippet->save();

        // add tags, making if necessary
        if (isset($request->tags)) {
            foreach ($request->tags as $tag) {
                if (!Tag::find($tag)) {
                    $tagModel = \App\Models\Tag::firstOrCreate(['text' => $tag]);
                    $tag = $tagModel->id;
                }
                $snippet->tags()->attach($tag);
            }
        }

        // sync users
        $snippet->users()->sync($request->users ?? []);
        // sync teams
        $snippet->teams()->sync($request->teams ?? []);

        $snippet->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Created Successfully');
        return redirect()->route('admin.snippets.index');
    }

    // store the thing
    public function update($id, Request $request)
    {
        $component = Snippet::find($id);
        $component->user_id = Auth::user()->id;
        $component->label = $request->label;
        $component->description = $request->description ?? '';
        $component->content = $request->content ?? '';
        $component->html = $request->html ?? '';
        $component->css = $request->css ?? '';
        $component->weight = $request->weight ?? 0;
        $component->keypoint = $request->keypoint == 'true' ?? null;
        $component->snippet_template = $request->snippet_template == 'true' ?? null;
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

        $component->projects()->sync($request->projects ?? []);

        session()->flash('flash_success', 'Updated Successfully');
        return redirect()->route('admin.snippets.index');;
    }

    // destroy the thing
    public function destroy($id, Request $request)
    {
        $component = Snippet::findOrFail($id);

        // Detach all tags
        $component->tags()->detach();

        // Delete the page
        $component->delete();

        session()->flash('flash_success', 'Deleted Successfully');
        return redirect()->route('admin.snippets.index');
    }

    // get the data for a snippet
    public function data($id)
    {
        $snippet = Snippet::findOrFail($id);
        return ['content' => $snippet->content ?? "Create content here"];
    }

    // get some info for a snippet- for when we add it to a list
    public function metadata($id)
    {
        $component = Snippet::findOrFail($id);
        return ['id' => $component->id, 'label' => $component->label, 'description' => $component->description];
    }

    // get the html for a snippet
    public function html($id)
    {
        $component = Snippet::findOrFail($id);
        return $component->html;
    }

    // get the css for a snippet
    public function css($id)
    {
        $component = Snippet::findOrFail($id);
        return $component->css;
    }

    // search by tags and text
    public function searchByTagsAndText(Request $request)
    {
        // dd($request->all());
        $components = Snippet::where('keypoint', false)
            ->when($request->has('tags'), function ($query) use ($request) {
                $query->whereHas('tags', function ($q) use ($request) {
                    $q->whereIn('tags.text', $request['tags']);
                });
            })->when($request->has('search'), function ($query) use ($request) {

                $query->where(function ($q) use ($request) {
                    $q->where('label', 'like', "%{$request['search']}%")
                        ->orWhere('description', 'LIKE', "%{$request['search']}%");
                });
                //    dd($query);
            })->get();
        //     dd($components->toBase());
        $output = [];
        $components->each(function ($item, $key) use (&$output) {
            // @TODO make baseline path
            $appendobj = array(
                'uuid' => $item->uuid,
                'label' => $item->label,
                'description' => $item->description,
                'tags' => $item->tags->pluck('text')->join(','),
            );
            $output[] = $appendobj;
        });
        return $output;
    }

    // search
    public function reorder(Request $request)
    {
        // dd($request->all());
        if ($request->q) {
            $components =  Snippet::where('keypoint', false)->where('label', 'like', "%{$request->q}%")->orWhere('description', 'LIKE', "%{$request->q}%")->take(20)->get();
        } else {
            $components = Snippet::take(50)->get();
        }
        $components->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label];
        });
        return response()->json($components);
    }

    // search
    public function search(Request $request)
    {
        // dd($request->all());
        if ($request->q) {
            $components =  Snippet::where('keypoint', false)->where('label', 'like', "%{$request->q}%")->orWhere('description', 'LIKE', "%{$request->q}%")->take(20)->get();
        } else {
            $components = Snippet::take(50)->get();
        }
        $components->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->label];
        });
        return response()->json($components);
    }

    // mass create snippets from a CSV file
    public function massCreateFromCSV(Request $request)
    {

        $input = $request->all();

        if (!empty($request->file('csvfile'))) {
            $template = $request->input('template_id', Snippet::where('snippet_template', true)->first()->id ?? null);
            $file = $request->file('csvfile');

            if (($handle = fopen($file, "r")) !== FALSE) {
                // loop through the CSV
                while (($data = fgetcsv($handle)) !== FALSE) {
                    // get the first row, work out the indices from teh headers
                    switch($data[0]) {
                        case 'school_code':
                            $schoolcoderow = 0;
                            break;
                        case 'school_description':
                            $schooldescrow = 1;
                            break;
                        case 'course_code':
                            $course_coderow = 2;
                            break;
                        case 'title':
                            $titlerow = 3;
                            break;
                        case 'abbreviation':
                            $abbreviationrow = 4;
                            break;
                        case 'course_status':
                            $course_statusrow = 5;
                            break;
                    }
                    // check that the school exists- populate from here a bit. We'll fix it up later
                    $school = School::updateOrCreate(
                        ['code' => $data[$schoolcoderow]],
                        ['code' => $data[$schoolcoderow], 'description' => $data[$schooldescrow]]
                    );
                    // populate the course table
                    $course = Course::updateOrCreate(
                        ['course_code' => $data[$course_coderow]],
                        [
                            'course_code' => $data[$course_coderow],
                            'label' => $data[$titlerow],
                            'abbreviation' => $data[$abbreviationrow],
                            'school_id' => $school->id,
                            'course_status' => $data[$course_statusrow]
                        ]
                    );
                }
            }
        } else {
            return array(
                'error' => '1',
                'value' => "No file!"
            );
        }

        return array(
            'status' => '0',

        );
    }
}
