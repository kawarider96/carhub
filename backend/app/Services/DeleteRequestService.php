<?php

namespace App\Services;

use App\Repositories\DeleteRequestRepository;
use App\Repositories\UserRepository;

class DeleteRequestService
{
    public function __construct(
        protected DeleteRequestRepository $requests,
        protected UserRepository $users
    ) {}

    public function pending()
    {
        return $this->requests->pending();
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
            'status' => 'pending'
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
