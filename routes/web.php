<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    ProfileController, ProductController, CartController,
    CheckoutController, OrderController, OutletController
};

// Public route
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('backend.dashboard');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared among all authenticated users
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');


    // Super Admin only access
    Route::middleware('role:super_admin')->group(function () {
    	Route::get('/products', [ProductController::class, 'productLists'])->name('products.all');
        Route::get('/outlets', [OutletController::class, 'outletLists'])->name('outlets.all');
    });

    
    Route::middleware('role:super_admin,admin,outlet')->group(function () {
	    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
	    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
	    Route::post('/orders/{order}/accept', [OrderController::class, 'accept'])->name('orders.accept');
	    Route::post('/orders/{order}/transfer', [OrderController::class, 'transfer'])->name('orders.transfer');
	});

	Route::middleware('role:admin,super_admin')->group(function () {
	    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
	    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
	});
});

require __DIR__.'/auth.php';
