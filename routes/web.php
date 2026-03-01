<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\ServiceController;

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

    // Stub (movements — step 5)
    Route::get('/movements', fn() => abort(503, 'Coming soon'))->name('movements.index');

    Route::group(['prefix' => '/'], function () {
        Route::get('', [RoutingController::class, 'index'])->name('root');
        Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
        Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
        Route::get('{any}', [RoutingController::class, 'root'])->name('any');
    });
});
