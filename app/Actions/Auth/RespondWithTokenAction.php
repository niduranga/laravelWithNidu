<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTGuard;

class RespondWithTokenAction
{
    public function respondWithToken($token): array
    {
        /** @var JWTGuard $guard */
        $guard = Auth::guard('api');

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $guard->factory()->getTTL() * 60,
        ];
    }
}
