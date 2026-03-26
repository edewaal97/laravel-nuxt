<?php

declare(strict_types=1);

use App\Mail\Auth\LoginLinkMail;

test('new users can register', function (): void {
    Mail::fake();

    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $this->assertGuest();
    $response->assertNoContent();

    Mail::assertSent(LoginLinkMail::class);
    Mail::assertSentCount(1);
});
