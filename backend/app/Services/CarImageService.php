<?php

namespace App\Services;

use App\Repositories\CarImageRepository;
use Illuminate\Http\UploadedFile;

class CarImageService
{
    public function __construct(
        protected CarImageRepository $images
    ) {}

    /**
     * Adott kedvenc autóhoz tartozó képek listázása.
     */
    public function getByFavoriteCar(int $favoriteCarId)
    {
        return $this->images->getByFavoriteCar($favoriteCarId);
    }

    /**
     * Több kép feltöltése egyszerre.
     *
     * @param int   $favoriteCarId
     * @param array $files  // UploadedFile[] tömb
     */
    public function uploadImages(int $favoriteCarId, array $files): array
    {
        $result = [];

        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $result[] = $this->images->create([
                'favorite_car_id' => $favoriteCarId,
                'content'         => file_get_contents($file->getRealPath()),
                'mime'            => $file->getClientMimeType(),
            ]);
        }

        return $result;
    }

    /**
     * Egy kép törlése.
     */
    public function delete(int $id)
    {
        return $this->images->delete($id);
    }
}
