<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\User\DashboardController;
use App\Http\Controllers\Web\User\FavoriteCarController;
use App\Http\Controllers\Web\HomeController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| USER AUTH
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');
});

// Logout (csak belépett user)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('auth.logout');

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');


/*
|--------------------------------------------------------------------------
| USER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
});

/*
|--------------------------------------------------------------------------
| FAVORITE CARS (CRUD)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])
    ->prefix('favorites')
    ->name('favorites.')
    ->group(function () {

        // Listázás (index)
        Route::get('/', [FavoriteCarController::class, 'index'])->name('index');

        // Új autó form
        Route::get('/create', [FavoriteCarController::class, 'create'])->name('create');

        // Mentés
        Route::post('/', [FavoriteCarController::class, 'store'])->name('store');

        // Megtekintés
        Route::get('/{favoriteCar}', [FavoriteCarController::class, 'show'])->name('show');

        // Szerkesztő form
        Route::get('/{favoriteCar}/edit', [FavoriteCarController::class, 'edit'])->name('edit');

        // Frissítés
        Route::put('/{favoriteCar}', [FavoriteCarController::class, 'update'])->name('update');

        // Törlés
        Route::delete('/{favoriteCar}', [FavoriteCarController::class, 'destroy'])->name('destroy');
    });
