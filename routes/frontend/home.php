<?php

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

use App\Http\Controllers\Frontend\DashboardController;
use Illuminate\Support\Facades\Route;

Route::group(
    ['middleware' => ['auth', 'verified']],
    function () {
        Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard');
    }
);
