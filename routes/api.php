<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\Auth0TokenController;
use App\Http\Controllers\Backend\SnippetsCollectionsController;
use  App\Http\Controllers\Frontend\Pages\Analytics\CompiledPagesAnalyticsController;
use App\Http\Controllers\Clipart\ClipartController;
use App\Http\Controllers\CustomBroadcastAuthController;
use App\Http\Middleware\EnsureAuth0TokenIsValid;
use App\Http\Middleware\ValidatePublicJWT;
use App\Http\Middleware\VerifyApiAuth0Jwt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post('/broadcasting/auth', [CustomBroadcastAuthController::class, 'authenticate'])->middleware(EnsureAuth0TokenIsValid::class);


// validate a token
// Route::post('/extension/token', function (Request $request) {
//     //dd($request->header('Authorization'));
//     $token = Str::chopStart($request->header('Authorization'), 'Bearer ');
//     // decode the token
//     $apiURL = 'https://dev-ezz8szgu0u6skf0x.au.auth0.com/userinfo';
//     $response = Http::withToken($token)->get($apiURL);
//     if ($response->successful()) {
//         dd($response->json()); // Returns user info as an array
//     }
//     // Handle errors like 401 Unauthorized
//     if ($response->status() === 401) {
//         dd($response->json());
//         return false; // Token is invalid or expired
//     }
//     dd($response);
// })->middleware('guest');

// Get an Ably token for client communication. See https://ably.com/docs/auth/token#ably-token
Route::post('/ablytoken', [ApiController::class, 'getAblyToken'])->middleware(EnsureAuth0TokenIsValid::class);

// handle an upload from the extension and create a job
Route::post('/extension/upload', [ApiController::class, 'uploadFromExtension'])
    ->middleware(EnsureAuth0TokenIsValid::class);

// handle an upload from the extension and create a job
Route::post('/extension/info', [ApiController::class, 'createInfoDocument'])
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

// Search- @TODO lock down using tokens or something
Route::get('collections/search', [SnippetsCollectionsController::class, 'search'])->middleware('guest');


// open clipart search (for now)
Route::get('clipart/searchbytagsandtext', [ClipartController::class, 'searchByTagsAndText']);
Route::get('clipart/cache', [ClipartController::class, 'uploadJsonToCache']);


// Tests- remove before production
// a test route to trigger a job
Route::get('/extension/testjob/template/{id}', [ApiController::class, 'testuploadFromExtension'])->middleware('guest');


// rebuild a page from a template
//Route::get('/rebuild/page/{uuid}', [ApiController::class, 'rebuildSummary'])->middleware('guest');

// fix the uuids in the clipart colourways table
// Route::get('/clipart/fixuuid', function () {
//     $colourways = App\Models\Clipart\ClipartColourway::all();
//     foreach ($colourways as $colourway) {
//         if ($colourway->uuid == null) {
//             $colourway->uuid = Str::uuid();
//             $colourway->save();
//         }
//     }
//     return response()->json(['status' => 'UUIDs fixed']);
// });

// Public, non authenticated routes (which we're still tracking)
// analytics capture- with JWT authentication to prevent some shenanigans
Route::post('page/{uuid}/analytics/flush', [CompiledPagesAnalyticsController::class, 'get_flush'])->middleware(ValidatePublicJWT::class);

/**
 * Machine to machine routes
 *
 *
 *
 */
Route::post('/get_token', [Auth0TokenController::class, 'getToken'])->name('api.get_token');

Route::middleware(VerifyApiAuth0Jwt::class)->group(function () {
    // check a token
    Route::post('/checktoken', function (Request $request) {
        //dd(Auth::user());
        return response()->json(['status' => 'Token is valid']);
    });
    // @TODO get available collections for this client
    Route::get('/collections', [ApiController::class, 'getAvailableCollections']);

    // @TODO submit a request for a page
});

