<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\CreateArticleAction;
use App\Actions\DeleteArticleAction;
use App\Actions\UpdateArticleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class ArticleController extends Controller
{
    public function store(CreateArticleRequest $request, CreateArticleAction $action)
    {
        $article = $action->handle($request->validated());

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateArticleRequest $request, Article $article, UpdateArticleAction $action)
    {
        $article = $action->handle(
            article: $article,
            attributes: $request->safe()->only(['title', 'body', 'slug']),
            bannerImage: $request->file('banner_image_upload'),
            removeBannerImage: $request->has('banner_image_upload') && ! $request->filled('banner_image_upload')
        );

        return new ArticleResource($article);
    }

    public function destroy(Article $article, DeleteArticleAction $action)
    {
        Gate::authorize('delete', $article);

        $action->handle($article);

        return response()->noContent();
    }
}
