<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Middleware\EnsureAuth0TokenIsValid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;




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
Route::post('/ablytoken',[ApiController::class, 'getAblyToken']);//->middleware(EnsureAuth0TokenIsValid::class);


// handle an upload from the extension and create a job
Route::post('/extension/upload',[ApiController::class, 'uploadFromExtension'])
->middleware(EnsureAuth0TokenIsValid::class);
