<?php


use App\Http\Controllers\Backend\SnippetsCollectionsController;
use App\Http\Controllers\Backend\SnippetsController;
use App\Models\Page\SnippetsCategory;
use Illuminate\Support\Facades\Route;

//
Route::group([], function () {
    // Page static components Management
    Route::group(['namespace' => 'Snippets'], function () {
        // index
        Route::get('snippets', [SnippetsController::class, 'index'])->name('snippets.index');

        /// show new/create page
        Route::get('snippet/create', [SnippetsController::class, 'create'])->name('snippet.create');

        // store new
        Route::post('snippet/save', [SnippetsController::class, 'store'])->name('snippet.store');

        // Search
        Route::get('snippet/search', [SnippetsController::class, 'search']);


        // Specific page
        Route::group(['prefix' => 'snippet/{id}'], function () {

            Route::get('/edit', [SnippetsController::class, 'edit'])->name('snippet.edit');

            Route::patch('/', [SnippetsController::class, 'update'])->name('snippet.update');

            Route::get('/destroy', [SnippetsController::class, 'destroy'])->name('snippet.destroy');

            Route::get('/data', [SnippetsController::class, 'data'])->name('snippet.data');
            // Metadata
            Route::get('/metadata', [SnippetsController::class, 'metadata'])->name('snippet.metadata');
        });
    });
});
//
Route::group([], function () {
    // Page static components Management
    Route::group(['namespace' => 'SnippetsCollections'], function () {
        // index
        Route::get('snippet_collection', [SnippetsCollectionsController::class, 'index'])->name('snippet_collection.index');

        /// show new/create page
        Route::get('snippet_collection/create', [SnippetsCollectionsController::class, 'create'])->name('snippet_collection.create');

        // store new
        Route::post('snippet_collection/save', [SnippetsCollectionsController::class, 'store'])->name('snippet_collection.store');

        // Search
        Route::get('snippet_collection/search', [SnippetsCollectionsController::class, 'search']);

        // Specific page
        Route::group(['prefix' => 'snippet_collection/{id}'], function () {

            Route::get('edit', [SnippetsCollectionsController::class, 'edit'])->name('snippet_collection.edit');

            Route::patch('/', [SnippetsCollectionsController::class, 'update'])->name('snippet_collection.update');

            Route::get('/destroy', [SnippetsCollectionsController::class, 'destroy'])->name('snippet_collection.destroy');
        });
    });
});
