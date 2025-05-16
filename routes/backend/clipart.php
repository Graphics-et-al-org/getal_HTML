<?php

use App\Http\Controllers\Clipart\ClipartController;

use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Clipart'], function () {
        // templates
        Route::get('clipart', [ClipartController::class, 'index'])->name('clipart.index');
        Route::get('clipart/create', [ClipartController::class, 'create'])->name('clipart.create');
        Route::post('clipart/store', [ClipartController::class, 'store'])->name('clipart.store');

        Route::post('clipart/bulkimport', [ClipartController::class, 'bulkImport'])->name('clipart.bulkimport');
        Route::post('clipart/refreshallaimetadata', [ClipartController::class, 'refreshAllAiMetadata']);
        Route::post('clipart/processpendingaimetadata', [ClipartController::class, 'processPendingAiMetadata']);

        // Specific clipart
        Route::group(['prefix' => 'clipart/{id}'], function () {
            Route::get('edit', [ClipartController::class, 'edit'])->name('clipart.edit');

            Route::patch('/', [ClipartController::class, 'update'])->name('clipart.update');

            Route::get('/', [ClipartController::class, 'destroy'])->name('clipart.destroy');
        });
    });
});
