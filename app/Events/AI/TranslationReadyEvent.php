<?php

namespace App\Events\AI;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TranslationReadyEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channelName;
    public $id;
    public $status;
    /**
     * Create a new event instance.
     */
    public function __construct($channelName, $id, $status)
    {
        //
        $this->channelName = $channelName;
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // use email as an identifier, we don't always know user id
        //return [new Channel('translation-status.' . Auth::user()->email)];
        // return [new PrivateChannel('private-channel')];
        //return [new PrivateChannel($this->channelName)];
        // return [
        //new Channel('translation-job.'.$this->id),
        //  new PrivateChannel('translation-job'),
        //  ];
        return [];
    }

    public function broadcastAs()
    {
        return 'translationcomplete.event';
    }
}
