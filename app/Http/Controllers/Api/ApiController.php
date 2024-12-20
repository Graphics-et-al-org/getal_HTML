<?php

namespace App\Http\Controllers\Api;

use Ably\AblyRest;
use Ably;
use Ably\LaravelBroadcaster\AblyBroadcaster;
use App\Events\AI\TranslationReadyEvent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
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
    public function getAblyToken(Request $request){
        try {
            // Initialize Ably client with your API key
            $ably = new AblyRest([
                'key' => env('ABLY_KEY'),
                'queryTime' => true, // Use Ably's server time for all timestamp-related operations
            ]);
            // Generate a token request

            $token = $ably->auth->requestToken();
            // Return the token request to the client
            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
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
    public function uploadFromExtension(Request $request){
        //Jay's magic happens here
        // Pass some .env secret, get a connection to Jay's thing
        // EXPERIMENT: broadcast a message via Ably directly
        $id = 24601;
        $status = 'This is a status string';
        $event = new TranslationReadyEvent('test-channel', $id, $status);

        $ably = new AblyRest(env('ABLY_KEY'));
    try {
        $ably->channels->get('test-channel')->publish('test-event', 'Tell my wife, hello');
        return response()->json(['success' => true, 'message' => 'Broadcast sent']);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
       // dd($event);
        // broadcast($event);
        // dd($event);
        // //dd(Auth::user());
        // return response()->json(['success' => true, 'message' => 'Tell my wife, hello']);
    }


}


