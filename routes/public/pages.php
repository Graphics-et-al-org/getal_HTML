<?php

use App\Http\Controllers\Frontend\Pages\CompiledPagesController;

use Illuminate\Support\Facades\Route;

// no auth required, these are all public pages
Route::group([], function () {
        // Specific page
        Route::group(['prefix' => 'page/{uuid}'], function () {
            Route::get("/", [CompiledPagesController::class, 'public_view'])->name('page.public.show');
        });

});
