<?php

use App\Http\Controllers\Clipart\ClipartColourwaysController;
use App\Http\Controllers\Clipart\ClipartController;
use Illuminate\Support\Facades\Route;

// open to the public
Route::group([], function () {

    // Asset Management
    Route::group(['namespace' => 'Clipart'], function () {

        // get a colourway by uuid
        Route::get('colourway/{uuid}', [ClipartColourwaysController::class, 'show']);

        // Specific asset
        Route::group(['prefix' => 'clipart/{id}'], function () {
            Route::get('/baseline', [ClipartController::class, 'baseline'])->name('clipart.baseline');
            Route::get('thumb/{colour?}', [ClipartController::class, 'thumb'])->name('clipart.thumb');
        });
    });
});
