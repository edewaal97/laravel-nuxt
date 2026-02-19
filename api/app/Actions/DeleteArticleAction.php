<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Article;
use App\Services\ImageService;
use Illuminate\Support\Facades\DB;

class DeleteArticleAction
{
    public function __construct(
        protected ImageService $imageService,
    ) {}

    public function handle(
        Article $article,
    ): void {
        DB::transaction(function () use (
            $article
        ) {
            $this->imageService->delete($article->banner_image);

            $article->delete();
        });
    }
}
