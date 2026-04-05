<?php

namespace App\Http\Controllers\Api\Auth;

use App\Actions\Auth\RegisterAction;
use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function __construct(protected RegisterAction $action)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $user = new User($request->validated());

        $this->action->handle($user);

        return response()->json([
            'message' => 'Registration successful'
        ]);
    }
}
