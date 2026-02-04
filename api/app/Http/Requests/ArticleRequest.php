<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        // If the user didn't provide a slug, generate one from the title
        // so that the 'unique' rule below can actually check it.
        if (!$this->has('slug') || empty($this->slug)) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->title),
            ]);
        }
    }

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
            'slug'  => [
                'required',
                "unique:articles,slug,{$articleId}"
            ],
        ];
    }
}
