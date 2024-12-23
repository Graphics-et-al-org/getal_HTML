<?php

namespace App\Http\Controllers\Messaging;

use Ably\AblyRest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class AblyController extends Controller
{
    public function sendMessage($channel, $message)
    {
        // Create an Ably client instance
        $ably = new AblyRest(env('ABLY_KEY'));

        // Publish a message to the specified channel
        try {
            $ably->channels->get($channel)->publish('event-name', [
                'data' => $message,
            ]);
            return response()->json(['status' => 'Message sent successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
