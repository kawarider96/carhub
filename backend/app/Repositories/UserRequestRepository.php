<?php

namespace App\Repositories;

use App\Models\UserRequest;

/**
 * @extends BaseRepository<UserRequest>
 */
class UserRequestRepository extends BaseRepository
{
    public function __construct(UserRequest $model)
    {
        parent::__construct($model);
    }

    /**
     * Megkeresi a felhasználó nyitott kérelmét.
     *
     * @return UserRequest|null
     */
    public function openRequestsByUser(int $userId): ?UserRequest
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('status', 'open')
            ->first();
    }

    /**
     * Megkeresi a felhasználó adott típusú nyitott kérelmét.
     *
     * @return UserRequest|null
     */
    public function findOpenByUserAndType(int $userId, string $type): ?UserRequest
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('type', $type)
            ->where('status', 'open')
            ->first();
    }
}
