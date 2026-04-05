<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface UserRepository
{
    /**
     * @param User $user
     *
     * @return User
     */
    public function create(User $user): Model;

    /**
     * @param int $id
     *
     * @return Model
     */
    public function find(int $id): Model;
}
