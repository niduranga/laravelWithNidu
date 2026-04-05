<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;

class LoginAction
{
    private RespondWithTokenAction $action;

    public function __construct(RespondWithTokenAction $action)
    {
        $this->action = $action;
    }

    public function handle(array $attributes): false|array
    {
        $credentials = [
            'email' => $attributes['email'],
            'password' => $attributes['password']
        ];

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return false;
        }

        return $this->action->respondWithToken($token);
    }
}
