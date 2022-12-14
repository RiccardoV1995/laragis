<?php

use App\Http\Controllers\ListingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Listings Routes

Route::get('/', [ListingController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/listings/create', [ListingController::class, 'create']);
    Route::post('/listings', [ListingController::class, 'store']);
    Route::get('/listings/{listing}/edit', [ListingController::class, 'edit']);
    Route::put('/listings/{listing}', [ListingController::class, 'update']);
    Route::delete('/listings/{listing}', [ListingController::class, 'destroy']);
    Route::get('/listings/manage', [ListingController::class, 'userListings']);
});

Route::get('/listings/{listing}', [ListingController::class, 'show']);

// User Routes

// Show Register Form
Route::get('/register', [UserController::class, 'create'])->middleware('guest');

// Register User
Route::post('/users', [UserController::class, 'store']);

// Show Login Form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Login User
Route::post('/auth', [UserController::class, 'auth']);

// Logout
Route::post('/logout', [UserController::class, 'logout']);
