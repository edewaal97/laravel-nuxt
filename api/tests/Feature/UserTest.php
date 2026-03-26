<?php

use App\Models\User;

it('can generate a signed login url for frontend', function () {
    $user = User::factory()->create();

    $url = $user->generateLoginUrl();

    expect($url)->toStartWith('http://example.test');
});
