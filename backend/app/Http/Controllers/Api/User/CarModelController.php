<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarModel\StoreCarModelRequest;
use App\Http\Requests\Admin\CarModel\UpdateCarModelRequest;
use App\Http\Resources\CarModelResource;
use App\Models\CarModel;
use App\Services\CarModelService;
use App\Traits\ApiResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CarModelController extends Controller implements HasMiddleware
{
    use ApiResponse;

    public function __construct(
        protected CarModelService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin', only: ['update', 'destroy']),
        ];
    }

    /**
     * @OA\Get(
     *     path="/car-models",
     *     summary="Autó típusok listázása",
     *     tags={"Car Models"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Autó típusok listája",
     *         @OA\JsonContent(ref="#/components/schemas/CarModelListResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=423,
     *         description="A felhasználó zárolva van",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index()
    {
        $models = CarModelResource::collection($this->service->all());

        return $this->success($models, 'Autó típusok listája', 200);
    }

    /**
     * @OA\Post(
     *     path="/car-models",
     *     summary="Új autó típus létrehozása (admin)",
     *     tags={"Car Models"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarModelStoreRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Autó típus létrehozva",
     *         @OA\JsonContent(ref="#/components/schemas/CarModelSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Hibás kérés",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
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
     *         description="Nincs jogosultság (admin szükséges)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=423,
     *         description="A felhasználó zárolva van",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(StoreCarModelRequest $request)
    {
        $model = $this->service->create($request->validated());

        return $this->success(CarModelResource::make($model), 'Autó típus létrehozva', 200);
    }

    /**
     * @OA\Get(
     *     path="/car-models/{id}",
     *     summary="Autó típus adatainak lekérése",
     *     tags={"Car Models"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Autó típus részletei",
     *         @OA\JsonContent(ref="#/components/schemas/CarModelSingleResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=423,
     *         description="A felhasználó zárolva van",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(CarModel $carModel)
    {
        return $this->success(CarModelResource::make($carModel->load('brand')), 'Autó típus adatai', 200);
    }

    /**
     * @OA\Put(
     *     path="/car-models/{id}",
     *     summary="Autó típus módosítása (admin)",
     *     tags={"Car Models"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarModelUpdateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Autó típus frissítve",
     *         @OA\JsonContent(ref="#/components/schemas/CarModelSingleResponse")
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
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=423,
     *         description="A felhasználói fiók zárolva",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function update(UpdateCarModelRequest $request, CarModel $carModel)
    {
        $updated = $this->service->update($carModel->id, $request->validated());

        return $this->success(CarModelResource::make($updated), 'Autó típus frissítve', 200);
    }

    /**
     * @OA\Delete(
     *     path="/car-models/{id}",
     *     summary="Autó típus törlése (admin)",
     *     tags={"Car Models"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sikeresen törölve",
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
     *         description="Nincs jogosultság (admin required)",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=423,
     *         description="Felhasználó zárolva",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(CarModel $carModel)
    {
        $this->service->delete($carModel->id);

        return $this->success(null, 'Típus sikeresen törölve', 200);
    }
}
