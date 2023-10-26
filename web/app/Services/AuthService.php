<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * User email verification.
     *
     * @param User $user
     * @return boolean
     */
    public function userEmailVerify(User $user): bool
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return true;
    }

    /**
     * Generate user remember token.
     *
     * @param User $user
     * @return string
     */
    public function userTokenCreate(User $user): string
    {
        return $user
            ->createToken("API token of {$user->name}")
            ->plainTextToken;
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function userRegister(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        event(new Registered($user));

        return $user;
    }

    /**
     * Log a user in.
     *
     * @param array $credentials
     * @return User|null
     */
    public function userLogin(array $credentials): ?User
    {
        if (Auth::attempt($credentials)) {
            return Auth::user();
        }

        return null;
    }

    /**
     * Log a user out.
     *
     * @return boolean
     */
    public function userLogout(): bool
    {
        if (Auth::check()) {
            return (bool) Auth::user()
                ->tokens()
                ->delete();
        }

        return false;
    }

    /**
     * Delete a user.
     *
     * @return boolean
     */
    public function userDelete(): bool
    {
        if (Auth::check()) {
            return (bool) Auth::user()->delete();
        }

        return false;
    }

}
