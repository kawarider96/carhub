<?php

namespace App\Repositories;

use App\Models\User;

/**
 * @extends BaseRepository<User>
 */
class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Felhasználó keresése felhasználónév alapján.
     *
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        return $this->model->where('username', $username)->first();
    }

    /**
     * Új felhasználó létrehozása.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function createUser(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Felhasználó zárolása.
     *
     * @return User
     */
    public function lockUser(int $id): User
    {
        $user = $this->find($id);
        $user->is_active = false;
        $user->save();
        return $user;
    }

    /**
     * Felhasználó feloldása.
     *
     * @return User
     */
    public function unlockUser(int $id): User
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
