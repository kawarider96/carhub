<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreCarImageRequest;
use App\Models\FavoriteCar;
use App\Models\CarImage;
use App\Services\CarImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CarImageController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected CarImageService $images
    ) {}

    /**
     * Képek listázása egy kedvenc autóhoz (AJAX).
     */
    public function index(FavoriteCar $favoriteCar): JsonResponse
    {
        $this->authorize('view', $favoriteCar);

        $images = $this->images->getByFavoriteCar($favoriteCar->id);

        return response()->json([
            'data' => $images->map(fn (CarImage $img) => [
                'id'  => $img->id,
                'url' => route('favorites.images.show', [
                    'favoriteCar' => $favoriteCar->id,
                    'image'       => $img->id,
                ]),
            ])->values(),
        ]);
    }

    /**
     * Egy adott kép bináris megjelenítése.
     */
    public function show(FavoriteCar $favoriteCar, CarImage $image)
    {
        $this->authorize('view', $favoriteCar);

        return response(
            $image->content,
            200,
            ['Content-Type' => $image->mime ?? 'application/octet-stream']
        );
    }

    /**
     * Több kép feltöltése egy kedvenc autóhoz.
     */
    public function store(StoreCarImageRequest $request, FavoriteCar $favoriteCar)
    {
        $this->authorize('create', $favoriteCar);

        $this->images->uploadImages(
            $favoriteCar->id,
            $request->file('images')
        );

        return back()->with('success', 'A képek sikeresen feltöltve.');
    }

    /**
     * Egy kép törlése.
     */
    public function destroy(FavoriteCar $favoriteCar, CarImage $carImage)
    {
        // Biztosan az adott autóhoz tartozik a kép?
        if ($carImage->favorite_car_id !== $favoriteCar->id) {
            abort(403, 'Ehhez az autóhoz nem tartozik ez a kép.');
        }

        $this->authorize('delete', $favoriteCar);

        $this->images->delete($carImage->id);

        return back()->with('success', 'A kép törölve.');
    }
}
