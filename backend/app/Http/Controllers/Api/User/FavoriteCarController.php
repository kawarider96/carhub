<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteCarStoreRequest;
use App\Http\Requests\FavoriteCarUpdateRequest;
use App\Http\Resources\FavoriteCarResource;
use App\Models\FavoriteCar;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class FavoriteCarController extends Controller implements HasMiddleware
{
    public function __construct() {}

    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('active'),
        ];
    }

    /**
     * @OA\Get(
     *     path="/api/favorite-cars",
     *     summary="Bejelentkezett felhasználó kedvenc autóinak listázása",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Lista")
     * )
     */
    public function index(Request $request)
    {
        return FavoriteCarResource::collection(
            FavoriteCar::with('model.brand')
                ->where('user_id', $request->user()->id)
                ->get()
        );
    }

    /**
     * @OA\Post(
     *     path="/api/favorite-cars",
     *     summary="Új kedvenc autó rögzítése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"car_model_id","year","color","fuel"},
     *             @OA\Property(property="car_model_id", type="integer", example=5),
     *             @OA\Property(property="year", type="integer", example=2018),
     *             @OA\Property(property="color", type="string", example="metallic blue"),
     *             @OA\Property(property="fuel", type="string", example="benzin")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Létrehozva")
     * )
     */
    public function store(FavoriteCarStoreRequest $request)
    {
        $favoriteCar = FavoriteCar::create([
            'user_id'       => $request->user()->id,
            'car_model_id'  => $request->car_model_id,
            'year'          => $request->year,
            'color'         => $request->color,
            'fuel'          => $request->fuel,
        ]);

        return new FavoriteCarResource($favoriteCar->load('model.brand'));
    }

    /**
     * @OA\Get(
     *     path="/api/favorite-cars/{id}",
     *     summary="Egy kedvenc autó adatainak lekérése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
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
    public function show(Request $request, FavoriteCar $favoriteCar)
    {
        // User csak a sajátját érheti el
        if ($favoriteCar->user_id !== $request->user()->id) {
            abort(403);
        }

        return new FavoriteCarResource($favoriteCar->load('model.brand'));
    }

    /**
     * @OA\Put(
     *     path="/api/favorite-cars/{id}",
     *     summary="Kedvenc autó frissítése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="car_model_id", type="integer", example=3),
     *             @OA\Property(property="year", type="integer", example=2020),
     *             @OA\Property(property="color", type="string", example="black"),
     *             @OA\Property(property="fuel", type="string", example="dízel")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Frissítve")
     * )
     */
    public function update(FavoriteCarUpdateRequest $request, FavoriteCar $favoriteCar)
    {
        if ($favoriteCar->user_id !== $request->user()->id) {
            abort(403);
        }

        $favoriteCar->update($request->validated());

        return new FavoriteCarResource($favoriteCar->load('model.brand'));
    }

    /**
     * @OA\Delete(
     *     path="/api/favorite-cars/{id}",
     *     summary="Kedvenc autó törlése",
     *     tags={"Favorite Cars"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=204, description="Törölve")
     * )
     */
    public function destroy(Request $request, FavoriteCar $favoriteCar)
    {
        if ($favoriteCar->user_id !== $request->user()->id) {
            abort(403);
        }

        $favoriteCar->delete();

        return response()->noContent();
    }
}
