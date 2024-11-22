<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use App\Http\Controllers\Frontend\Pages\PagesController;
use App\Http\Controllers\Frontend\Pages\PagesPagesController;
use Tabuna\Breadcrumbs\Trail;


//
Route::group([
], function () {
    // Pages Management
    Route::group(['namespace' => 'Pages'], function () {
        // templates
        Route::get('templates', [TemplatesController::class, 'index'])->name('templates.index');

       // Specific page
          Route::group(['prefix' => 'template/{id}'], function () {
            Route::get('edit', [TemplatesController::class, 'create'])->name('template.create');
            //    Route::get('/', [BoardsBackendController::class, 'show'])->name('board.show');
            Route::get('edit', [TemplatesController::class, 'edit'])->name('template.edit');

            Route::patch('/', [TemplatesController::class, 'update'])->name('template.update');
            //  Route::delete('/', [BoardsBackendController::class, 'destroy'])->name('board.destroy');
        });

        Route::get('pages', [PagesController::class, 'index'])->name('pages.index');
        Route::post('pages/store', [PagesController::class, 'create'])->name('pages.store');
       // Route::post('pages/copy', [PagesController::class, 'copy'])->name('board.copy');
        Route::post('pages/delete', [PagesController::class, 'delete'])->name('pages.delete');

        // Specific page
        Route::group(['prefix' => 'page/{id}'], function () {
            //    Route::get('/', [BoardsBackendController::class, 'show'])->name('board.show');
            Route::get('edit', [PagesController::class, 'edit'])->name('page.edit');

            Route::patch('update', [PagesController::class, 'update'])->name('page.update');
            //  Route::delete('/', [BoardsBackendController::class, 'destroy'])->name('board.destroy');
        });

         // Specific page
         Route::group(['prefix' => 'pagepage/{uuid}'], function () {
            //    Route::get('/', [BoardsBackendController::class, 'show'])->name('board.show');
            Route::get('data', [PagesPagesController::class, 'read'])->name('pagepage.data');

            Route::patch('update', [PagesPagesController::class, 'update'])->name('page.update');
            //  Route::delete('/', [BoardsBackendController::class, 'destroy'])->name('board.destroy');
        });


    });
});


