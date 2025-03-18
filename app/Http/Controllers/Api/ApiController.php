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
use App\Models\Page\Page;
use App\Models\Tag;
use App\Traits\AblyFunctions;
use Illuminate\Http\Request;
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

        // get template- probably based on the team?
        $template_id = Page::where('is_template', '1')->get()->last()->id;

        // these wil be extracted from the template.
        // $job_params = [
        //     "doctor_text" => $request->text,
        //     "static_components"=>$request->components
        // ];

        SubmitTextForTranslation::dispatch($redacted, $template_id, $uuid, Auth::user()->id, $request->components ?? null)->onConnection('database')->onQueue('textprocess');

        // send the job now. Later we'll use a queueing system
        //$exitCode = Artisan::call('queue:work', []);

        return response()->json(['success' => true, 'uuid' => $uuid]);
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
        $job = Monitor::where('data', '{"uuid":"'.$request->uuid.'"}')->get()->first();
        try {
            return  response()->json(['status' => $job->status, 'uuid' => ($job->status == 1) ? Page::where('job_uuid', $request->uuid)->get()->first()->uuid : '']);
        } catch (\Exception $e) {
            return  response()->json(['status' => $e->getMessage()]);
        }
    }
}
