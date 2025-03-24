<?php

use App\Http\Controllers\Backend\PageStaticComponentsController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Page static components Management
    Route::group(['namespace' => 'StaticComponents'], function () {
        // index
        Route::get('page_static_components', [PageStaticComponentsController::class, 'index'])->name('page_static_components.index');

        /// show new/create page
        Route::get('page_static_component/create', [PageStaticComponentsController::class, 'create'])->name('page_static_component.create');

        // store new
        Route::post('page_static_component/save', [PageStaticComponentsController::class, 'store'])->name('page_static_component.store');

        // Specific page
        Route::group(['prefix' => 'page_static_component/{id}'], function () {

            Route::get('edit', [PageStaticComponentsController::class, 'edit'])->name('page_static_component.edit');

            Route::patch('/', [PageStaticComponentsController::class, 'update'])->name('page_static_component.update');

            Route::get('/destroy', [PageStaticComponentsController::class, 'destroy'])->name('page_static_component.destroy');

            Route::get('data', [PageStaticComponentsController::class, 'data'])->name('page_static_component.data');
        });
    });
});
