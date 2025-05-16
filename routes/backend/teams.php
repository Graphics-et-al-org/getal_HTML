<?php

use App\Http\Controllers\Auth\TeamsController;

use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;


//
// Teams Management
Route::group(['prefix' => 'teams', 'namespace' => 'Teams'], function () {
    // teams
    Route::get('/', [TeamsController::class, 'index'])->name('teams.index');

    Route::get('new', [TeamsController::class, 'new'])->name('teams.new');

    Route::post('store', [TeamsController::class, 'create'])->name('teams.store');

    // Search
    Route::get('/search', [TeamsController::class, 'search']);

    //Specific team
    Route::group(['prefix' => 'team/{id}'], function () {
        Route::get('edit', [TeamsController::class, 'edit'])->name('teams.edit');
        Route::get('membership', [TeamsController::class, 'showmembership'])->name('teams.membership');

        Route::patch('/', [TeamsController::class, 'update'])->name('teams.update');
        Route::delete('/', [TeamsController::class, 'destroy'])->name('teams.destroy');
        Route::post('addmembers', [TeamsController::class, 'addmembers'])->name('teams.members.add');
        Route::get('removemembers/{userid}', [TeamsController::class, 'removemembers'])->name('teams.members.remove');
    });
});
