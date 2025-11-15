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

    public function createUser(array $data)
    {
        return $this->model->create($data);
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

    public function incrementFailedLogins(User $user): User
    {
        $user->failed_logins++;

        if ($user->failed_logins >= 5) {
            $user->is_active = false;
        }

        $user->save();

        return $user;
    }

    public function resetFailedLogins(User $user): User
    {
        $user->failed_logins = 0;
        $user->save();

        return $user;
    }
}
