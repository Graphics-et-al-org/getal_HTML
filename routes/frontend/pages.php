<?php


use App\Http\Controllers\Frontend\Pages\CompiledPagesController;

use Illuminate\Support\Facades\Route;


//
Route::group([], function () {
    // Pages Management
    Route::group(['namespace' => 'Pages'], function () {


        // Specific page
        Route::group(['prefix' => 'page/{uuid}'], function () {

            Route::get('/', [CompiledPagesController::class, 'clinician_view'])->name('page.clinician_view');

            Route::get('update', [CompiledPagesController::class, 'clinician_update'])->name('page.clinican_update');

            Route::get('approve', [CompiledPagesController::class, 'clinician_approve'])->name('page.clinican_approve');

            Route::get('share_view', [CompiledPagesController::class, 'share_view'])->name('page.share_view');

            Route::post('add_keypoint/{keypoint_id}',  [CompiledPagesController::class, 'add_keypoint'])->name('page.add_keypoint');

            Route::get('qrcode', [CompiledPagesController::class, 'getQRcode'])->name('page.qrcode');

        });

        Route::get('page/remove_keypoint/{uuid}',  [CompiledPagesController::class, 'remove_keypoint'])->name('page.remove_keypoint');

    });
});
