<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;


class UserService
{
    public function __construct(
        protected UserRepository $users
    ) {}

    /**
     * Összes felhasználó listázása.
     *
     * @return Collection<int, User>
     */
    public function all()
    {
        return $this->users->all();
    }

    /**
     * Egy felhasználó lekérése ID alapján.
     *
     * @return User
     */
    public function find(int $id)
    {
        return $this->users->find($id);
    }

    /**
     * Felhasználó frissítése.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function update(int $id, array $data)
    {
        return $this->users->update($id, $data);
    }

    /**
     * Felhasználó törlése.
     *
     * @return bool|null
     */
    public function delete(int $id)
    {
        return $this->users->delete($id);
    }

    /**
     * Felhasználó zárolása.
     *
     * @return User
     */
    public function lock(int $id)
    {
        return $this->users->lockUser($id);
    }

    /**
     * Felhasználó zárolásának feloldása.
     *
     * @return User
     */
    public function unlock(int $id)
    {
        return $this->users->unlockUser($id);
    }

    /**
     * Bejelentkezés kezelése.
     *
     * @param array{username:string, password:string} $credentials
     *
     * @return array{
     *     token: string,
     *     user: array{
     *         id:int,
     *         full_name:string,
     *         role:string
     *     }
     * } | 'wrong_credentials' | 'locked'
     */
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

    /**
     * Új felhasználó regisztrációja.
     *
     * @param array<string, mixed> $data
     * @return User
     */
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

        return $user;
    }
}
