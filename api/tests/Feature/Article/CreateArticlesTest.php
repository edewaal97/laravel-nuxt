<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

it('can create an article', function () {
    $user = User::factory()->create();
    expect(Article::count())->toBe(0);

    $response = $this->actingAs($user)->postJson('/api/articles', [
        'title' => 'Title of Article',
        'body' => '<p>Lorem Ipsum Dolor Sit</p>',
    ]);

    $article = Article::first();

    expect($response->status())->toBe(201)
        ->and($response->json('data'))
            ->toBeArray()
            ->toHaveKeys(['id', 'title', 'slug'])
            ->toHaveKey('title', 'Title of Article')
            ->toHaveKey('slug', 'title-of-article')
        ->and($article->body)->toBe('<p>Lorem Ipsum Dolor Sit</p>');
});

it('prevents unauthenticated user from creating articles', function () {
    $response = $this->actingAsGuest()->postJson('/api/articles', [
        'title' => Str::random(10),
        'body' => Str::random(10),
    ]);

    expect($response->status())->toBe(401)
        ->and(Article::count())->toBe(0);
});

it('requires a title', function () {
    $user = User::factory()->create();
    expect(Article::count())->toBe(0);

    $response = $this->actingAs($user)->postJson('/api/articles', [
        'title' => '',
        'body' => Str::random(10),
    ]);

    expect($response->status())->toBe(422)
        ->and($response->json('errors'))
        ->toBeArray()
        ->toHaveKey('title')
        ->not()->toHaveKey('body')
        ->and(Article::count())->toBe(0);
});

it('requires a body', function () {
    $user = User::factory()->create();
    expect(Article::count())->toBe(0);

    $response = $this->actingAs($user)->postJson('/api/articles', [
        'title' => Str::random(10),
        'body' => '',
    ]);

    expect($response->status())->toBe(422)
        ->and($response->json('errors'))
        ->toBeArray()
        ->toHaveKey('body')
        ->not()->toHaveKey('title')
        ->and(Article::count())->toBe(0);;
});
