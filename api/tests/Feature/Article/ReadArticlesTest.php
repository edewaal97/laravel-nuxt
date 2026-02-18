<?php

use App\Models\Article;

it('can read the article', function () {
    $article = Article::factory()->create();
    expect(Article::count())->toBe(1);

    $response = $this->getJson('/api/articles/'.$article->slug);
    $response->assertOk();

    expect($response->status())->toBe(200)
        ->and($response->json('data'))
        ->toBeArray()
        ->toHaveKeys(['id', 'title', 'slug', 'banner_image', 'body', 'created_at', 'updated_at']);
});
