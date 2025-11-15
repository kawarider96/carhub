<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreFavoriteCarRequest;
use App\Http\Requests\User\UpdateFavoriteCarRequest;
use App\Http\Resources\FavoriteCarResource;
use App\Models\FavoriteCar;
use App\Services\FavoriteCarService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class FavoriteCarController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected FavoriteCarService $service
    ) {}

    /**
     * @OA\Get(
     *     path="/favorite-cars",
     *     summary="Bejelentkezett felhasználó kedvenc autóinak listázása",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Kedvencek listája",
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteCarListResponse")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Nem bejelentkezett",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny');

        $favorites = $this->service->forUser($request->user()->id);

        return $this->success(FavoriteCarResource::collection($favorites), 'Kedvenc autók listája', 200);
    }

    /**
     * @OA\Post(
     *     path="/favorite-cars",
     *     summary="Új kedvenc autó rögzítése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteCarStoreRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Létrehozott kedvenc autó",
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteCarSingleResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function store(StoreFavoriteCarRequest $request)
    {
        $favorite = $this->service->create($request->user()->id, $request->validated());

        return $this->success(FavoriteCarResource::make($favorite), 'Kedvenc autó létrehozva', 201);
    }

    /**
     * @OA\Get(
     *     path="/favorite-cars/{id}",
     *     summary="Kedvenc autó lekérése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true),
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres lekérdezés",
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteCarSingleResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Hozzáférés megtagadva",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function show(Request $request, FavoriteCar $favoriteCar)
    {
        $this->authorize('view', $favoriteCar);

        return $this->success(FavoriteCarResource::make($favoriteCar), 'Kedvenc autó adatai', 200);
    }

    /**
     * @OA\Put(
     *     path="/favorite-cars/{id}",
     *     summary="Kedvenc autó frissítése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteCarUpdateRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Frissítve",
     *         @OA\JsonContent(ref="#/components/schemas/FavoriteCarSingleResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validációs hiba",
     *         @OA\JsonContent(ref="#/components/schemas/ValidationErrorResponse")
     *     )
     * )
     */
    public function update(UpdateFavoriteCarRequest $request, FavoriteCar $favoriteCar)
    {
        $this->authorize('update', $favoriteCar);

        $updated = $this->service->update($favoriteCar->id, $request->validated());

        return $this->success(FavoriteCarResource::make($updated), 'Kedvenc autó frissítve', 200);
    }

    /**
     * @OA\Delete(
     *     path="/favorite-cars/{id}",
     *     summary="Kedvenc autó törlése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sikeres törlés",
     *         @OA\JsonContent(ref="#/components/schemas/SuccessResponse")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Nincs jogosultság",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Nem található",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function destroy(Request $request, FavoriteCar $favoriteCar)
    {
        $this->authorize('delete', $favoriteCar);

        $this->service->delete($favoriteCar->id);

        return $this->success(null, 'Kedvenc autó törölve', 200);
    }
}
