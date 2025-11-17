<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthService
{
    public function __construct(
        protected UserRepository $users
    ) {}

    /**
     * Regisztrál egy új felhasználót.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = true;
        $data['failed_logins'] = 0;

        return $this->users->create($data);
    }

    /**
     * Bejelentkezési logika.
     *
     * @return array{
     *     status: bool,
     *     user?: User,
     *     error?: string
     * }
     */
    public function login(string $username, string $password)
    {
        $user = $this->users->findByUsername($username);

        if (!$user) {
            return ['status' => false, 'error' => 'invalid'];
        }

        if (!$user->is_active) {
            return ['status' => false, 'error' => 'locked'];
        }

        if (!Hash::check($password, $user->password)) {

            // increment failed logins
            $user->failed_logins += 1;

            if ($user->failed_logins >= 5) {
                $user->is_active = false;
            }

            $user->save();

            return ['status' => false, 'error' => 'invalid'];
        }

        // success login → reset attempts
        $user->failed_logins = 0;
        $user->save();

        return ['status' => true, 'user' => $user];
    }
}
