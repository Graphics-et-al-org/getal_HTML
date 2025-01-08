<?php

use App\Http\Controllers\Clipart\ClipartController;
use App\Http\Controllers\Frontend\Pages\PagesController;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Clipart'], function () {
        // templates
        Route::get('clipart', [ClipartController::class, 'index'])->name('clipart.index');
        Route::get('clipart/create', [ClipartController::class, 'create'])->name('clipart.create');
        Route::post('clipart/store', [ClipartController::class, 'store'])->name('clipart.store');
        Route::post('clipart/delete', [ClipartController::class, 'delete'])->name('clipart.delete');
        Route::post('clipart/bulkimport', [ClipartController::class, 'bulkImport'])->name('clipart.bulkimport');

        // Specific page
        Route::group(['prefix' => 'clipart/{id}'], function () {
            Route::get('edit', [ClipartController::class, 'edit'])->name('clipart.edit');
        });

    });
});
