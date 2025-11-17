<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CarModel\StoreCarModelRequest;
use App\Models\CarBrand;
use App\Services\CarModelService;
use Illuminate\Http\RedirectResponse;

/**
 * Felhasználók által rögzíthető autómodellek kezelése.
 *
 * A felhasználó csak új modellt hozhat létre, ha a kiválasztott
 * márkához nem létezik még a típus. Törlés és szerkesztés nem engedélyezett.
 */
class CarModelController extends Controller
{
    /**
     * @param CarModelService $models
     */
    public function __construct(
        protected CarModelService $models
    ) {
        $this->middleware(['auth', 'active']);
    }

    /**
     * Új típus rögzítése (felhasználó által).
     *
     * @param StoreCarModelRequest $request
     * @param CarBrand $brand
     *
     * @return RedirectResponse
     */
    public function store(StoreCarModelRequest $request, CarBrand $brand): RedirectResponse
    {
        // user nem vihet fel rossz brand ID-t
        if ((int)$request->input('car_brand_id') !== (int)$brand->id) {
            abort(400, 'Márka ID nem egyezik.');
        }

        // service szintű create
        $this->models->create($request->validated());

        return back()->with('success', 'Új autótípus sikeresen rögzítve.');
    }
}
