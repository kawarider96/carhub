<?php

namespace App\Services;

use App\Repositories\FavoriteCarRepository;

class FavoriteCarService
{
    public function __construct(
        protected FavoriteCarRepository $favorites
    ) {}

    public function forUser(int $userId)
    {
        return $this->favorites->getByUser($userId);
    }

    public function create(array $data)
    {
        return $this->favorites->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->favorites->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->favorites->delete($id);
    }
}
