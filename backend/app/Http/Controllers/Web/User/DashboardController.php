<?php

namespace App\Http\Controllers\Web\User;

use App\Http\Controllers\Controller;
use App\Models\FavoriteCar;
use App\Models\CarImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

/**
 * Class DashboardController
 *
 * Felhasználói dashboard kezelése.
 * Megjeleníti a statisztikákat, legutóbbi módosított elemeket és gyorsműveleteket.
 *
 * @package App\Http\Controllers\Web\User
 */
class DashboardController extends Controller implements HasMiddleware
{
    /**
     * Middleware deklaráció – Laravel 11 standard
     *
     * @return array<int, \Illuminate\Routing\Controllers\Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('active'),
        ];
    }

    /**
     * A dashboard főoldala.
     *
     * Itt jelennek meg:
     * - kedvenc autók száma
     * - feltöltött képek száma
     * - utoljára módosított autó
     * - legutóbb szerkesztett autók listája
     *
     * @return View
     */
    public function index(): View
    {
        $user = Auth::user();

        // Kedvenc autók darabszám
        $favoriteCount = FavoriteCar::where('user_id', $user->id)->count();

        // Feltöltött képek darabszáma
        $imageCount = CarImage::whereHas('favoriteCar', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        // Legutóbb módosított autó (created_at vagy updated_at alapján)
        $lastModified = FavoriteCar::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->first();

        // Legutóbb módosított autók listája (2 elem)
        $recentCars = FavoriteCar::where('user_id', $user->id)
            ->orderBy('updated_at', 'desc')
            ->take(2)
            ->get();

        return view('user.dashboard', [
            'favoriteCount' => $favoriteCount,
            'imageCount'    => $imageCount,
            'lastModified'  => $lastModified,
            'recentCars'    => $recentCars,
        ]);
    }
}
