<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    AuthController,
    DashboardController,
    ProfileController,
    ProductController,
    CartController,
    OrderController,
    SellerOrderController,
    AdminController,
    CategoriesController,
    ReportController
};

// --- HALAMAN UMUM (Public) ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('product/{id}', [HomeController::class, 'show'])->name('product.show');

// --- OTENTIKASI (Hanya untuk tamu/belum login) ---
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/register', 'registerForm')->name('register');
        Route::post('/register', 'register');
        Route::get('/login', 'loginForm')->name('login');
        Route::post('/login', 'login');
    });
});

// --- SEMUA USER LOGIN (Pembeli, Penjual, Admin) ---
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil (Mengelompokkan rute dengan controller yang sama)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'index')->name('profile.index');
        Route::get('/profile/edit', 'edit')->name('profile.edit');
        Route::put('/profile', 'update')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
    });

    // Laporan (General Resource)
    Route::resource('reports', ReportController::class);

    // --- KHUSUS PEMBELI ---
    Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add/{productId}', 'store')->name('store');
        Route::put('/update/{id}', 'update')->name('update');
        Route::delete('/remove/{id}', 'destroy')->name('destroy');
    });

    Route::controller(OrderController::class)->prefix('orders')->name('order.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/checkout', 'checkout')->name('checkout');
        Route::post('/{id}/upload-proof', 'uploadProof')->name('upload-proof');
        Route::post('/{id}/complete', 'complete')->name('complete');
        Route::get('/{id}/invoice', 'showInvoice')->name('invoice');
    });

    // --- KHUSUS PENJUAL ---
    Route::middleware('role:penjual')->prefix('seller')->name('seller.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Produk
        Route::resource('products', ProductController::class)->except(['show']);

        // Manajemen Pesanan Masuk
        Route::controller(SellerOrderController::class)->prefix('orders')->name('orders.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
            Route::post('/{id}/status', 'updateStatus')->name('update-status');
            Route::post('/{id}/verify', 'verifyPayment')->name('verify');
        });
    });

    // --- KHUSUS ADMIN ---
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::controller(AdminController::class)->group(function () {
            Route::get('/verifications', 'verifications')->name('verifications.index');
            Route::patch('/verifications/{id}', 'verify')->name('verifications.verify');
            Route::get('/users', 'users')->name('users.index');
            Route::patch('/users/{id}/role', 'updateRole')->name('users.update-role');
            Route::delete('/users/{id}', 'destroyUser')->name('users.destroy');
        });

        Route::resource('categories', CategoriesController::class);
    });

    // Update Status Laporan (Hanya Admin, dikeluarkan dari grup name('admin.') agar namanya pas 'reports.updateStatus')
    Route::patch('/admin/reports/{report}/status', [ReportController::class, 'updateStatus'])
        ->middleware('role:admin')
        ->name('reports.updateStatus');
});
