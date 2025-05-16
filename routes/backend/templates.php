<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\TemplatesComponentsController;
use App\Http\Controllers\Frontend\Pages\PagesPagesController;
use Illuminate\Support\Facades\Route;



//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Templates'], function () {
        // templates
        Route::get('templates', [TemplatesController::class, 'index'])->name('templates.index');

        Route::get('template/create', [TemplatesController::class, 'create'])->name('template.create');

        Route::post('template/store', [TemplatesController::class, 'store'])->name('template.store');


        // Specific template
        Route::group(['prefix' => 'template/{id}'], function () {

            Route::get('data', [TemplatesController::class, 'data'])->name('template.data');

            Route::get('edit', [TemplatesController::class, 'edit'])->name('template.edit');

            Route::patch('/', [TemplatesController::class, 'update'])->name('template.update');

            Route::delete('/', [TemplatesController::class, 'destroy'])->name('template.destroy');
        });

        // template components
        Route::get('template/components', [TemplatesComponentsController::class, 'index'])->name('template.components.index');
        Route::get('template/component/create', [TemplatesComponentsController::class, 'create'])->name('template.component.create');
        Route::get('template/component/search', [TemplatesComponentsController::class, 'search'])->name('template.component.search');
        Route::post('template/component/store', [TemplatesComponentsController::class, 'store'])->name('template.component.store');

         // Specific template component
         Route::group(['prefix' => 'template/component/{id}'], function () {

            Route::get('data', [TemplatesComponentsController::class, 'data'])->name('template.component.data');

            Route::get('metadata', [TemplatesComponentsController::class, 'metadata'])->name('template.component.metadata');

            Route::get('edit', [TemplatesComponentsController::class, 'edit'])->name('template.component.edit');

            Route::patch('/', [TemplatesComponentsController::class, 'update'])->name('template.component.update');

            Route::delete('/', [TemplatesComponentsController::class, 'destroy'])->name('template.component.destroy');
        });

    });
});
