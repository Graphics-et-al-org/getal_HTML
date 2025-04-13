<?php


use App\Http\Controllers\Backend\SnippetsCategoriesController;
use App\Http\Controllers\Frontend\AI\AIProcessingController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Generate Keypoint Icon
    Route::group(['namespace' => 'Categories'], function () {
        Route::get('categories/search', [SnippetsCategoriesController::class, 'search']);
        Route::post('categories/addfromuuids', [SnippetsCategoriesController::class, 'addFromUuids']);
    });
});
