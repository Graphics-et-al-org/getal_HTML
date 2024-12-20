<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('translation-job', function ($user) {
//     return true;
// });

Broadcast::channel('translation-job', function ( User $user) {
    // if($user->canJoinRoom($roomId))
    //     return ['id' => $user->id, 'name' => $user->name, 'ably-capability' => ["subscribe", "history", "channel-metadata", "presence", "publish"]];

    return true;
});
