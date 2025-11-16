<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\User\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Pages
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');



// Login form
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('user.login');

// Login submit
Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post');

// Register form
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('user.register');

// Register submit
Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| Admin Authentication (SEPARATE)
|--------------------------------------------------------------------------
*/

// Admin login form
Route::get('/admin/login', [AuthController::class, 'showAdminLoginForm'])
    ->name('admin.login');

// Admin login submit
Route::post('/admin/login', [AuthController::class, 'adminLogin'])
    ->name('admin.login.post');

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');   // csak bejelentkezett user