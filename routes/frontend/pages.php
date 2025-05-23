<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Frontend\Pages\CompiledPagesController;

use Illuminate\Support\Facades\Route;


//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Pages'], function () {

        // internal create page, using teh API controller
        Route::post('/createpage', [ApiController::class, 'createPageFromApi']);


        // Specific page
        Route::group(['prefix' => 'page/{uuid}'], function () {

            Route::get('/', [CompiledPagesController::class, 'clinician_view'])->name('page.clinician_view');

            Route::get('summary_update', [CompiledPagesController::class, 'summary_update'])->name('page.summary_update');

            Route::get('approve', [CompiledPagesController::class, 'clinician_approve'])->name('page.clinican_approve');

            Route::get('share_view', [CompiledPagesController::class, 'share_view'])->name('page.share_view');

            Route::post('add_keypoint/',  [CompiledPagesController::class, 'add_keypoint'])->name('page.add_keypoint');
            

            Route::post('add_collections/',  [CompiledPagesController::class, 'add_collections'])->name('page.add_collections');

            Route::get('qrcode', [CompiledPagesController::class, 'getQRcode'])->name('page.qrcode');
        });
    });
});
