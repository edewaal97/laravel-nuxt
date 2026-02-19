<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Article;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class UpdateArticleAction
{
    public function __construct(
        protected ImageService $imageService,
    ) {}

    public function handle(
        Article $article,
        array $attributes,
        ?UploadedFile $bannerImage = null,
        bool $removeBannerImage = false
    ): Article {
        return DB::transaction(function () use (
            $article,
            $attributes,
            $bannerImage,
            $removeBannerImage
        ) {
            if ($removeBannerImage) {
                $this->imageService->delete($article->banner_image);
                $attributes['banner_image'] = null;
            }

            if ($bannerImage) {
                $this->imageService->delete($article->banner_image);
                $attributes['banner_image'] = $this->imageService->store($bannerImage);
            }

            $article->update($attributes);

            return $article->refresh();
        });
    }
}
