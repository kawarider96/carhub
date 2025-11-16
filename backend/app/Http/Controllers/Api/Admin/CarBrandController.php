<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarBrand\StoreCarBrandRequest;
use App\Http\Requests\Admin\CarBrand\UpdateCarBrandRequest;
use App\Http\Resources\CarBrandResource;
use App\Services\CarBrandService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use App\Traits\ApiResponse;
use App\Models\CarBrand;
use Illuminate\Http\JsonResponse;

class CarBrandController extends Controller implements HasMiddleware
{
    use ApiResponse;
    
    public function __construct(
        protected CarBrandService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('admin', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * @OA\Get(
     *     path="/car-brands",
     *     summary="Márkák listázása",
     *     tags={"Car Brands"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Márkák listája",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandListResponse")
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Nincs bejelentkezve",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $brands = CarBrandResource::collection($this->service->all());

        return $this->success($brands, 'Márkák listája', 200);
    }

    /**
     * @OA\Post(
     *     path="/car-brands",
     *     summary="Új márka létrehozása (admin)",
     *     tags={"Car Brands"},
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandStoreRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Új márka létrehozva",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandSingleResponse")
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
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function store(StoreCarBrandRequest $request): JsonResponse
    {
        $brand = $this->service->create($request->validated());

        return $this->success(CarBrandResource::make($brand), 'Új márka létrehozva', 201);
    }

    /**
     * @OA\Get(
     *     path="/car-brands/{id}",
     *     summary="Márka lekérése",
     *     tags={"Car Brands"},
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
     *         description="Márka adatai",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandSingleResponse")
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
     *         description="A márka nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(CarBrand $brand): JsonResponse
    {
        return $this->success(CarBrandResource::make($brand), 'Márka adatai', 200);
    }

    /**
     * @OA\Put(
     *     path="/car-brands/{id}",
     *     summary="Márka módosítása (admin)",
     *     tags={"Car Brands"},
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
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandUpdateRequest")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Márka frissítve",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandSingleResponse")
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
     *         description="A márka nem található",
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
    public function update(UpdateCarBrandRequest $request, CarBrand $brand): JsonResponse
    {
        $updated = $this->service->update($brand->id, $request->validated());

        return $this->success(CarBrandResource::make($updated), 'Márka sikeresen frissítve', 200);
    }

    /**
     * @OA\Delete(
     *     path="/car-brands/{id}",
     *     summary="Márka törlése (admin)",
     *     tags={"Car Brands"},
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
     *         description="Sikeres törlés",
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
     *         description="A márka nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(CarBrand $brand): JsonResponse
    {
        $this->service->delete($brand->id);

        return $this->success(null, 'Márka sikeresen törölve', 200);
    }
}
