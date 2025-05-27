<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\TemplatesController;
use Illuminate\Support\Facades\Route;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);

Route::get('dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('templates', [TemplatesController::class, 'index'])
    ->name('templates.index');

Route::get('editor', [TemplatesController::class, 'editor'])
    ->name('templates.editor');

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
