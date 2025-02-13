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
        // templates
        Route::get('templates', [TemplatesController::class, 'index'])->name('templates.index');

        Route::get('template/create', [TemplatesController::class, 'create'])->name('template.create');

        Route::post('template/store', [TemplatesController::class, 'store'])->name('template.store');


        // Specific page
        Route::group(['prefix' => 'template/{id}'], function () {

            Route::get('edit', [TemplatesController::class, 'edit'])->name('template.edit');

            Route::patch('/', [TemplatesController::class, 'update'])->name('template.update');

            Route::get('/', [TemplatesController::class, 'destroy'])->name('template.destroy');
        });


        Route::get('pages', [PagesController::class, 'index'])->name('pages.index');

        Route::post('pages/store', [PagesController::class, 'create'])->name('pages.store');

        Route::post('pages/delete', [PagesController::class, 'delete'])->name('pages.delete');

        // Specific page
        Route::group(['prefix' => 'page/{id}'], function () {

            Route::get('edit', [PagesController::class, 'edit'])->name('page.edit');

            Route::get('data', [PagesController::class, 'data'])->name('page.data');

            Route::patch('update', [PagesController::class, 'update'])->name('page.update');
            //  Route::delete('/', [BoardsBackendController::class, 'destroy'])->name('board.destroy');
        });
    });
});
