<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepository
{
    /**
     * @param User $user
     * @return User
     */
    public function create(User $user): Model;
}
