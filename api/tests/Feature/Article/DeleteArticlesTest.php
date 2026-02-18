<?php

use App\Models\Article;
use App\Models\User;

it('can delete the article', function () {
    $article = Article::factory()->create();
    $user = User::factory()->create();
    expect(Article::count())->toBe(1);

    $response = $this->actingAs($user)->deleteJson('/api/articles/'.$article->slug);

    expect($response->status())->toBe(204)
        ->and($response->isEmpty())->toBeTrue()
        ->and(Article::count())->toBe(0);
});

it('prevents unauthenticated user from deleting articles', function () {
    $article = Article::factory()->create();
    expect(Article::count())->toBe(1);

    $response = $this->actingAsGuest()->deleteJson('/api/articles/'.$article->slug);

    expect($response->status())->toBe(401)
        ->and(Article::count())->toBe(1);
});
