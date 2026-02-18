<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Support\Str;

final class UpdateArticleRequest extends BaseArticleRequest
{
    public function rules(): array
    {

        return array_merge_recursive(
            [
                'title' => ['sometimes'],
                'body' => ['sometimes'],
                'slug' => ['sometimes'],
            ],
            parent::rules()
        );
    }

    public function authorize(): bool
    {
        return $this->user()->can('update', $this->article());
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
