<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Article;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;

class CreateArticleAction
{
    public function __construct(
        protected ImageService $imageService,
    ) {}
    public function handle(
        array $attributes,
        ?UploadedFile $bannerImage = null,
    ): Article {
        if ($bannerImage) {
            $attributes['banner_image'] = $this->imageService->store($bannerImage);
        }

        return Article::create($attributes);
    }
}
