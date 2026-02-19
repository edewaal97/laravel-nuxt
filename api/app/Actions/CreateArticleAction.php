<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Article;

class CreateArticleAction
{
    public function handle(array $data): Article
    {
        return Article::create($data);
    }
}
