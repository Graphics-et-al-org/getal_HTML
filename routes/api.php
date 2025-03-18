<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Backend\PageStaticComponentsController;
use App\Http\Controllers\Clipart\ClipartController;
use App\Http\Controllers\CustomBroadcastAuthController;
use App\Http\Middleware\EnsureAuth0TokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use App\Models\Clipart\ClipartColourway;
use App\Models\Page\PageStaticComponent;

Route::post('/broadcasting/auth', [CustomBroadcastAuthController::class, 'authenticate'])->middleware(EnsureAuth0TokenIsValid::class);


// validate a token
Route::post('/extension/token', function (Request $request) {
    //dd($request->header('Authorization'));
    $token = Str::chopStart($request->header('Authorization'), 'Bearer ');
    // decode the token
    $apiURL = 'https://dev-ezz8szgu0u6skf0x.au.auth0.com/userinfo';
    $response = Http::withToken($token)->get($apiURL);
    if ($response->successful()) {
        dd($response->json()); // Returns user info as an array
    }
    // Handle errors like 401 Unauthorized
    if ($response->status() === 401) {
        dd($response->json());
        return false; // Token is invalid or expired
    }
    dd($response);
})->middleware('guest');

// Get an Ably token for client communication. See https://ably.com/docs/auth/token#ably-token
Route::post('/ablytoken', [ApiController::class, 'getAblyToken'])->middleware(EnsureAuth0TokenIsValid::class);

// handle an upload from the extension and create a job
Route::post('/extension/upload', [ApiController::class, 'uploadFromExtension'])
    ->middleware(EnsureAuth0TokenIsValid::class);

    // handle an upload from the extension and create a job
Route::post('/extension/checkjob', [ApiController::class, 'checkJobStatus'])->middleware('guest');
//->middleware(EnsureAuth0TokenIsValid::class);

// trigger jobs
Route::get(
    'triggerjobs',
    function () {
        Artisan::call('queue:work', [
            '--queue' => 'summarisedocument',
            '--tries' => 3,
            '--stop-when-empty' => true, // Ensure the worker exits after the queue is empty
        ]);
        return response()->json(['status' => 'Queue worker started and will exit when empty']);
    }
)->middleware('guest');

// list tags
Route::get('tags', [ApiController::class, 'tags'])->middleware('guest');

// Search
Route::get('static-components/searchbytagsandtext', [PageStaticComponentsController::class, 'searchByTagsAndText'])->middleware('guest');


// open clipart search (for now)
Route::get('clipart/searchbytagsandtext', [ClipartController::class, 'searchByTagsAndText']);
Route::get('clipart/cache', [ClipartController::class, 'uploadJsonToCache']);

// Tests- remove before production
// a test route to trigger a job
Route::get('/extension/testjob/template/{id}', [ApiController::class, 'testuploadFromExtension']);

// fix the uuids in the clipart colourways table
Route::get('/clipart/fixuuid', function () {
    $colourways = App\Models\Clipart\ClipartColourway::all();
    foreach ($colourways as $colourway) {
        if ($colourway->uuid == null) {
            $colourway->uuid = Str::uuid();
            $colourway->save();
        }
    }
    return response()->json(['status' => 'UUIDs fixed']);
});
