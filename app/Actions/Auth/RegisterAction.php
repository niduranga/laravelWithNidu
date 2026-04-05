<?php

namespace App\Actions\Auth;

use App\Events\UserRegistered;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;

class RegisterAction
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(User $user)
    {
        $user = $this->userRepository->create($user);
        UserRegistered::dispatch($user);
        return $user;
    }
}
