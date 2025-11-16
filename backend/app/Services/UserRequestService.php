<?php

namespace App\Services;

use App\Repositories\UserRequestRepository;
use App\Repositories\UserRepository;
use App\Repositories\CarBrandRepository;

class UserRequestService
{
    public function __construct(
        protected UserRequestRepository $requests,
        protected UserRepository $users,
        protected CarBrandRepository $carBrands
    ) {}

    /**
     * Összes felhasználói kérés listázása.
     *
     * @return Collection<int, UserRequest>
     */
    public function all()
    {
        return $this->requests->all();
    }

    /**
     * Új felhasználói kérés létrehozása.
     *
     * @param int $userId
     * @param string $type
     * @param array<string,mixed>|null $payload
     *
     * @return UserRequest|false
     */
    public function createRequest(int $userId, string $type, ?array $payload)
    {
        $existing = $this->requests->findOpenByUserAndType($userId, $type);

        if ($existing) {
            return false;
        }

        return $this->requests->create([
            'user_id' => $userId,
            'type'    => $type,
            'payload' => $payload ?? [],
            'status'  => 'open',
        ]);
    }

    /**
     * Felhasználói kérés jóváhagyása admin által.
     *
     * @return UserRequest
     */
    public function approve(int $id, int $adminId)
    {
        $request = $this->requests->find($id);

        if ($request->type === 'delete_account') {
            $this->users->delete($request->user_id);
        }

        if ($request->type === 'missing_brand') {

            $brandName = $request->payload['brand'] ?? null;

            if ($brandName) {

                // Ha még nincs ilyen márka → létrehozzuk
                if (!$this->carBrands->existsByName($brandName)) {
                    $this->carBrands->create([
                        'name' => $brandName
                    ]);
                }
            }
        }

        $request->status     = 'approved';
        $request->handled_by = $adminId;
        $request->handled_at = now();
        $request->save();

        return $request;
    }

    /**
     * Felhasználói kérés elutasítása admin által.
     *
     * @return UserRequest
     */
    public function reject(int $id, int $adminId)
    {
        $request = $this->requests->find($id);
        $request->status = 'rejected';
        $request->handled_by = $adminId;
        $request->save();

        return $request;
    }
}
