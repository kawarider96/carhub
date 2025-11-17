<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreFavoriteCarRequest;
use App\Http\Requests\User\UpdateFavoriteCarRequest;
use App\Models\FavoriteCar;
use App\Services\FavoriteCarService;
use App\Services\CarBrandService;
use App\Services\CarModelService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Class FavoriteCarController
 *
 * Kezeli a felhasználó kedvenc autóinak megjelenítését és CRUD műveleteit.
 */
class FavoriteCarController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected FavoriteCarService $favorites,
        protected CarBrandService $brands,
        protected CarModelService $models
    ) {}

    /**
     * A felhasználó saját kedvenc autóinak listája.
     *
     * @return View
     */
    public function index(): View
    {
        $userId = Auth::id();

        /** @var \Illuminate\Support\Collection<int, FavoriteCar> $favoriteCars */
        $favoriteCars = $this->favorites->forUser($userId);

        $carImages = [];

        foreach ($favoriteCars as $car) {
            $carImages[$car->id] = $car->images->map(
                fn($img) => route('favorites.images.show', [
                    'favoriteCar' => $car->id,
                    'image'       => $img->id,
                ])
            )->toArray();
        }

        return view('pages.user.favorites.index', [
            'favorites'  => $favoriteCars,
            'carImages'  => $carImages,
        ]);
    }

    /**
     * Új kedvenc autó létrehozó form megtekintése.
     *
     * @return View
     */
    public function create(): View
    {
        $brands = $this->brands->all();
        $models = $this->models->all();

        $modelsByBrand = $models
            ->groupBy('car_brand_id')
            ->map(fn($items) => $items->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
            ])->values())
            ->toArray();

        return view('pages.user.favorites.create', [
            'brands'        => $brands,
            'modelsByBrand' => $modelsByBrand,
        ]);
    }

    /**
     * Új kedvenc autó mentése.
     *
     * @param StoreFavoriteCarRequest $request
     * @return RedirectResponse
     */
    public function store(StoreFavoriteCarRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        $this->favorites->create($data);

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Autó sikeresen hozzáadva a kedvencekhez.');
    }

    /**
     * Egy kedvenc autó részleteinek megtekintése.
     *
     * @param FavoriteCar $favoriteCar
     * @return View
     */
    public function show(FavoriteCar $favoriteCar): View
    {
        $this->authorize('view', $favoriteCar);

        $favoriteCar->load(['carModel.brand', 'images']);

        return view('pages.user.favorites.show', [
            'favoriteCar' => $favoriteCar,
        ]);
    }

    /**
     * Kedvenc autó szerkesztő form megjelenítése.
     *
     * @param FavoriteCar $favoriteCar
     * @return View
     */
    public function edit(FavoriteCar $favoriteCar): View
    {
        $this->authorize('update', $favoriteCar);

        $brands = $this->brands->all();
        $models = $this->models->all();

        $modelsByBrand = $models
            ->groupBy('car_brand_id')
            ->map(fn($items) => $items->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
            ])->values())
            ->toArray();

        return view('pages.user.favorites.edit', [
            'favoriteCar'   => $favoriteCar,
            'brands'        => $brands,
            'modelsByBrand' => $modelsByBrand,
            'images'        => $favoriteCar->images,
        ]);
    }

    /**
     * Kedvenc autó frissítése.
     *
     * @param UpdateFavoriteCarRequest $request
     * @param FavoriteCar $favoriteCar
     * @return RedirectResponse
     */
    public function update(
        UpdateFavoriteCarRequest $request,
        FavoriteCar $favoriteCar
    ): RedirectResponse {
        $this->authorize('update', $favoriteCar);

        $this->favorites->update($favoriteCar->id, $request->validated());

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Kedvenc autó frissítve.');
    }

    /**
     * Kedvenc autó törlése.
     *
     * @param FavoriteCar $favoriteCar
     * @return RedirectResponse
     */
    public function destroy(FavoriteCar $favoriteCar): RedirectResponse
    {
        $this->authorize('delete', $favoriteCar);

        $this->favorites->delete($favoriteCar->id);

        return redirect()
            ->route('favorites.index')
            ->with('success', 'Kedvenc autó törölve.');
    }
}
