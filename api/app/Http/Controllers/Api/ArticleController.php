<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

final class ArticleController extends Controller
{
    public function store(CreateArticleRequest $request)
    {
        $article = Article::create($request->validated());

        return (new ArticleResource($article))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $data = $request->safe()->only(['title', 'body', 'slug']);

        if ($request->hasFile('banner_image_upload')) {
            // TODO: remove original banner_image from disk
            $data['banner_image'] = $request->file('banner_image_upload')->store(
                path: 'images',
                options: [
                    'disk' => 'public',
                    'visibility' => 'public',
                ]
            );
        }

        if ($request->has('banner_image_upload') && empty($request->banner_image_upload)) {
            // TODO: remove original banner_image from disk
            $data['banner_image'] = null;
        }

        $article->update($data);

        return new ArticleResource($article);
    }

    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return response()->noContent();
    }
}
