<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MovementController;
use App\Http\Controllers\HomeController;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Wallet CRUD routes
    Route::resource('accounts',      AccountController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('categories',    CategoryController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('subcategories', SubcategoryController::class)->only(['index', 'show', 'store', 'update', 'destroy']);
    Route::resource('services',      ServiceController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    Route::resource('movements', MovementController::class)->only(['index', 'show', 'store', 'update', 'destroy']);

    Route::group(['prefix' => '/'], function () {
        Route::get('', [HomeController::class, 'index'])->name('root');
    });
});
