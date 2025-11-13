<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByUsername(string $username)
    {
        return $this->model->where('username', $username)->first();
    }

    public function lockUser(int $id)
    {
        $user = $this->find($id);
        $user->is_active = false;
        $user->save();
        return $user;
    }

    public function unlockUser(int $id)
    {
        $user = $this->find($id);
        $user->is_active = true;
        $user->failed_logins = 0;
        $user->save();
        return $user;
    }
}
