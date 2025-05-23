<?php

use App\Http\Controllers\Frontend\AI\AIProcessingController;
use App\Http\Controllers\Frontend\Pages\CompiledSnippetsController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Generate Keypoint Icon
    Route::group(['namespace' => 'Keypoints'], function () {


        Route::post('/snippets/reorder',  [CompiledSnippetsController::class, 'reorder_snippets'])->name('snippet.reorder_snippets');

        Route::group(['prefix' => 'snippet/{uuid}'], function () {

            Route::get('/remove',  [CompiledSnippetsController::class, 'remove_keypoint'])->name('snippet.remove');

            Route::post('/update',  [CompiledSnippetsController::class, 'update_keypoint'])->name('snippet.update');
        });
    });
});
