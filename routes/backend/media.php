<?php


use App\Http\Controllers\Media\MediaController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Media'], function () {
        // templates
        Route::get('media', [MediaController::class, 'index'])->name('media.index');
        Route::get('media/create', [MediaController::class, 'create'])->name('media.create');
        Route::post('media/store', [MediaController::class, 'store'])->name('media.store');

        Route::post('media/tinymce_store', [MediaController::class, 'tinymce_store'])->name('media.tinymce_store');


        // Specific clipart
        Route::group(['prefix' => 'media/{id}'], function () {
            Route::get('edit', [MediaController::class, 'edit'])->name('media.edit');

            Route::patch('/', [MediaController::class, 'update'])->name('media.update');

            Route::get('/', [MediaController::class, 'destroy'])->name('media.destroy');
        });
    });
});
