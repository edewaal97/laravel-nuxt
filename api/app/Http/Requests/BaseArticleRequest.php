<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

/** @mixin Article */
class BaseArticleRequest extends FormRequest
{
    public function article(): ?Article
    {
        $article = $this->route('article');
        return $article instanceof Article ? $article : null;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'string',
                'max:255',
            ],
            'body' => [
                'string',
            ],
            'slug' => [
                "unique:articles,slug,{$this->article()?->id}",
            ],
            'banner_image_upload' => [
                'sometimes',
                'image',
                'nullable',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        // If the user didn't provide a slug, generate one from the title
        // so that the 'unique' rule below can actually check it.
        if (! $this->has('slug') || empty($this->slug)) {
            $this->merge([
                'slug' => Str::slug($this->title),
            ]);
        }
    }
}
