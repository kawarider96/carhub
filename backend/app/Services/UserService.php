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

    public function find(int $id)
    {
        return $this->users->find($id);
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

    public function login(array $credentials)
    {
        $user = $this->users->findByUsername($credentials['username']);

        if (!$user) {
            return 'wrong_credentials';
        }

        if (!$user->is_active) {
            return 'locked';
        }

        if (!Hash::check($credentials['password'], $user->password)) {

            $this->users->incrementFailedLogins($user);

            return 'wrong_credentials';
        }

        // success
        $this->users->resetFailedLogins($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'token' => $token,
            'user' => [
                'id'        => $user->id,
                'full_name' => $user->full_name,
                'role'      => $user->role,
            ],
        ];
    }

    public function register(array $data)
    {
        $user = $this->users->createUser([
            'full_name'     => $data['full_name'],
            'username'      => $data['username'],
            'password'      => Hash::make($data['password']),
            'role'          => 'user',
            'is_active'     => true,
            'failed_logins' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'id'        => $user->id,
            'full_name' => $user->full_name,
            'username'  => $user->username,
            'role'      => $user->role,
            'token'     => $token,
        ];
    }
}
