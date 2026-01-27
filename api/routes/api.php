<?php

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/articles', function () {
    return Article::select('id', 'title', 'slug')->get()->toResourceCollection();
})->name('articles.index');

Route::get('/articles/{article:slug}', function (Article $article) {
    return $article->toResource();
})->name('articles.show');
