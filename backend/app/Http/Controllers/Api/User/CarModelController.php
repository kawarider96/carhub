<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarModelStoreRequest;
use App\Http\Requests\CarModelUpdateRequest;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CarModelController extends Controller implements HasMiddleware
{
    public function __construct() {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('active'),
            new Middleware('admin', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * @OA\Get(
     *     path="/api/car-models",
     *     summary="Autó típusok listázása",
     *     tags={"Car Models"},
     *     @OA\Response(
     *         response=200,
     *         description="Lista"
     *     )
     * )
     */
    public function index()
    {
        return CarModelResource::collection(
            CarModel::with('brand')->orderBy('name')->get()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/car-models",
     *     summary="Új autó típus létrehozása (ADMIN)",
     *     tags={"Car Models"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_brand_id","name"},
     *             @OA\Property(property="car_brand_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="M4 Competition")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Létrehozva")
     * )
     */
    public function store(CarModelStoreRequest $request)
    {
        $model = CarModel::create([
            'car_brand_id' => $request->car_brand_id,
            'name'         => $request->name,
        ]);

        return new CarModelResource($model);
    }

    /**
     * @OA\Get(
     *     path="/api/car-models/{id}",
     *     summary="Egy autó típus adatainak lekérése",
     *     tags={"Car Models"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Megjelenítve"),
     *     @OA\Response(response=404, description="Nem található")
     * )
     */
    public function show(CarModel $carModel)
    {
        return new CarModelResource($carModel->load('brand'));
    }

    /**
     * @OA\Put(
     *     path="/api/car-models/{id}",
     *     summary="Autó típus frissítése (ADMIN)",
     *     tags={"Car Models"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="car_brand_id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="M3 Competition")
     *         )
     *     )
     * )
     */
    public function update(CarModelUpdateRequest $request, CarModel $carModel)
    {
        $carModel->update($request->validated());

        return new CarModelResource($carModel);
    }

    /**
     * @OA\Delete(
     *     path="/api/car-models/{id}",
     *     summary="Autó típus törlése (ADMIN)",
     *     tags={"Car Models"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=204, description="Törölve")
     * )
     */
    public function destroy(CarModel $carModel)
    {
        $carModel->delete();

        return response()->noContent();
    }
}
