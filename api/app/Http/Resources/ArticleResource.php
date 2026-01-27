<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

/**
 * @mixin Article
 */
final class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function toArray(Request $request): array
    {
        $base = [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
        ];

        return array_merge($base, $request->routeIs('articles.show') ? [
            'banner' => $this->banner_image,
            'content' => $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ] : []);
    }
}
