<?php

use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use App\Http\Controllers\Frontend\Pages\PagesController;
use App\Http\Controllers\Frontend\Pages\PagesPagesController;
use Tabuna\Breadcrumbs\Trail;


//
// Users Management
Route::group(['prefix' => 'users', 'namespace' => 'Users'], function () {
    // users
    Route::get('/', [UserController::class, 'backend_index'])->name('users.index');
    Route::get('new', [UserController::class, 'backend_new'])->name('users.new');
    Route::post('store', [UserController::class, 'backend_create'])->name('users.store');

    // exposing some supporting funcitonality
    Route::get('checkemail', [UserController::class, 'checkEmail'])->name('users.checkemail');
    Route::get('search', [UserController::class, 'searchusers'])->name('users.search');

    //Specific user
    Route::group(['prefix' => 'user/{id}'], function () {
        Route::get('edit', [UserController::class, 'backend_edit'])->name('users.edit');

        Route::patch('/', [UserController::class, 'backend_update'])->name('users.update');
        Route::delete('/', [UserController::class, 'backend_destroy'])->name('users.destroy');
    });
});
