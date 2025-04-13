<?php


use App\Http\Controllers\Backend\SnippetsCategoriesController;
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
    Route::group(['namespace' => 'SnippetsCategories'], function () {
        // index
        Route::get('snippet_category', [SnippetsCategoriesController::class, 'index'])->name('snippet_category.index');

        /// show new/create page
        Route::get('snippet_category/create', [SnippetsCategoriesController::class, 'create'])->name('snippet_category.create');

        // store new
        Route::post('snippet_category/save', [SnippetsCategoriesController::class, 'store'])->name('snippet_category.store');

        // Search
        Route::get('snippet_category/search', [SnippetsCategoriesController::class, 'search']);

        // Specific page
        Route::group(['prefix' => 'snippet_category/{id}'], function () {

            Route::get('edit', [SnippetsCategoriesController::class, 'edit'])->name('snippet_category.edit');

            Route::patch('/', [SnippetsCategoriesController::class, 'update'])->name('snippet_category.update');

            Route::get('/destroy', [SnippetsCategoriesController::class, 'destroy'])->name('snippet_category.destroy');
        });
    });
});
