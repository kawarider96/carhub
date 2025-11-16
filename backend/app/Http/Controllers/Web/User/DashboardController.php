<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Services\FavoriteCarService;
use App\Services\CarImageService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Felhasználói dashboard
 *
 * Megjeleníti:
 * - kedvenc autók számát
 * - feltöltött képek számát
 * - utoljára módosított kedvenc autót
 * - legutóbb módosított autók listáját
 */
class DashboardController extends Controller
{
    public function __construct(
        protected FavoriteCarService $favoriteCars,
        protected CarImageService $images
    ) {}

    /**
     * Dashboard főoldal.
     */
    public function index(): View
    {
        $userId = Auth::id();

        //Felhasználó kedvenc autói
        $favorites = $this->favoriteCars->forUser($userId);

        // Kedvenc autók száma
        $favoriteCount = $favorites->count();

        //Képek száma (repo + service segítségével)
        $imageCount = $this->imagesCountForUser($userId);

        //Utoljára módosított kedvenc autó
        $lastModified = $favorites
            ->sortByDesc('updated_at')
            ->first();

        //Legutóbb módosított autók (2 db)
        $recentCars = $favorites
            ->sortByDesc('updated_at')
            ->take(2);

        return view('pages.user.dashboard.index', [
            'favoriteCount' => $favoriteCount,
            'imageCount'    => $imageCount,
            'lastModified'  => $lastModified,
            'recentCars'    => $recentCars,
        ]);
    }

    /**
     * Felhasználó összes képének száma.
     *
     * A CarImageService csak favorite_car_id alapján ad képeket,
     * ezért itt ki kell számolni a felhasználó kedvenc autói alapján.
     */
    private function imagesCountForUser(int $userId): int
    {
        $favorites = $this->favoriteCars->forUser($userId);

        $count = 0;

        foreach ($favorites as $favorite) {
            $count += $this->images
                ->getByFavoriteCar($favorite->id)
                ->count();
        }

        return $count;
    }
}
