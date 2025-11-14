<?php

namespace App\Services;

use App\Repositories\UserRequestRepository;
use App\Repositories\UserRepository;

class UserRequestService
{
    public function __construct(
        protected UserRequestRepository $requests,
        protected UserRepository $users
    ) {}

    public function open()
    {
        return $this->requests->open();
    }

    public function userRequest(int $userId)
    {
        return $this->requests->byUser($userId);
    }

    public function createRequest(int $userId)
    {
        if ($this->requests->byUser($userId)) {
            return ['status' => false, 'error' => 'exists'];
        }

        return $this->requests->create([
            'user_id' => $userId,
            'status' => 'open'
        ]);
    }

    public function approve(int $id, int $adminId)
    {
        $request = $this->requests->find($id);
        $request->status = 'approved';
        $request->handled_by = $adminId;
        $request->save();

        // user törlése
        $this->users->delete($request->user_id);

        return $request;
    }

    public function reject(int $id, int $adminId)
    {
        $request = $this->requests->find($id);
        $request->status = 'rejected';
        $request->handled_by = $adminId;
        $request->save();

        return $request;
    }
}
