<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class ArticleController extends Controller
{
    public function store(ArticleRequest $request)
    {
        Gate::authorize('create', Article::class);

        $article = Article::create($request->validated());

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        Gate::authorize('update', $article);

        $data = $request->safe()->only(['title', 'body', 'slug']);

        if ($request->hasFile('banner_image_upload')) {
            $data['banner_image'] = $request->file('banner_image_upload')->store(
                path: 'images',
                options: [
                    'disk' => 'public',
                    'visibility' => 'public',
                ]
            );
        }

        $article->update($data);

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return response()->noContent();
    }
}
