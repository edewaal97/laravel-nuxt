<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendLoginLink;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

final class LoginLinkController
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request, SendLoginLink $sendLoginLinkAction): Response
    {
        $request->authenticate($sendLoginLinkAction);

        return response()->noContent();
    }

    public function handle(Request $request)
    {
        if (! $request->hasValidSignature()) {
            return response()->json(['message' => 'Invalid or expired link.'], 401);
        }

        /**
         * @var User $user
         */
        $user = User::findOrFail($request->user);

        Auth::login(user: $user, remember: true);

        $request->session()->regenerate();

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return response()->json([
            'message' => 'Authenticated',
            'user' => $user
        ]);
    }
}
