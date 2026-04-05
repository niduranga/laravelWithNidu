<?php

namespace App\Actions\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTGuard;

class Refresh
{
    public function refresh(): JsonResponse
    {
        /** @var JWTGuard $guard */
        $guard = Auth::guard('api');

        $newToken = $guard->refresh();

        return response()->json([
            'access_token' => $newToken,
            'token_type' => 'bearer'
        ]);
    }
}
