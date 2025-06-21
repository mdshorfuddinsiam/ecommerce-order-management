<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    AuthController,
    ProductController,
    OutletController,
    OrderController,
};

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    // Super Admin
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::get('/outlets', [OutletController::class, 'index']);
    });

    // Admin & Super Admin
    Route::middleware('role:admin,super_admin')->group(function () {
        Route::post('/orders', [OrderController::class, 'store']);
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    });

    // Admin, Super Admin & Outlet
    Route::middleware('role:admin,super_admin,outlet')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);
        Route::post('/orders/{order}/accept', [OrderController::class, 'accept']);
        Route::post('/orders/{order}/transfer', [OrderController::class, 'transfer']);
    });

    Route::post('/logout', [AuthController::class, 'logout']);
});