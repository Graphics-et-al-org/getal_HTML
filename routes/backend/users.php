<?php

use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;
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

    Route::get('leaveimpersonation', [UserController::class, 'backend_leave_impersonate'])->name('users.impersonate.leave');

    //Specific user
    Route::group(['prefix' => 'user/{id}'], function () {
        Route::get('edit', [UserController::class, 'backend_edit'])->name('users.edit');
        Route::patch('/', [UserController::class, 'backend_update'])->name('users.update');
        Route::delete('/', [UserController::class, 'backend_destroy'])->name('users.destroy');

        // impersonate
        Route::get('impersonate', [UserController::class, 'backend_impersonate'])->name('users.impersonate');
    });
});
