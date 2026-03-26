<?php

declare(strict_types=1);

use App\Actions\Auth\SendLoginLink;
use App\Mail\Auth\LoginLinkMail;
use App\Models\User;

test('users receive mail when trying to login', function (): void {
    Mail::fake();
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
    ]);

    $response->assertNoContent();

    Mail::assertSent(LoginLinkMail::class);
    Mail::assertSentCount(1);
});

test('non users do not receive mail when trying to login', function (): void {
    Mail::fake();
    $response = $this->post('/login', [
        'email' => "test@example.com",
    ]);

    $response->assertNoContent();

    Mail::assertNothingSent();
});

it('logs the user out', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertNoContent();
});

test('Login mail contains login link', function (): void {
    Mail::fake();
    $user = User::factory()->create();

    (new SendLoginLink())->handle($user);

    Mail::assertSent(LoginLinkMail::class, fn ($mail) => $mail->hasTo($user->email));
});

it('logs in the user and verifies email when clicking the link', function (): void {
    Mail::fake();
    $user = User::factory()->unverified()->create();

    $url = $user->generateLoginUrl();

    $response = $this->getJson(str_replace(config('app.frontend_url'), config('app.url'), $url));
    $response->assertOk();
    $this->assertAuthenticatedAs($user);

    expect($response->getStatusCode())->toBe(200)
        ->and($response->json('user'))
        ->toHaveKeys(['id', 'name', 'email'])
        ->toHaveKey('id', $user->id)
        ->toHaveKey('name', $user->name)
        ->toHaveKey('email', $user->email)
        ->and($user->fresh()->hasVerifiedEmail())->toBeTrue();
    ;
});


