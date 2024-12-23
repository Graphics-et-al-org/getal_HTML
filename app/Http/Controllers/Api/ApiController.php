<?php

namespace App\Http\Controllers\Api;

use Ably\AblyRest;
use Ably;
use Ably\LaravelBroadcaster\AblyBroadcaster;
use App\Events\AI\TranslationReadyEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Messaging\AblyController;
use App\Jobs\AI\SubmitTextForTranslation;
use App\Traits\AblyFunctions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiController extends Controller
{

    use AblyFunctions;
    //
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return;
    }

    /**
     *Handle an upload from an extension
     * @return
     *     */
    public function getAblyToken(Request $request)
    {
        try {
            // Initialize Ably client with your API key
            $ably = new AblyRest([
                'key' => env('ABLY_KEY'),
                //'queryTime' => true, // Use Ably's server time for all timestamp-related operations
            ]);
            // Generate a token request

    // Create a token request
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
        //Jay's magic happens here
        // Pass some .env secret, get a connection to Jay's thing

        // Simulated long job

        // a unique identifier
        $uuid = Str::uuid()->toString();

        // some fake job parameters
        $template_id = '69';

        // these wil be extracted from the template.
        $job_params = [
            "input_document" => 'blah blah blah',
            "system_prompt" => 'this is a system prompt',
            "prompt" => "Summarise the text from this document",
        ];

        $job = SubmitTextForTranslation::dispatch($job_params, $template_id, $uuid, Auth::user()->id)->onConnection('database')->onQueue('summarisedocument')  ;

       // dd($job);
        // send the job now. Later we'll use a queueing system
        //$exitCode = Artisan::call('queue:work', []);

        // $ably = new AblyRest(env('ABLY_KEY'));
        // try {
        //     $ably->channels->get('test-channel')->publish('test-event', 'Tell my wife, hello');
        //     return response()->json(['success' => true, 'message' => 'Broadcast sent']);
        // } catch (\Exception $e) {
        //     return response()->json(['success' => false, 'error' => $e->getMessage()]);
        // }
        // dd($event);
       //  broadcast($event);
        // dd($event);
        // //dd(Auth::user());
         return response()->json(['success' => true, 'uuid' => $uuid]);
    }
}
