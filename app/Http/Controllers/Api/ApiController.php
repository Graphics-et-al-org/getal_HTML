<?php

namespace App\Http\Controllers\Api;

use Ably\AblyRest;
use Ably;
use Ably\LaravelBroadcaster\AblyBroadcaster;
use App\Events\AI\TranslationReadyEvent;
use App\Helpers\GPT\GPTHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Messaging\AblyController;
use App\Jobs\AI\SubmitTextForTranslation;
use App\Models\Clipart\Clipart;
use App\Models\Page\Page;
use App\Models\Page\PageComponent;
use App\Models\Page\PageComponentCategory;
use App\Models\Tag;
use App\Traits\AblyFunctions;
use DiDom\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use romanzipp\QueueMonitor\Models\Monitor;
use romanzipp\QueueMonitor\Enums\MonitorStatus;

class ApiController extends Controller
{

    use AblyFunctions;
    //
    /**
     * Placeholder
     * @return
     */
    public function index()
    {
        return;
    }

    /**
     *Get an token from the Ably service, to exchange messges
     *
     * @return
     */
    public function getAblyToken(Request $request)
    {
        try {
            // Initialize Ably client with the API key
            $ably = new AblyRest([
                'key' => env('ABLY_KEY'),
                //'queryTime' => true, // Use Ably's server time for all timestamp-related operations
            ]);
            // Create and return a token request
            $tokenRequest = $ably->auth->createTokenRequest();
            return response()->json($tokenRequest, 200, ['Content-Type' => 'application/json']);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     *Handle an upload from an extension
     * @return
     *     */
    public function uploadFromExtension(Request $request)
    {
        // a unique identifier
        $uuid = Str::uuid()->toString();

        // @TODO Extract string from file

        // strip PII
        $redacted = GPTHelper::anonymize($request->text);

       // dd($request->all());

        // get template:
        //@TODO this will be dynamic based on template restriction- user, team, project
        $template_id = Page::where('is_template', '1')
            ->where('template_type', 'summary')->get()->last()->id;

        // actually submit the job
        SubmitTextForTranslation::dispatch($redacted, $template_id, $uuid, Auth::user()->id, $request->categories ?? null)->onConnection('database')->onQueue('textprocess');


        // return the uuid so we can listen for it
        return response()->json(['success' => true, 'uuid' => $uuid]);
    }

    // just an infographic built from categories available to the user
    public function createInfoDocument(Request $request)
    {
        // a unique identifier
        $uuid = Str::uuid()->toString();

        // get template:
        //@TODO this will be dynamic based on template restriction- user, team, project
        $template_id = Page::where('is_template', '1')
            ->where('template_type', 'info')->get()->last()->id;

        // get the template contents
        $output = Page::find($template_id)->content;

        $categories = PageComponentCategory::whereIn('uuid', $request->categories)->get();

        // only tack them on when there's something
        if ($categories->count() > 0) {
            $componentsOutput = '';
            // tack the content on to the end
            foreach ($categories as $category) {
                foreach ($category->components as $component) {
                    $componentsOutput .= $component->content;
                }
            }

            $output = str_ireplace("{{components_container}}", $componentsOutput, $output);
        }
        // build the new page
        $outputPage = new Page();
        $outputPage->uuid = $uuid;
        $outputPage->label = 'Helpful information generated ' . Carbon::now()->toDateTimeString();
        $outputPage->group_id = null;
        $outputPage->content = $output;
        $outputPage->user_id = Auth::user()->id;
        $outputPage->job_uuid = '';
        $outputPage->save();
    }

     // Rebuild a document summary page- useful for testing
     public function rebuildSummary($uuid)
     {
        $page = Page::where('uuid', $uuid)->get()->first();
         $response_content = json_decode($page->data);
       //  dd($response_content->title);
         $template_id = Page::where('is_template', '1')
         ->where('template_type', 'summary')->get()->last()->id;
         $templateHtml = Page::find($template_id)->content;

        // get the keypoint template
        $keypointHTML = PageComponent::where('keypoint', 1)->first()->content;

        // build the output
        $output = str_ireplace(["{{title}}", "{{summary}}"], [$response_content->title, $response_content->summary], $templateHtml);

        // build and insert the keypoints
        $keypointOutput = '';
        foreach ($response_content->paired_images as $key => $value) {
            // dd($response_content['paired_images'][$key]['best_image']);
             // dd($value);
            $img =  Clipart::find($value->best_image);
            if ($img) {
                $img_path = Clipart::find($value->best_image)->baseline()->path();
            } else {
                $img_path = 'https://picsum.photos/200';
            }
            $keypointOutput .= str_ireplace(["{{image_src}}", "{{text}}"], [$img_path, $value->keypoint], $keypointHTML);
        }

        $output = str_ireplace("{{keypoints_container}}", $keypointOutput, $output);

        // extras at teh end
        // get the compoents and sort by weight
        // $categories = PageComponentCategory::whereIn('uuid', $response_content['categories'])->get();
        // $response_content['categories'] =
         $categories = $response_content->categories;

        // only tack them on when there's something
        if (count($categories) > 0) {
            $componentsOutput = '';
            // tack the content on to the end
            foreach ($categories as $category) {
                foreach ($category->components as $component) {
                    $componentsOutput .= PageComponent::find($component->id)->content;
                }
            }

            $output = str_ireplace("{{components_container}}", $componentsOutput, $output);
        } else {
            $document = new Document($output);
            $nodes = $document->find('[data-field="components-container"]');
            // Remove each node
            foreach ($nodes as $node) {
                $node->remove();
            }
            $output = $document->html();
        }

        // build the new page

        $page->label = 'Patient information generated ' . Carbon::now()->toDateTimeString();
        $page->group_id = null;
       // $outputPage->data = json_encode($response_content);
        $page->content = $output;
        $page->save();
        return response()->json(['success' => true, 'uuid' => $page->uuid]);

     }

    /**
     *Handle an upload from an extension
     * @return
     *     */
    public function testuploadFromExtension($id)
    {
        //Jay's magic happens here
        // Pass some .env secret, get a connection to the thing
        // dd($request->text);

        // a unique identifier
        $uuid = Str::uuid()->toString();

        // strip PII
        //$redacted = GPTHelper::anonymize($request->text);

        // find the first template
        //   $template_id = Page::where('is_template', '1')->first()->id;
        //   dd($template_id);

        // these wil be extracted from the template.
        $job_params = [
            "doctor_text" => 'demo test',
        ];

        SubmitTextForTranslation::dispatch($job_params, $id, $uuid, 1, true)->onConnection('database')->onQueue('textprocess');

        //dd($job);
        // send the job now. Later we'll use a queueing system
        //$exitCode = Artisan::call('queue:work', []);

        return response()->json(['success' => true, 'uuid' => $uuid]);
    }

    // expose tags search
    public function tags(Request $request)
    {
        $tags = Tag::where('text', 'like', '%' . $request->q . '%')->take(20)->get();
        $tags->transform(function ($item, $key) {
            return ['value' => $item->id, 'text' => $item->text];
        });
        return response()->json($tags);
    }

    // check the job's status, based on a uuid
    public function checkJobStatus(Request $request)
    {
        $job = Monitor::where('data', '{"uuid":"' . $request->uuid . '"}')->get()->first();
        try {
            return  response()->json(['status' => $job->status, 'uuid' => ($job->status == 1) ? Page::where('job_uuid', $request->uuid)->get()->first()->uuid : '']);
        } catch (\Exception $e) {
            return  response()->json(['status' => $e->getMessage()]);
        }
    }
}
