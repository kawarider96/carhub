<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreCarImageRequest;
use App\Http\Resources\CarImageResource;
use App\Models\CarImage;
use App\Models\FavoriteCar;
use App\Services\CarImageService;
use App\Traits\ApiResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CarImageController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public function __construct(
        protected CarImageService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/favorite-cars/{favoriteCar}/images",
     *     summary="Képek listázása egy kedvenc autóhoz",
     *     tags={"Car Images"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="favoriteCar",
     *         in="path",
     *         required=true,
     *         description="A kedvenc autó azonosítója",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Képek listája",
     *         @OA\JsonContent(ref="#/components/schemas/CarImageListResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(FavoriteCar $favoriteCar)
    {
        $this->authorize('view', $favoriteCar);

        $images = $this->service->getByFavoriteCar($favoriteCar->id);

        return $this->success(CarImageResource::collection($images), 'Képek listája', 200);
    }

    /**
     * @OA\Post(
     *     path="/favorite-cars/{favoriteCar}/images",
     *     summary="Új kép(ek) feltöltése",
     *     tags={"Car Images"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="favoriteCar",
     *         in="path",
     *         required=true,
     *         description="A kedvenc autó azonosítója",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         description="Több kép is feltölthető egyszerre",
     *
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(ref="#/components/schemas/CarImageUploadRequest")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Képek sikeresen feltöltve",
     *         @OA\JsonContent(ref="#/components/schemas/CarImageListResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function store(CarImageStoreRequest $request, FavoriteCar $favoriteCar)
    {
        $this->authorize('create', $favoriteCar);

        $uploaded = $this->service->uploadImages(
            $favoriteCar->id,
            $request->validated()['images']
        );

        return $this->success(CarImageResource::collection($uploaded), 'Képek sikeresen feltöltve', 201);
    }

    /**
     * @OA\Get(
     *     path="/images/{carImage}",
     *     summary="Kép megtekintése / letöltése",
     *     tags={"Car Images"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="carImage",
     *         in="path",
     *         required=true,
     *         description="A kép azonosítója",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="A kép bináris adatként",
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Kép nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(CarImage $carImage): StreamedResponse
    {
        $this->authorize('view', $carImage->favoriteCar);

        return response()->stream(function () use ($carImage) {
            echo $carImage->content;
        }, 200, [
            'Content-Type'        => $carImage->mime ?? 'application/octet-stream',
            'Content-Length'      => strlen($carImage->content),
            'Content-Disposition' => 'inline; filename=\"image_'.$carImage->id.'\"',
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/images/{carImage}",
     *     summary="Kép törlése",
     *     tags={"Car Images"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="carImage",
     *         in="path",
     *         required=true,
     *         description="A törlendő kép azonosítója",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Kép törölve",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Kép nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(CarImage $carImage)
    {
        $this->authorize('delete', $carImage->favoriteCar);

        $this->service->delete($carImage->id);

        return $this->success(null, 'Kép sikeresen törölve', 200);
    }
}
