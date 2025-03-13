<?php

use App\Http\Controllers\Frontend\Pages\PagesController;
use Illuminate\Support\Facades\Route;

// no auth required, these are all public pages
Route::group([], function () {
        // Specific page
        Route::group(['prefix' => 'page/{uuid}'], function () {
            Route::get("/", [PagesController::class, 'public'])->name('page.public.show');
        });

});
