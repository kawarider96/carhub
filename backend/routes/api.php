<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CarBrandController;
use App\Http\Controllers\Api\Admin\UserRequestController;
use App\Http\Controllers\Api\User\CarModelController;
use App\Http\Controllers\Api\User\FavoriteCarController;
use App\Http\Controllers\Api\User\CarImageController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (sanctum token required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum', 'active')->group(function () {

    // Kijelentkezés
    Route::post('/logout', [AuthController::class, 'logout']);

    // Favorite Car CRUD
    Route::get('/favorite-cars', [FavoriteCarController::class, 'index']);
    Route::post('/favorite-cars', [FavoriteCarController::class, 'store']);
    Route::get('/favorite-cars/{favoriteCar}', [FavoriteCarController::class, 'show']);
    Route::put('/favorite-cars/{favoriteCar}', [FavoriteCarController::class, 'update']);
    Route::delete('/favorite-cars/{favoriteCar}', [FavoriteCarController::class, 'destroy']);
    
    // Car Image routes
    Route::get('/favorite-cars/{favoriteCar}/images', [CarImageController::class, 'index']);
    Route::post('/favorite-cars/{favoriteCar}/images', [CarImageController::class, 'store']);
    Route::get('/images/{carImage}', [CarImageController::class, 'show']);
    Route::delete('/images/{carImage}', [CarImageController::class, 'destroy']);

    // CAR BRAND CRUD
    Route::get('/car-brands', [CarBrandController::class, 'index']);
    Route::post('/car-brands', [CarBrandController::class, 'store']);
    Route::get('/car-brands/{brand}', [CarBrandController::class, 'show']);
    Route::put('/car-brands/{brand}', [CarBrandController::class, 'update']);
    Route::delete('/car-brands/{brand}', [CarBrandController::class, 'destroy']);


    // Modellek CRUD
    Route::get('/car-models', [CarModelController::class, 'index']);
    Route::post('/car-models', [CarModelController::class, 'store']);
    Route::get('/car-models/{carModel}', [CarModelController::class, 'show']);
    Route::put('/car-models/{carModel}', [CarModelController::class, 'update']);
    Route::delete('/car-models/{carModel}', [CarModelController::class, 'destroy']);


    // UserRequests
    Route::get('/requests', [UserRequestController::class, 'index']);
    Route::post('/requests', [UserRequestController::class, 'store']);
    Route::post('/requests/{userRequest}/approve', [UserRequestController::class, 'approve']);
    Route::post('/requests/{userRequest}/reject', [UserRequestController::class, 'reject']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users', [UserController::class, 'update']);
    Route::put('/users/{user}', [UserController::class, 'adminUpdate']);
    Route::post('/users', [UserController::class, 'adminStore']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/users/{user}/lock', [UserController::class, 'lock']);
    Route::post('/users/{user}/unlock', [UserController::class, 'unlock']);
});
