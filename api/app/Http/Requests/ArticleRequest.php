<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

final class ArticleRequest extends FormRequest
{
    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        $article = $this->route('article');

        $articleId = $article instanceof \App\Models\Article ? $article->id : null;

        return [
            'title' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
                'max:255',
            ],
            'body' => [
                $isUpdate ? 'sometimes' : 'required',
                'string',
            ],
            'slug' => [
                'required',
                "unique:articles,slug,{$articleId}",
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
