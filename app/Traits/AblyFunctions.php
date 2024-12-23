<?php

namespace App\Traits;

use Ably\AblyRest;

trait AblyFunctions
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
            return (['status' => 'Message sent successfully']);
        } catch (\Exception $e) {
            return (['error' => $e->getMessage()]);
        }
    }
}
