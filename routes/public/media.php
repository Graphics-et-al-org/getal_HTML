<?php

use App\Http\Controllers\Clipart\ClipartColourwaysController;
use App\Http\Controllers\Clipart\ClipartController;
use App\Http\Controllers\Media\MediaController;
use Illuminate\Support\Facades\Route;

// open to the public
Route::group([], function () {

    // Asset Management
    Route::group(['namespace' => 'Media'], function () {
        // get a media by uuid
        // stream a media (audio)
        Route::get('media/{uuid}/stream', [MediaController::class, 'stream'])->name('media.stream');
        // display a media (image)
        Route::get('media/{uuid}/show', [MediaController::class, 'show'])->name('media.show');
        // show a thumbnail
        Route::get('media/{uuid}/thumb/{size}', [MediaController::class, 'thumb'])->name('media.thumb');;

    });
});
