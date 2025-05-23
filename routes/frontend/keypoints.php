<?php

use App\Http\Controllers\Frontend\AI\AIProcessingController;
use App\Http\Controllers\Frontend\Pages\CompiledSnippetsController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Generate Keypoint Icon
    Route::group(['namespace' => 'Keypoints'], function () {
        Route::post('/generate_keypoint_icon',  [AIProcessingController::class, 'generate_keypoint_icon'])->name('keypoint.generate_keypoint_icon');

        Route::post('/keypoint/reorder',  [CompiledSnippetsController::class, 'reorder_keypoints'])->name('keypoint.reorder_keypoints');

        Route::group(['prefix' => 'keypoint/{uuid}'], function () {

            Route::get('/remove',  [CompiledSnippetsController::class, 'remove_keypoint'])->name('keypoint.remove');

            Route::post('/update',  [CompiledSnippetsController::class, 'update_keypoint'])->name('keypoint.update');
        });
    });
});
