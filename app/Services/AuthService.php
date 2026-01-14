<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 *
 * Service for handling user authentication logic.
 *
 * @package App\Services
 */
class AuthService
{
    /**
     * Register a new user.
     *
     * @param  array  $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Attempt to log in a user with credentials.
     *
     * @param  array  $credentials
     * @return string|null
     */
    public function login(array $credentials): ?string
    {
        if (! $token = Auth::attempt($credentials)) {
            return null;
        }

        return $token;
    }

    /**
     * Get the authenticated user.
     *
     * @return User|null
     */
    public function me(): ?User
    {
        return Auth::user();
    }

    /**
     * Log the user out (invalidate the token).
     *
     * @return void
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Refresh the current token.
     *
     * @return string
     */
    public function refresh(): string
    {
        return Auth::refresh();
    }

    /**
     * Get the generic authenticated user instance.
     *
     * @return User|null
     */
    public function getGenericUser(): ?User
    {
        return Auth::user();
    }
}
