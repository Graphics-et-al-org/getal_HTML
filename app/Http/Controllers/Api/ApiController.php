<?php

namespace App\Http\Controllers\Api;

use Ably\AblyRest;
use Ably;
use Ably\LaravelBroadcaster\AblyBroadcaster;
use App\Events\AI\TranslationReadyEvent;
use App\Helpers\File\FileContentsHelper;
use App\Helpers\GPT\GPTHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Messaging\AblyController;
use App\Jobs\AI\SubmitTextForTranslation;
use App\Models\Page\Compiled\CompiledPage;
use App\Models\Page\Compiled\CompiledPageComponent;
use App\Models\Page\Compiled\CompiledPageSnippet;
use App\Models\Page\Page;
use App\Models\Page\PageTemplate;
use App\Models\Page\SnippetsCategory;
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

        // get template:
        //@TODO this will be dynamic based on template restriction- user, team, project
        $template_id = PageTemplate::where('is_template', '1')
            ->where('template_type', 'summary')->get()->last()->id;

        return response()->json(['status' => 0, 'uuid' => $this->_createSummaryPage($template_id, Auth::user()->id, $request->categories, $request->text, $request->file)]);
    }

    // Create a page: keypoint summary if there's a file or doctor text, an info document if there's no file
    // @TODO work out who teh user is
    public function createPageFromApi(Request $request)
    {
        $categories = explode(',', $request->categories);
        $snippets = explode(',', $request->snippets);
        if ($request->text || $request->file) {
            // get template:
            //@TODO this will be dynamic based on template restriction- user, team, project
            $template_id = PageTemplate::where('template_type', 'summary')->get()->last()->id;
            return response()->json(['status' => 0, 'type' => 'summary', 'uuid' => $this->_createSummaryPage($template_id, Auth::user()->id, $request->categories, $request->text, $request->file)]);
        } else {
            // make an info document, return a link to it
            $template_id = PageTemplate::where('template_type', 'info')->get()->last()->id;
            return response()->json(['status' => 0, 'type' => 'info', 'uuid' => $this->_createInfoDocument($template_id, $categories, $snippets, $request->title, Auth::user()->id)]);
        }
    }







    // Rebuild a document summary page- useful for testing
    public function rebuildSummary($uuid)
    {
        $page = CompiledPage::where('uuid', $uuid)->get()->first();
        //      $response_content = json_decode($page->data);
        //    //  dd($response_content->title);
        //      $template_id = Page::where('is_template', '1')
        //      ->where('template_type', 'summary')->get()->last()->id;
        //      $templateHtml = Page::find($template_id)->content;

        //     // get the keypoint template
        //     $keypointHTML = PageComponent::where('keypoint', 1)->first()->content;

        //     // build the output
        //     $output = str_ireplace(["{{title}}", "{{summary}}"], [$response_content->title, $response_content->summary], $templateHtml);

        //     // build and insert the keypoints
        //     $keypointOutput = '';
        //     foreach ($response_content->paired_images as $key => $value) {
        //         // dd($response_content['paired_images'][$key]['best_image']);
        //          // dd($value);
        //         $img =  Clipart::find($value->best_image);
        //         if ($img) {
        //             $img_path = Clipart::find($value->best_image)->baseline()->path();
        //         } else {
        //             $img_path = 'https://picsum.photos/200';
        //         }
        //         $keypointOutput .= str_ireplace(["{{image_src}}", "{{text}}"], [$img_path, $value->keypoint], $keypointHTML);
        //     }

        //     $output = str_ireplace("{{keypoints_container}}", $keypointOutput, $output);

        //     // extras at teh end
        //     // get the compoents and sort by weight
        //     // $categories = PageComponentCategory::whereIn('uuid', $response_content['categories'])->get();
        //     // $response_content['categories'] =
        //      $categories = $response_content->categories;

        //     // only tack them on when there's something
        //     if (count($categories) > 0) {
        //         $componentsOutput = '';
        //         // tack the content on to the end
        //         foreach ($categories as $category) {
        //             foreach ($category->components as $component) {
        //                 $componentsOutput .= PageComponent::find($component->id)->content;
        //             }
        //         }

        //         $output = str_ireplace("{{components_container}}", $componentsOutput, $output);
        //     } else {
        //         $document = new Document($output);
        //         $nodes = $document->find('[data-field="components-container"]');
        //         // Remove each node
        //         foreach ($nodes as $node) {
        //             $node->remove();
        //         }
        //         $output = $document->html();
        //     }

        //     // build the new page

        //     $page->label = 'Patient information generated ' . Carbon::now()->toDateTimeString();
        //     $page->group_id = null;
        //    // $outputPage->data = json_encode($response_content);
        //     $page->content = $output;
        //     $page->save();
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

        SubmitTextForTranslation::dispatch('Simulation', $id, $uuid, 1, [], true)->onConnection('database')->onQueue('textprocess');

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


    //////////////////////////////////////////////////////////////
    // private functions
    /////////////////////////////////////////////////////////////

    // internal function to create a summary page
    private function _createSummaryPage($template_id, $user_id, $categories, $text = null, $file = null)
    {
        // a unique identifier
        $uuid = Str::uuid()->toString();

        $content = '';
        // if there's  a file, extract the text from it
        if (isset($file)) {

            // extract the string
            $content = FileContentsHelper::extractContent($file);
        } elseif (isset($text)) {
            // if there's no file, use the text
            $content = $text;
        }
        // strip PII
        if (strlen($content) > 0) {
            // strip PII
            $redacted = GPTHelper::anonymize($content);
        } else {
            // if there's no content, return an error
            return response()->json(['status' => 0, 'error' => 'No content provided'], 400);
        }
        $redacted = GPTHelper::anonymize($content);


        // actually submit the job
        SubmitTextForTranslation::dispatch($redacted, $template_id, $uuid,  $user_id, $categories ?? null)->onConnection('database')->onQueue('textprocess');

        // return the uuid so we can listen for it
        return $uuid;
        //  return response()->json(['success' => true, 'uuid' => $uuid]);


    }

    //internal function to create an info document
    private function _createInfoDocument($template_id, $categories, $snippets, $title, $user_id)
    {
        // a unique identifier
        $uuid = Str::uuid()->toString();

        // get template:
        //@TODO this will be dynamic based on template restriction- user, team, project
        $template = PageTemplate::find($template_id);

        // build the compiled page
        $outputPage = new CompiledPage(
            [
                'uuid' => $uuid,
                'label' => 'Patient information generated ' . Carbon::now()->toDateTimeString(),
                'content' => $template->content,
                'user_id' => $user_id,
                'job_uuid' => $uuid,
                'released_at' => null,
                'from_template_id' => $template_id,
                'header' => $template->header,
                'footer' => $template->footer,
                'title' => $title,
                'summary' => '',
                'data' => '',
                'css' => $template->css,
            ]
        );

        $outputPage->save();


        // populate the page with the components

        foreach ($template->page_templates_components as $templateComponent) {

            // add the component to the page
            $pageComponent = new CompiledPageComponent(
                [
                    'uuid' => Str::uuid()->toString(),
                    'type' => $templateComponent->type,
                    'order' => $templateComponent->pivot->order,
                    'content' => $templateComponent->content,
                    'compiled_page_id' => $outputPage->id,
                    'from_page_template_components_id' => $templateComponent->id,
                ]
            );

            $pageComponent->save();

            // get the keypoint component and populate
            switch ($templateComponent->type) {
                case 'snippets':
                    foreach ($categories as $category_uuid) {
                        // get the category
                        $category = SnippetsCategory::where('uuid', $category_uuid)->first();
                        if ($category) {
                            // get the snippets in the category
                            $snippets = $category->snippets;
                            foreach ($snippets as $snippet) {
                                // add the snippet to the page
                                $pageSnippet = new CompiledPageSnippet(
                                    [
                                        'uuid' => Str::uuid()->toString(),
                                        'type' => 'snippet',
                                        'order' => $snippet->pivot->order,
                                        'content' => $snippet->content,
                                        'compiled_page_components_id' => $pageComponent->id,
                                        'from_template_id' => $snippet->id,
                                    ]
                                );
                                // replace the image src
                                $pageSnippet->save();
                            }
                        }
                    }
                    break;
                default:
                    // handle other component types if needed
                    break;
            }
        }

        return $uuid;
    }
}
