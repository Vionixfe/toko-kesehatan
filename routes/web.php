<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\UserOrderController;
use App\Http\Controllers\Backend\UserProfileController;
use App\Http\Controllers\Frontend\FrontendReviewController;



Auth::routes();

//  Route::get('/panel/dashboard', [DashboardController::class, 'index'])->name('panel.dashboard');

//  Route::middleware(['auth'])->group(function () {
//     );

// Halaman Khusus Admin (Wajib Login & Role Admin)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/panel/dashboard', [DashboardController::class, 'index'])->name('panel.dashboard');


    Route::get('/profiles', [UserProfileController::class, 'show'])->name('profiles.show');
    Route::put('/profiles', [UserProfileController::class, 'update'])->name('profiles.update');
    Route::get('/profiles/password', [UserProfileController::class, 'editPassword'])->name('profiles.edit.password');
    Route::put('/profiles/password', [UserProfileController::class, 'updatePassword'])->name('profiles.password.update');

    // ROUTE UNTUK PRODUK
    Route::resource('products', ProductController::class);
    Route::get('products-data', [ProductController::class, 'getProducts'])->name('products.data');

    // ROUTE UNTUK KATEGORI
    Route::get('/categories/data', [CategoryController::class, 'getCategories'])->name('categories.data');
    Route::resource('categories', CategoryController::class);

    // ROUTE UNTUK CUSTOMER
    Route::resource('customers', CustomerController::class);
    Route::get('customers-data', [CustomerController::class, 'getCustomers'])->name('customers.data');
    Route::post('customers/{user}/toggle-role', [CustomerController::class, 'toggleRole'])->name('customers.toggleRole');

    // ROUTE UNTUK MANAJEMEN ULASAN
    Route::resource('reviews', ReviewController::class);
    Route::get('reviews-data', [ReviewController::class, 'getReviews'])->name('reviews.data');

    // ROUTE KHUSUS UNTUK MANAJEMEN PESANAN
    Route::resource('orders', OrderController::class);
    Route::get('orders-data', [OrderController::class, 'getOrders'])->name('orders.data');
    Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

     Route::post('/reports/export', [ReportController::class, 'export'])->name('reports.export');

});

Route::middleware(['auth', 'customer'])->group(function () {

    // PERBAIKAN: Tambahkan route untuk halaman "Pesanan Saya"
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('my-orders.index');
    Route::get('/my-orders/{order}', [UserOrderController::class, 'show'])->name('my-orders.show');
    Route::post('/my-orders/{order}/upload-proof', [UserOrderController::class, 'uploadPaymentProof'])->name('my-orders.upload_proof');
    Route::post('/my-orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('my-orders.cancel');
    Route::delete('/my-orders/{order}', [UserOrderController::class, 'destroy'])->name('my-orders.destroy');


    // Route untuk Keranjang Belanja
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{uuid}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{uuid}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Route untuk Checkout
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');

    // ROUTE PROFIL UNTUK CUSTOMER (FRONTEND)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

     // ROUTE UNTUK MEMBERI ULASAN
        Route::get('/reviews/create/product/{product}', [FrontendReviewController::class, 'create'])->name('reviews.create');
        Route::post('/review', [FrontendReviewController::class, 'store'])->name('reviews.store');
});

// ROUTE UNTUK PENGUNJUNG & CUSTOMER
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/shop', [FrontendController::class, 'shop'])->name('shop.index');
// Route::get('/products/filter', [FrontendController::class, 'filter'])->name('shop.filter');

Route::get('/is-products/{product:slug}', [FrontendController::class, 'shows'])->name('products.shows');

// ROUTE UNTUK HALAMAN LOGIN
Route::get('/login', function () {
    return view('auth.login');
})->name('login')->middleware('guest');

