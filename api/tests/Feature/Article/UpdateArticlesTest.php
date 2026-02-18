<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Str;

it('can update an article', function () {
    $article = Article::factory()->create([
        'title' => 'Original Title',
        'body' => 'Original Body',
    ]);
    $user = User::factory()->create();

    $response = $this->actingAs($user)->putJson('/api/articles/'.$article->slug, [
        'title' => 'Updated Title',
        'body' => 'Updated Body',
    ]);

    $article->refresh();

    expect($response->status())->toBe(200)
        ->and($response->json('data'))
            ->toBeArray()
            ->toHaveKey('title', 'Updated Title')
            ->toHaveKey('slug', 'updated-title')
        ->and(Article::count())
            ->toBe(1)
        ->and($article->body)->toBe('Updated Body');
});

it('prevents unauthenticated user from updating articles', function () {
    $article = Article::factory()->create([
        'title' => 'Original Title',
        'body' => 'Original Body',
    ]);

    $response = $this->actingAsGuest()->putJson('/api/articles/'.$article->slug, [
        'title' => 'Non-updated Title',
        'body' => 'Non-updated Body',
    ]);

    $article->refresh();

    expect($response->status())->toBe(401)
        ->and(Article::count())->toBe(1)
        ->and($article->title)->toBe('Original Title')
        ->and($article->body)->toBe('Original Body');
});

it('requires a title', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'title' => 'Original Title',
        'body' => 'Original Body',
    ]);

    $response = $this->actingAs($user)->putJson('/api/articles/'.$article->slug, [
        'title' => '',
        'body' => Str::random(10),
    ]);

    $article->refresh();

    expect($response->status())->toBe(422)
        ->and($response->json('errors'))
        ->toBeArray()
        ->toHaveKey('title')
        ->not()->toHaveKey('body')
        ->and(Article::count())->toBe(1)
        ->and($article->title)->toBe('Original Title')
        ->and($article->body)->toBe('Original Body');
});

it('requires a body', function () {
    $user = User::factory()->create();
    $article = Article::factory()->create([
        'title' => 'Original Title',
        'body' => 'Original Body',
    ]);

    $response = $this->actingAs($user)->putJson('/api/articles/'.$article->slug, [
        'title' => Str::random(10),
        'body' => '',
    ]);

    expect($response->status())->toBe(422)
        ->and($response->json('errors'))
        ->toBeArray()
        ->toHaveKey('body')
        ->not()->toHaveKey('title')
        ->and(Article::count())->toBe(1)
        ->and($article->title)->toBe('Original Title')
        ->and($article->body)->toBe('Original Body');
});
