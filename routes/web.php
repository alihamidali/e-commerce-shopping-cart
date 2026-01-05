<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/products');
    }
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return redirect('/products');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Product Pages
    Route::get('/products', function () {
        return Inertia::render('Products/Index');
    })->name('products.index');

    // Cart Page
    Route::get('/cart', function () {
        return Inertia::render('Cart/Index');
    })->name('cart.index');

    // Orders Page
    Route::get('/orders', function () {
        return Inertia::render('Orders/Index');
    })->name('orders.index');

    // API Routes for Products
    Route::prefix('api')->group(function () {
        Route::apiResource('products', ProductController::class);

        // Cart API Routes
        Route::get('cart', [CartController::class, 'index'])->name('api.cart.index');
        Route::post('cart', [CartController::class, 'store'])->name('api.cart.store');
        Route::patch('cart/{id}', [CartController::class, 'update'])->name('api.cart.update');
        Route::delete('cart/{id}', [CartController::class, 'destroy'])->name('api.cart.destroy');
        Route::delete('cart', [CartController::class, 'clear'])->name('api.cart.clear');
        Route::get('cart/total', [CartController::class, 'total'])->name('api.cart.total');

        // Order API Routes
        Route::get('orders', [OrderController::class, 'index'])->name('api.orders.index');
        Route::post('orders', [OrderController::class, 'store'])->name('api.orders.store');
        Route::get('orders/{id}', [OrderController::class, 'show'])->name('api.orders.show');
    });
});

require __DIR__.'/auth.php';
