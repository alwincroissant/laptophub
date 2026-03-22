<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminReviewController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\RestockTransactionController;
use App\Http\Controllers\ExpenseReportController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminAccountController;
use App\Http\Controllers\ReviewController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

$landingPage = function () {
    $featuredCategories = Category::query()
        ->where('is_active', true)
        ->whereNull('deleted_at')
        ->withCount([
            'products as active_products_count' => function ($query) {
                $query->where('is_archived', false)
                    ->whereNull('deleted_at')
                    ->where('stock_qty', '>', 0);
            },
        ])
        ->having('active_products_count', '>', 0)
        ->orderByDesc('active_products_count')
        ->orderBy('name')
        ->limit(6)
        ->get(['category_id', 'name']);

    $featuredBrands = Brand::query()
        ->where('is_active', true)
        ->whereHas('products', function ($query) {
            $query->where('is_archived', false)
                ->whereNull('deleted_at');
        })
        ->orderBy('name')
        ->limit(12)
        ->get(['brand_id', 'name']);

    $featuredProducts = Product::query()
        ->leftJoin('brands', 'products.brand_id', '=', 'brands.brand_id')
        ->leftJoin('reviews', function ($join) {
            $join->on('products.product_id', '=', 'reviews.product_id')
                ->where('reviews.is_visible', true);
        })
        ->where('products.is_archived', false)
        ->whereNull('products.deleted_at')
        ->where('products.stock_qty', '>', 0)
        ->groupBy(
            'products.product_id',
            'products.name',
            'products.image_url',
            'products.price',
            'products.stock_qty',
            'products.low_stock_threshold',
            'products.created_at',
            'brands.name'
        )
        ->orderByDesc(DB::raw('COALESCE(AVG(reviews.rating), 0)'))
        ->orderByDesc(DB::raw('COUNT(reviews.review_id)'))
        ->orderByDesc('products.created_at')
        ->limit(8)
        ->get([
            'products.product_id',
            'products.name',
            'products.image_url',
            'products.price',
            'products.stock_qty',
            'products.low_stock_threshold',
            'products.created_at',
            DB::raw('COALESCE(brands.name, "Unbranded") as brand_name'),
            DB::raw('COALESCE(ROUND(AVG(reviews.rating), 1), 0) as avg_rating'),
            DB::raw('COUNT(reviews.review_id) as review_count'),
        ]);

    return view('index', [
        'featuredCategories' => $featuredCategories,
        'featuredBrands' => $featuredBrands,
        'featuredProducts' => $featuredProducts,
    ]);
};

Route::get('/', $landingPage);

Route::get('/index', $landingPage)->name('index');

Route::view('/terms-and-conditions', 'legal.terms')->name('legal.terms');
Route::view('/privacy-policy', 'legal.privacy')->name('legal.privacy');

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Customer Routes (Public Browsing)
Route::prefix('customer')->name('customer.')->group(function () {
    // Shop routes (public)
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/{productId}', [ShopController::class, 'show'])->name('shop.show');
    Route::get('/search', [ShopController::class, 'search'])->name('shop.search');
});

// Customer Routes (Authenticated Users)
Route::middleware(['auth', 'active'])->prefix('customer')->name('customer.')->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update-qty', [CartController::class, 'updateQty'])->name('cart.update-qty');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/addresses', [CheckoutController::class, 'storeAddress'])->name('checkout.addresses.store');
    Route::post('/checkout/addresses/{address}/default', [CheckoutController::class, 'setDefaultAddress'])->name('checkout.addresses.default');
    Route::post('/checkout/addresses/{address}/delete', [CheckoutController::class, 'destroyAddress'])->name('checkout.addresses.delete');

    // Orders routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Reviews routes
    Route::post('/shop/{productId}/reviews', [ReviewController::class, 'store'])->name('shop.reviews.store');
    Route::put('/shop/{productId}/reviews/{review}', [ReviewController::class, 'update'])->name('shop.reviews.update');
    Route::delete('/shop/{productId}/reviews/{review}', [ReviewController::class, 'destroy'])->name('shop.reviews.destroy');

    // Account routes
    Route::get('/account/profile', [AccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::get('/account/password', [AccountController::class, 'password'])->name('account.password');
    Route::post('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
    Route::get('/account/addresses', [AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses', [AccountController::class, 'storeAddress'])->name('account.addresses.store');
    Route::put('/account/addresses/{address}', [AccountController::class, 'updateAddress'])->name('account.addresses.update');
    Route::post('/account/addresses/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('account.addresses.default');
    Route::delete('/account/addresses/{address}', [AccountController::class, 'destroyAddress'])->name('account.addresses.destroy');
    Route::delete('/account/deactivate', [AccountController::class, 'deactivate'])->name('account.deactivate');
});

Route::middleware(['auth.admin', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/account/profile', [AdminAccountController::class, 'profile'])->name('account.profile');
    Route::post('/account/profile', [AdminAccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::post('/account/password', [AdminAccountController::class, 'updatePassword'])->name('account.password.update');

    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::get('/products/{productId}', [ProductController::class, 'show'])->name('product.show');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('product.destroy');
    Route::patch('/products/{productId}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::delete('/products/{productId}/force', [ProductController::class, 'forceDestroy'])->name('product.force-destroy');
    Route::post('/products/import', [ProductController::class, 'import'])->name('product.import');
    Route::post('/products/images/import', [ProductController::class, 'importImages'])->name('product.images.import');

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

    // ============================================
    // UNIVERSAL ADMIN MODULES
    // (Accessible by Admin AND Inventory Manager)
    // ============================================

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{inventory}/edit', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{inventory}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{inventory}', [InventoryController::class, 'destroy'])->name('inventory.destroy');

    Route::get('/suppliers', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('supplier.show');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('supplier.destroy');
    Route::patch('/suppliers/{supplierId}/restore', [SupplierController::class, 'restore'])->name('supplier.restore');
    Route::delete('/suppliers/{supplierId}/force', [SupplierController::class, 'forceDestroy'])->name('supplier.force-destroy');

    Route::get('/restocks', [RestockTransactionController::class, 'index'])->name('restock.index');
    Route::post('/restocks/store', [RestockTransactionController::class, 'store'])->name('restock.store');
    Route::get('/restocks/supplier-products', [RestockTransactionController::class, 'getSupplierProducts'])->name('restock.supplier-products');

    // ============================================
    // STRICT ADMIN MODULES
    // (Blocked for Inventory Managers)
    // ============================================
    Route::middleware(\App\Http\Middleware\EnsureStrictAdmin::class)->group(function () {
        
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('order.index');
        Route::get('/orders/{orderId}', [AdminOrderController::class, 'show'])->name('order.show');
        Route::patch('/orders/{orderId}/status', [AdminOrderController::class, 'updateStatus'])->name('order.update-status');

        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('review.index');
        Route::get('/reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('review.edit');
        Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('review.update');
        Route::patch('/reviews/{review}/visibility', [AdminReviewController::class, 'toggleVisibility'])->name('review.toggle-visibility');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('review.destroy');

        // FR1.9 Legacy Standard Reports Module Node
        Route::get('/reports/charts', [\App\Http\Controllers\ReportController::class, 'charts'])->name('reports.charts');
        Route::get('/reports/sales', [\App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
        Route::get('/reports/inventory', [\App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
        Route::get('/reports/orders', [\App\Http\Controllers\ReportController::class, 'orders'])->name('reports.orders');
        Route::get('/reports/top-products', [\App\Http\Controllers\ReportController::class, 'topProducts'])->name('reports.top-products');
        
        Route::get('/reports/expenses', [ExpenseReportController::class, 'expenses'])->name('reports.expenses');

        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('user.create');
        Route::post('/users', [UserController::class, 'store'])->name('user.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('user.update');
        Route::patch('/users/{user}/activate', [UserController::class, 'activate'])->name('user.activate');
        Route::patch('/users/{user}/deactivate', [UserController::class, 'deactivate'])->name('user.deactivate');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('user.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('role.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('role.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('role.destroy');

    });
});

// Email verification routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

// Handle verification link
Route::get('/email/verify/{id}/{hash}', function ($id, $hash) {
    $user = \App\Models\User::find($id);

    if (! $user || ! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('index')->with('error', 'Invalid or expired verification link.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('index')->with('success', 'Your email has been verified! You can now log in.');
})->middleware(['signed'])->name('verification.verify');

// Resend verification link
Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    if (auth()->check()) {
        $user = $request->user();
    } else {
        $request->validate(['email' => 'required|email']);
        $user = \App\Models\User::where('email', $request->email)->first();
    }

    if ($user && ! $user->hasVerifiedEmail()) {
        $user->sendEmailVerificationNotification();
    }

    return back()->with('success', 'If an account with that email exists and is unverified, a verification link has been sent!');
})->middleware(['throttle:6,1'])->name('verification.send');

