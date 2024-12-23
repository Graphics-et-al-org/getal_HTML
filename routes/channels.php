<?php

use App\Http\Controllers\CustomBroadcastAuthController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Middleware\EnsureAuth0TokenIsValid;
use Illuminate\Support\Facades\Log;

// Broadcast::channel('translation-job', function ($user) {
//     return true;
// });

Route::middleware([EnsureAuth0TokenIsValid::class])->group(function () {
   // dd('here');
    Broadcast::routes();
});
// Route::post('/broadcasting/auth', [CustomBroadcastAuthController::class, 'authenticate'])->middleware(EnsureAuth0TokenIsValid::class);

Broadcast::channel('translation-status.{email}', function ($user, $email) {
    dd($email);
//   Log::info("Channel authorization attempt", [
//     'user_id' => $user->id,
//     'user_email' => $user->email,
//     'requested_email' => $email,
// ]);
  //return true;
   return $user->email ===  $email; // Authorize only the matching user. Using email as an identifier because we don't always have a user ID
});

// Broadcast::channel('test-channel', function ( User $user) {
//     // if($user->canJoinRoom($roomId))
//     //     return ['id' => $user->id, 'name' => $user->name, 'ably-capability' => ["subscribe", "history", "channel-metadata", "presence", "publish"]];

//     return true;
// });
