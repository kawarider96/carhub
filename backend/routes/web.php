<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\User\DashboardController;
use App\Http\Controllers\Web\User\FavoriteCarController;
use App\Http\Controllers\Web\User\CarImageController;
use App\Http\Controllers\Web\User\CarModelController;
use App\Http\Controllers\Web\Admin\UserRequestController as WebUserRequestController;
use App\Http\Controllers\Web\Admin\UserController as WebUserController;
use App\Http\Controllers\Web\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Web\Admin\CarBrandController as AdminCarBrandController;
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

    // User által új autómodell rögzítése egy márkához
    Route::post('/brands/{brand}/models', [CarModelController::class, 'store'])
        ->name('brands.models.store');

    // User modell létrehozó oldal
    Route::get('/models/create', [CarModelController::class, 'create'])
        ->name('models.create');

    // UserRequests (USER) – ugyanazon controller alatt (Web/Admin/...)
    Route::prefix('user-requests')->name('user-requests.')->group(function () {
        Route::get('/create', [WebUserRequestController::class, 'create'])->name('create');
        Route::post('/', [WebUserRequestController::class, 'store'])->name('store');
    });

    // PROFILE
    Route::get('/profile', [WebUserController::class, 'profile'])->name('profile.show');
    Route::get('/profile/change-password', [WebUserController::class, 'changePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [WebUserController::class, 'changePassword'])->name('profile.change-password.post');
});

// Admin – UserRequests kezelése
Route::middleware(['auth', 'active'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/requests', [WebUserRequestController::class, 'index'])->name('requests.index');
    Route::post('/requests/{userRequest}/approve', [WebUserRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{userRequest}/reject', [WebUserRequestController::class, 'reject'])->name('requests.reject');

    // Admin – márkák
    Route::get('/brands/create', [AdminCarBrandController::class, 'create'])->name('brands.create');
    Route::post('/brands', [AdminCarBrandController::class, 'store'])->name('brands.store');

    // Admin – felhasználók
    Route::get('/users/create', [WebUserController::class, 'adminCreateForm'])->name('users.create');
    Route::post('/users', [WebUserController::class, 'adminStore'])->name('users.store');
    Route::get('/users/{user}/edit', [WebUserController::class, 'adminEditForm'])->name('users.edit');
    Route::patch('/users/{user}', [WebUserController::class, 'adminUpdate'])->name('users.update');
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

        // Szerkesztő form
        Route::get('/{favoriteCar}/edit', [FavoriteCarController::class, 'edit'])->name('edit');

        // Frissítés
        Route::put('/{favoriteCar}', [FavoriteCarController::class, 'update'])->name('update');

        // Törlés
        Route::delete('/{favoriteCar}', [FavoriteCarController::class, 'destroy'])->name('destroy');

        // Megtekintés
        Route::get('/{favoriteCar}', [FavoriteCarController::class, 'show'])->name('show');

        // --- Car Image routes (favorites alatt, auth+active) ---
        Route::prefix('{favoriteCar}/images')
            ->name('images.')
            ->group(function () {

                // Összes kép listázása (AJAX)
                Route::get('/', [CarImageController::class, 'index'])->name('index');

                // Képfeltöltés
                Route::post('/', [CarImageController::class, 'store'])->name('store');

                // Egy kép megjelenítése (bináris)
                Route::get('/{image}', [CarImageController::class, 'show'])->name('show');

                // Egy kép törlése
                Route::delete('/{image}', [CarImageController::class, 'destroy'])->name('destroy');
            });

    });
