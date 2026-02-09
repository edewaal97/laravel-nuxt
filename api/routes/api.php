<?php

declare(strict_types=1);

use App\Http\Controllers\Api\ArticleController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/articles', function () {
    return Article::select('id', 'title', 'slug')->get()->toResourceCollection();
})->name('articles.index');

Route::post('/articles', [ArticleController::class, 'store'])
    ->name('articles.store');

Route::get('/articles/{article:slug}', function (Article $article) {
    return $article->toResource();
})->name('articles.show');

Route::put('/articles/{article:slug}', [ArticleController::class, 'update'])
    ->name('articles.update');

Route::delete('/articles/{article:slug}', [ArticleController::class, 'destroy'])
    ->name('articles.destroy');
