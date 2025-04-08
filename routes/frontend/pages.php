<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use App\Http\Controllers\Frontend\Pages\PagesController;
use App\Http\Controllers\Frontend\Pages\PagesPagesController;
use Illuminate\Support\Facades\Route;


//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Pages'], function () {
        // Specific page
        Route::group(['prefix' => 'page/{uuid}'], function () {


            Route::get('/', [PagesController::class, 'clinician_view'])->name('page.clinician_view');

            Route::get('update', [PagesController::class, 'clinician_update'])->name('page.clinican_update');

            Route::get('approve', [PagesController::class, 'clinician_approve'])->name('page.clinican_approve');

            Route::post('add_keypoint',  [PagesController::class, 'add_keypoint_to_data'])->name('page.add_keypoint_to_data');
        });

    });
});
