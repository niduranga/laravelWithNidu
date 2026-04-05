<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\Refresh;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    //
    public function store(LoginRequest $request, LoginAction $action)
    {
        $data = $action->handle($request->validated());

        if (!$data) {
            return response()->json([
                'message' => 'Invalid email or password.',
            ], 401);
        }

        return response()->json([
            'message' => 'Logged in successfully.',
            'data'    => $data,
        ]);
    }

    public function refresh(Refresh $action)
    {
        return $action->refresh();
    }
}
