<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoriteCar;
use App\Models\CarImage;
use App\Models\User;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __construct()
    {}

    public function index(): View
    {
        $favoriteCount = FavoriteCar::count();
        $imageCount    = CarImage::count();
        $userCount     = User::count();

        $users = User::orderByDesc('created_at')->paginate(10);

        return view('pages.admin.dashboard.index', [
            'favoriteCount' => $favoriteCount,
            'imageCount'    => $imageCount,
            'userCount'     => $userCount,
            'users'         => $users,
        ]);
    }
}
