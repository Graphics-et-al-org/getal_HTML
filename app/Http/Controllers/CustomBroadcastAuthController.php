<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;



class CustomBroadcastAuthController extends Controller
{
    //
    public function authenticate(Request $request)
    {
        // Custom logic to authenticate the user for the channel

        $user = $request->user();
      //  dd($user);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $channelName = $request->input('channel_name');

        //if ($this->isAuthorized($user, $channelName)) {
            $apiKey = env('ABLY_API_KEY');
            $apiSecret = env('ABLY_API_SECRET');

            $signature = hash_hmac('sha256', $request->input('clientId') . ':' . $channelName, $apiSecret);

            return response()->json([
                'auth' => $apiKey . ':' . $signature,
                'token'=>Broadcast::auth($request)['token']
            ]);
       // }

        // Custom validation based on the channel name
        // $channelName = $request->input('channel_name');
        // if ($this->isAuthorized($user, $channelName)) {
        //dd(Broadcast::auth($request));
      //  $auth = Broadcast::auth($request);
//dd($auth);
        //return Broadcast::auth($request); // Use Laravel's built-in channel authorization
        // }

        // return response()->json(['error' => 'Forbidden'], 403);
    }

    // private function isAuthorized($user, $channelName)
    // {
    //     // Example: Allow access only if the channel matches the user's ID or role
    //     if ($user->email === $this->extractEmailFromChannel($channelName)) {
    //         return true;
    //     }

    //     return false;
    // }

    // private function extractEmailFromChannel($channelName)
    // {
    //     // Extract email from channel name (example logic, adjust as needed)
    //     $parts = explode('.', $channelName);
    //     return end($parts);
    // }
}
