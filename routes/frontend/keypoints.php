<?php

use App\Http\Controllers\Frontend\AI\AIProcessingController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Generate Keypoint Icon
    Route::group(['namespace' => 'Keypoints'], function () {
        Route::post('/generate_keypoint_icon',  [AIProcessingController::class, 'generate_keypoint_icon'])->name('keypoint.generate_keypoint_icon');
    });
});
