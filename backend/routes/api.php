<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CarBrandController;
use App\Http\Controllers\Api\User\CarModelController;
use App\Http\Controllers\Api\User\FavoriteCarController;
use App\Http\Controllers\Api\Admin\UserRequestController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (sanctum token required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Kijelentkezés
    Route::post('/logout', [LogoutController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | USER ROUTES (minden aktív user)
    |--------------------------------------------------------------------------
    */

    // Favorite Car CRUD
    Route::apiResource('favorite-cars', FavoriteCarController::class);

    // Autó törlési kérelem indítása
    Route::post('/user/delete-request', [UserRequestController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        // Márkák CRUD
        Route::apiResource('car-brands', CarBrandController::class);

        // Modellek CRUD
        Route::apiResource('car-models', CarModelController::class);

        // Felhasználók törlési kérelmeinek kezelése
        Route::get('/user/delete-requests', [UserRequestController::class, 'index']);
        Route::delete('/user/delete-requests/{id}', [UserRequestController::class, 'destroy']);

        // Admin feloldhatja a zárolt felhasználót
        Route::post('/users/{id}/unlock', [UserController::class, 'unlock']);

        // Admin létrehozhat új user-t
        Route::post('/users', [UserController::class, 'store']);

        // Admin listázni tudja a usereket
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);
    });
});
