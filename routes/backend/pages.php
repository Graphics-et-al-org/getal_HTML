<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use App\Http\Controllers\Backend\PagesController;

use Illuminate\Support\Facades\Route;



//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Pages'], function () {

        Route::get('pages', [PagesController::class, 'index'])->name('pages.index');

        Route::post('pages/store', [PagesController::class, 'create'])->name('pages.store');

        Route::post('pages/delete', [PagesController::class, 'delete'])->name('pages.delete');

        // // Specific page
        Route::group(['prefix' => 'page/{id}'], function () {

            Route::get('edit', [PagesController::class, 'edit'])->name('page.edit');

            Route::get('data', [PagesController::class, 'data'])->name('page.data');

            Route::patch('update', [PagesController::class, 'update'])->name('page.update');

            Route::get('/', [PagesController::class, 'destroy'])->name('page.destroy');
            //  Route::delete('/', [BoardsBackendController::class, 'destroy'])->name('board.destroy');
        });
    });
});
