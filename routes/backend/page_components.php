<?php

use App\Http\Controllers\Backend\PageComponentsCategoriesController;
use App\Http\Controllers\Backend\PageComponentsController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Page static components Management
    Route::group(['namespace' => 'PageComponents'], function () {
        // index
        Route::get('page_components', [PageComponentsController::class, 'index'])->name('page_components.index');

        /// show new/create page
        Route::get('page_component/create', [PageComponentsController::class, 'create'])->name('page_component.create');

        // store new
        Route::post('page_component/save', [PageComponentsController::class, 'store'])->name('page_component.store');

        // Search
        Route::get('page_component/search', [PageComponentsController::class, 'search']);


        // Specific page
        Route::group(['prefix' => 'page_component/{id}'], function () {

            Route::get('/edit', [PageComponentsController::class, 'edit'])->name('page_component.edit');

            Route::patch('/', [PageComponentsController::class, 'update'])->name('page_component.update');

            Route::get('/destroy', [PageComponentsController::class, 'destroy'])->name('page_component.destroy');

            Route::get('/data', [PageComponentsController::class, 'data'])->name('page_component.data');
            // Metadata
            Route::get('/metadata', [PageComponentsController::class, 'metadata'])->name('page_component.metadata');
        });
    });
});
//
Route::group([], function () {
    // Page static components Management
    Route::group(['namespace' => 'PageComponentCategories'], function () {
        // index
        Route::get('page_component_category', [PageComponentsCategoriesController::class, 'index'])->name('page_component_category.index');

        /// show new/create page
        Route::get('page_component_category/create', [PageComponentsCategoriesController::class, 'create'])->name('page_component_category.create');

        // store new
        Route::post('page_component_category/save', [PageComponentsCategoriesController::class, 'store'])->name('page_component_category.store');

        // Search
        Route::get('page_component_category/search', [PageComponentsCategoriesController::class, 'search']);

        // Specific page
        Route::group(['prefix' => 'page_component_category/{id}'], function () {

            Route::get('edit', [PageComponentsCategoriesController::class, 'edit'])->name('page_component_category.edit');

            Route::patch('/', [PageComponentsCategoriesController::class, 'update'])->name('page_component_category.update');

            Route::get('/destroy', [PageComponentsCategoriesController::class, 'destroy'])->name('page_component_category.destroy');
        });
    });
});
