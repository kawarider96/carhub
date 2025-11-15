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
     * Visszaadja az összes "open" státuszú kérelmeket.
     *
     * @return \Illuminate\Support\Collection
     */
    public function openRequests()
    {
        return $this->model
            ->where('status', 'open')
            ->get();
    }

    /**
     * Megkeresi a felhasználóhoz tartozó törlési kérelmet.
     *
     * @param int $userId
     * @return UserRequest|null
     */
    public function openDeleteRequestsByUser(int $userId): ?UserRequest
    {
        return $this->model
            ->where('user_id', $userId)
            ->where('type', 'delete_account')
            ->where('status', 'open')
            ->first();
    }
}
