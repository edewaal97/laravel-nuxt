<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\LoginLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware('guest')
    ->name('register');

Route::post('/login', [LoginLinkController::class, 'store'])
    ->middleware(['guest', 'throttle:3,1'])
    ->name('login');

Route::get('/login/{user}', [LoginLinkController::class, 'handle'])
    ->name('login.store')
    ->middleware(['guest', 'signed']);

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');
