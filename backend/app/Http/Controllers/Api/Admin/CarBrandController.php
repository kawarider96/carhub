<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarBrand\StoreCarBrandRequest;
use App\Http\Requests\Admin\CarBrand\UpdateCarBrandRequest;
use App\Http\Resources\CarBrandResource;
use App\Services\CarBrandService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CarBrandController extends Controller implements HasMiddleware
{
    public function __construct(
        protected CarBrandService $service
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware('active'),
            new Middleware('admin', only: ['store', 'update', 'destroy']),
        ];
    }

    /**
     * @OA\Get(
     *     path="/car-brands",
     *     summary="Márkák listázása",
     *     tags={"Car Brands"},
     *     @OA\Response(
     *         response=200,
     *         description="Márkák listája",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CarBrand"))
     *     )
     * )
     */
    public function index()
    {
        return CarBrandResource::collection(
            $this->service->all()
        );
    }

    /**
     * @OA\Post(
     *     path="/car-brands",
     *     summary="Új márka létrehozása",
     *     tags={"Car Brands"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CarBrandStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Létrehozott márka",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrand")
     *     )
     * )
     */
    public function store(StoreCarBrandRequest $request)
    {
        $brand = $this->service->create($request->validated());

        return CarBrandResource::make($brand)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *     path="/car-brands/{id}",
     *     summary="Egy márka lekérése",
     *     tags={"Car Brands"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true, @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Márka adat",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrand")
     *     )
     * )
     */
    public function show(int $id)
    {
        $brand = $this->service->find($id);

        return CarBrandResource::make($brand);
    }

    /**
     * @OA\Put(
     *     path="/car-brands/{id}",
     *     summary="Márka módosítása",
     *     tags={"Car Brands"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/CarBrandUpdateRequest")),
     *     @OA\Response(
     *         response=200,
     *         description="Frissített márka",
     *         @OA\JsonContent(ref="#/components/schemas/CarBrand")
     *     )
     * )
     */
    public function update(UpdateCarBrandRequest $request, int $id)
    {
        $brand = $this->service->update($id, $request->validated());

        return CarBrandResource::make($brand);
    }

    /**
     * @OA\Delete(
     *     path="/car-brands/{id}",
     *     summary="Márka törlése",
     *     tags={"Car Brands"},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Sikeres törlés")
     * )
     */
    public function destroy(int $id)
    {
        $this->service->delete($id);

        return response()->noContent();
    }
}
