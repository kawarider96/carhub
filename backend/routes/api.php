<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CarBrandController;
use App\Http\Controllers\Api\User\CarModelController;
use App\Http\Controllers\Api\User\FavoriteCarController;
use App\Http\Controllers\Api\Admin\UserRequestController;
use App\Http\Controllers\Api\User\CarImageController;

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
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
    Route::get('/favorite-cars/{id}', [FavoriteCarController::class, 'show']);
    Route::put('/favorite-cars/{id}', [FavoriteCarController::class, 'update']);
    Route::delete('/favorite-cars/{id}', [FavoriteCarController::class, 'delete']);
    
    // Car Image routes
    Route::get('/favorite-cars/{favoriteCar}/images', [CarImageController::class, 'index']);
    Route::post('/favorite-cars/{favoriteCar}/images', [CarImageController::class, 'store']);
    Route::get('/images/{carImage}', [CarImageController::class, 'show']);
    Route::delete('/images/{carImage}', [CarImageController::class, 'destroy']);

    // CAR BRAND CRUD
    Route::get('/car-brands', [CarBrandController::class, 'index']);
    Route::post('/car-brands', [CarBrandController::class, 'store']);
    Route::get('/car-brands/{id}', [CarBrandController::class, 'show']);
    Route::put('/car-brands/{id}', [CarBrandController::class, 'update']);
    Route::delete('/car-brands/{id}', [CarBrandController::class, 'destroy']);


    // Modellek CRUD
    Route::get('/car-models', [CarModelController::class, 'index']);
    Route::post('/car-models', [CarModelController::class, 'store']);
    Route::get('/car-models/{id}', [CarModelController::class, 'show']);
    Route::put('/car-models/{id}', [CarModelController::class, 'update']);
    Route::delete('/car-models/{id}', [CarModelController::class, 'destroy']);


    // UserRequests
    Route::get('/user-requests', [UserRequestController::class, 'index']);
    Route::post('/user-request', [UserRequestController::class, 'store']);
    Route::post('/user-requests/approve', [UserRequestController::class, 'approve']);
    Route::post('/user-requests/reject', [UserRequestController::class, 'reject']);

    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users', [UserController::class, 'update']);
    Route::put('/users/{id}', [UserController::class, 'adminUpdate']);
    Route::post('/users', [UserController::class, 'adminStore']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/users/{id}/lock', [UserController::class, 'lock']);
    Route::post('/users/{id}/unlock', [UserController::class, 'unlock']);
});
