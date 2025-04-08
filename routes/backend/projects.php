<?php

use App\Http\Controllers\Auth\TeamsController;
use App\Http\Controllers\Backend\ProjectsController;
use Illuminate\Support\Facades\Route;

//
// Teams Management
Route::group([ 'namespace' => 'Projects'], function () {
    // teams
    Route::get('projects/', [ProjectsController::class, 'index'])->name('projects.index');

    Route::get('projects/new', [ProjectsController::class, 'new'])->name('projects.new');

    Route::post('projects/store', [ProjectsController::class, 'store'])->name('projects.store');

    // Search
    Route::get('projects/search', [ProjectsController::class, 'search']);

    //Specific team
    Route::group(['prefix' => 'project/{id}'], function () {
        Route::get('edit', [ProjectsController::class, 'edit'])->name('projects.edit');
        Route::get('destroy', [ProjectsController::class, 'destroy'])->name('projects.destroy');
      //  Route::get('membership', [TeamsController::class, 'showmembership'])->name('teams.membership');
        Route::patch('/', [ProjectsController::class, 'update'])->name('projects.update');

        //Route::post('addmembers', [TeamsController::class, 'addmembers'])->name('teams.members.add');
        //Route::get('removemembers/{userid}', [TeamsController::class, 'removemembers'])->name('teams.members.remove');
    });
});
