<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Mail\Auth\LoginLinkMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

final class SendLoginLink
{
    public function handle(User $user, ?string $redirectTo = null): void
    {
        $url = $user->generateLoginUrl($redirectTo);

        Mail::to(
            users: $user->email,
        )->send(
            mailable: new LoginLinkMail(
                url: $url,
            )
        );
    }
}
