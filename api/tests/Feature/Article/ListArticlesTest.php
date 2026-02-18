<?php

use App\Models\Article;

it('can access the articles', function () {
    Article::factory()->count(10)->create();

    $response = $this->getJson('/api/articles');
    $response->assertOk();

    expect($response->status())->toBe(200)
        ->and($response->json('data'))->toHaveCount(10);
});

it('displays only id, slug and title', function () {
    Article::factory()->create();
    expect(Article::count())->toBe(1);

    $response = $this->getJson('/api/articles');

    expect($response->json('data'))
        ->toBeArray()
        ->each()->toHaveKeys(['id', 'slug', 'title'])
        ->each()->not()->toHaveKeys(['banner_image', 'body', 'created_at', 'updated_at']);
});
