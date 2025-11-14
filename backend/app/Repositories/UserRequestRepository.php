<?php

namespace App\Repositories;

use App\Models\UserRequest;

class UserRequestRepository extends BaseRepository
{
    public function __construct(UserRequest $model)
    {
        parent::__construct($model);
    }

    /**
     * Visszaadja az összes "pending" státuszú törlési kérelmet.
     *
     * @return \Illuminate\Support\Collection
     */
    public function pending()
    {
        return $this->model
            ->where('status', 'pending')
            ->get();
    }

    /**
     * Megkeresi a felhasználóhoz tartozó törlési kérelmet.
     *
     * @param int $userId
     * @return UserRequest|null
     */
    public function byUser(int $userId): ?UserRequest
    {
        return $this->model
            ->where('user_id', $userId)
            ->first();
    }
}
