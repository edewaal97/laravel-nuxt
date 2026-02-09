<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    public function store(ArticleRequest $request)
    {
        $article = Article::create($request->validated());

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->update($request->validated());

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->noContent();
    }
}
