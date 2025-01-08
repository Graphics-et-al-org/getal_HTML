<?php

use App\Http\Controllers\Clipart\ClipartController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Asset Management
    Route::group(['namespace' => 'Clipart'], function () {
      //  Route::get('clipart/thumb/{id}/{colour?}', [\App\Http\Controllers\Frontend\Graphics\ClipartController::class, 'thumb'])->name('clipart.thumb');;
        // Specific asset
        Route::group(['prefix' => 'clipart/{id}'], function () {
            Route::get('thumb/{colour?}', [ClipartController::class, 'thumb'])->name('clipart.thumb');
        });
    });
});
