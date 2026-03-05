<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;


Route::get('/', function () {
    return view('index');
});

Route::get('/index', function () {
    return view('index');
})->name('index');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Routes (Authenticated Users)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    // Shop routes
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{productId}', [ShopController::class, 'show'])->name('shop.show');
    Route::get('/search', [ShopController::class, 'search'])->name('shop.search');

    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update-qty', [CartController::class, 'updateQty'])->name('cart.update-qty');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // Orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth.admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::get('/products/{productId}', [ProductController::class, 'show'])->name('product.show');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::patch('/products/{productId}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::delete('/products/{productId}/force', [ProductController::class, 'forceDestroy'])->name('product.force-destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('category.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');
    Route::patch('/categories/{categoryId}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    Route::delete('/categories/{categoryId}/force', [CategoryController::class, 'forceDestroy'])->name('category.force-destroy');

    Route::get('/brands', [BrandController::class, 'index'])->name('brand.index');
    Route::get('/brands/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('/brands/{brand}', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');
    Route::patch('/brands/{brandId}/restore', [BrandController::class, 'restore'])->name('brand.restore');
    Route::delete('/brands/{brandId}/force', [BrandController::class, 'forceDestroy'])->name('brand.force-destroy');
});

