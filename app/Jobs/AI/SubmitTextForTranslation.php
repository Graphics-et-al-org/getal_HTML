<?php

namespace App\Jobs\AI;

use Carbon\Carbon;
use DiDom\Element;
use DiDom\Document;
use App\Models\Page\Page;
use Illuminate\Support\Str;
use App\Traits\AblyFunctions;
use App\Models\Clipart\Clipart;
use App\Models\Page\Compiled\CompiledPage;
use App\Models\Page\Compiled\CompiledPageComponent;
use App\Models\Page\Compiled\CompiledPageSnippet;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Page\PageComponent;
use App\Models\Page\PageComponentCategory;
use App\Models\Page\PageTemplate;
use App\Models\Page\PageTemplateComponent;
use App\Models\Page\Snippet;
use App\Models\Page\SnippetsCategory;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use romanzipp\QueueMonitor\Traits\IsMonitored;



class SubmitTextForTranslation implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels, AblyFunctions, IsMonitored;

    protected $_params;
    protected $_uuid;
    protected $_user_id;
    protected $_inputStr;
    protected $_simulated;
    protected $_template_id;
    protected $_extras;


    //👇 Making the timeout larger
    public $timeout = 300;

    public $job_uuid;

    /**
     * Create a new job instance.
     */
    public function __construct($inputStr, $template_id, $uuid, $user_id, $extras = [], $simulated = false)
    {
        $this->_inputStr = $inputStr;
        $this->_uuid = $uuid;
        $this->_template_id = $template_id;
        $this->_user_id = $user_id;
        $this->_extras = $extras;
        $this->_simulated = $simulated;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        // dd('handling');
        $result_uuid = Str::uuid()->toString();

        $this->queueData(['uuid' =>  $this->_uuid]);
        //
        $payload = array("doctor_text" => $this->_inputStr);
        $apiURL = env('AI_MAIN_FUNC_URL');

        if ($this->_simulated) {
            // some dummy data if simulated
            $response_content = array(
                'title' => 'Feeling Down and Tired Help',
                'summary' => 'You have been feeling low, tired, and unmotivated for about two weeks. This may be due to mild to moderate depression. We are starting treatment with medication and therapy to help you feel better. Follow these steps and let us know if things get worse.',
                'keypoints' =>
                array(
                    0 => 'Take your medication (escitalopram 10 mg) once daily as prescribed.',
                    1 => 'Start therapy (CBT) to talk about your feelings and get support.',
                    2 => 'Try to improve your sleep by going to bed and waking up at the same time.',
                    3 => 'If you can’t sleep, let us know. We may give you a low-dose sleep aid.',
                    4 => 'Come back in 2-3 weeks or sooner if you feel worse.',
                    5 => 'Tell us if you have side effects or changes in your mood.',
                ),
                'paired_images' =>
                array(
                    0 =>
                    array(
                        'keypoint' => 'Take your medication (escitalopram 10 mg) once daily as prescribed.',
                        'tags' => 'oral medication, daily use',
                        'best_image' => '18',
                        'similarity_score' => 999.0,
                    ),
                    1 =>
                    array(
                        'keypoint' => 'Start therapy (CBT) to talk about your feelings and get support.',
                        'tags' => 'therapy, mental health support',
                        'best_image' => '33',
                        'similarity_score' => 999.0,
                    ),
                    2 =>
                    array(
                        'keypoint' => 'Try to improve your sleep by going to bed and waking up at the same time.',
                        'tags' => 'sleep improvement, routine',
                        'best_image' => '298',
                        'similarity_score' => 999.0,
                    ),
                    3 =>
                    array(
                        'keypoint' => 'If you can’t sleep, let us know. We may give you a low-dose sleep aid.',
                        'tags' => 'sleep aid, report issues',
                        'best_image' => '1572',
                        'similarity_score' => 999.0,
                    ),
                    4 =>
                    array(
                        'keypoint' => 'Come back in 2-3 weeks or sooner if you feel worse.',
                        'tags' => 'follow-up, worsening symptoms',
                        'best_image' => '-1',
                        'similarity_score' => 999.0,
                    ),
                    5 =>
                    array(
                        'keypoint' => 'Tell us if you have side effects or changes in your mood.',
                        'tags' => 'side effects, mood changes',
                        'best_image' => '982',
                        'similarity_score' => 999.0,
                    ),
                ),
            );
            $this->_extras = ['86278671-67fe-4a4d-a214-2479c79fee4c', 'e6543121-d947-4b4e-8d3d-59e1607b80fd'];
            // some categories
            $response_content['categories'] = $this->_extras;
            //$this->_extras = [4, 5];
        } else {
            // or else some real data
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post($apiURL, $payload);
            if ($response->successful()) {
                $response_content = $response->json();
            } else {
                // log teh failure, send feedback
                $this->sendMessage('translation-status.' . $this->_uuid, json_encode(['message' => 'failure']));
                return;
            }
        }
        // build an output
        // get the template, the assumption is it has the required 'data-' fields
        Log::info('Using template:' . $this->_template_id);
        // make a page from a template
        $template = PageTemplate::find($this->_template_id);
        // dd($template->page_templates_components);
        if (!$template) {
            Log::error('Template not found:' . $this->_template_id);
            $this->sendMessage('translation-status.' . $this->_uuid, json_encode(['message' => 'failure']));
            return;
        }

        // build the compiled page
        $outputPage = new CompiledPage(
            [
                'uuid' => $result_uuid,
                'label' => 'Patient information generated ' . Carbon::now()->toDateTimeString(),
                'content' => $template->content,
                'user_id' => $this->_user_id,
                'job_uuid' => $this->_uuid,
                'released_at' => null,
                'from_template_id' => $template->id,
                'header' => $template->header,
                'footer' => $template->footer,
                'title' => $response_content['title'],
                'summary' => $response_content['summary'],
                'data' => json_encode($response_content),
                'css' => $template->css,
            ]
        );

        $outputPage->save();

        Log::info('Output page id:' . $outputPage->id);

        // populate the page with the components

        foreach ($template->page_templates_components as $templateComponent) {
            Log::info('templateComponent id:' . $templateComponent->id);
            Log::info('templateComponent type:' . $templateComponent->type);
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
                case 'keypoints':
                    Log::info('Building keypoints: from');
                    Log::info($response_content['paired_images']);
                    // Build the keypoints
                    foreach ($response_content['paired_images'] as $key => $value) {
                        // get the keypoint template- TODO pick template based on authorisation
                        $templateKeypointSnippet = Snippet::where('keypoint', 1)->first();
                        $keypointSnippet = new CompiledPageSnippet(
                            [
                                'uuid' => Str::uuid()->toString(),
                                'type' => 'keypoint',
                                'order' => $templateKeypointSnippet->order ?? $key,
                                'content' => $templateKeypointSnippet->content,
                                'compiled_page_components_id' => $pageComponent->id,
                                'from_template_id' => $templateKeypointSnippet->id,
                            ]
                        );

                        // replace the text
                        $keypointSnippet->content = str_ireplace("{{text}}", $value['keypoint'], $keypointSnippet->content);
                        // replace the image src
                        $img = Clipart::find($value['best_image']);
                        if ($img) {
                            $img_path = Clipart::find($value['best_image'])->baseline()->path();
                        } else {
                            $img_path = 'https://picsum.photos/200';
                        }
                        // replace the image src
                        $keypointSnippet->content = str_ireplace("{{image_src}}", $img_path, $keypointSnippet->content);
                        $keypointSnippet->save();
                    }
                    break;
                case 'snippets':
                    foreach ($this->_extras as $category_uuid) {
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
        
        $this->sendMessage('translation-status.' . $this->_uuid, json_encode(['message' => 'success', 'uuid' => $result_uuid, 'content' => $response_content]));
        return;
    }




    private function replaceContentByAttribute($html, $attribute, $replacements)
    {
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // Suppress errors for invalid HTML
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);

        foreach ($replacements as $value => $newContent) {
            $nodes = $xpath->query("//*[@$attribute='$value']");
            foreach ($nodes as $node) {
                $newFragment = $dom->createDocumentFragment();
                $newFragment->appendXML($newContent);
                $node->nodeValue = ''; // Clear existing content
                $node->appendChild($newFragment);
            }
        }

        return $dom->saveHTML();
    }
}
