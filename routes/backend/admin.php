<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);

Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('admin.dashboard'));
    });


Route::get('templates', [TemplatesController::class, 'index'])
    ->name('templates.index');
// ->breadcrumbs(function (Trail $trail) {
//     $trail->push(__('Home'), route('admin.dashboard'));
//}
//);

Route::get('editor', [TemplatesController::class, 'editor'])
    ->name('templates.editor');

