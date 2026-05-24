<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('home');
})->name('home');

// ==================== ROUTE AUTHENTIKASI ====================
// Tampilkan form register
Route::get('/register', [AuthController::class, 'registerForm'])
    ->name('register')
    ->middleware('guest'); // hanya bisa diakses jika BELUM login
// Proses simpan register
Route::post('/register', [AuthController::class, 'register'])
    ->middleware('guest');
// Tampilkan form login
Route::get('/login', [AuthController::class, 'loginForm'])
    ->name('login') // nama route ini WAJIB 'login' agar middleware auth berfungsi
    ->middleware('guest');
// Proses login
Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');
// Proses logout (gunakan POST untuk keamanan, bukan GET)
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth'); // hanya bisa diakses jika SUDAH login

// ==================== ROUTE YANG DILINDUNGI ====================
// Semua route di dalam group ini hanya bisa diakses jika sudah login
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
// Route::get('/', function () {
//     return view('welcome');
// });
