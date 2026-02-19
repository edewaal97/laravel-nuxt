<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

it('removes the banner image from storage', function () {
    $user = User::factory()->create();

    Storage::fake('public');
    $bannerImage = UploadedFile::fake()->image('original.jpg');
    $imageStoredPath = $bannerImage->store(path: 'images', options: 'public');

    $article = Article::factory()->create([
        'banner_image' => $imageStoredPath,
    ]);

    $response = $this->actingAs($user)->deleteJson('/api/articles/'.$article->slug);

    expect($response->status())->toBe(204)
        ->and(Storage::disk('public')->exists($imageStoredPath))->toBeFalse();
});
