<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        protected UserRepository $users
    ) {}

    public function all()
    {
        return $this->users->all();
    }

    public function create(array $data)
    {
        // admin oldali user létrehozás
        return $this->users->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->users->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->users->delete($id);
    }

    public function lock(int $id)
    {
        return $this->users->lockUser($id);
    }

    public function unlock(int $id)
    {
        return $this->users->unlockUser($id);
    }
}
