<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QrisController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Awal
|--------------------------------------------------------------------------
*/
Route::redirect('/', '/login');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Area Login (Admin & Kasir)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kasir / POS
    Route::controller(PosController::class)->group(function () {
        Route::get('/pos', 'index')->name('pos');
        Route::post('/pos', 'store')->name('pos.store');
        Route::get('/struk/{transaction}', 'struk')->name('struk');
        Route::get('/struk/{transaction}/dapur', 'strukDapur')->name('struk.dapur');
    });

    // Riwayat transaksi
    Route::controller(ReportController::class)->prefix('riwayat')->group(function () {
        Route::get('/', 'riwayat')->name('riwayat');
        Route::get('/{transaction}', 'detail')->name('riwayat.detail');
    });

    /*
    |----------------------------------------------------------------------
    | Khusus Admin
    |----------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        // Kategori & produk
        Route::post('/produk/qris', [QrisController::class, 'update'])->name('qris.update');
        Route::delete('/produk/qris', [QrisController::class, 'destroy'])->name('qris.destroy');
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except('show');
        Route::patch('/products/{product}/toggle', [ProductController::class, 'toggle'])
            ->name('products.toggle');
            

        // Pengguna
        Route::resource('users', UserController::class)->except('show');

        // Outlet
        Route::controller(OutletController::class)->prefix('outlet')->name('outlet.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/edit', 'edit')->name('edit');
            Route::put('/', 'update')->name('update');
        });

        // Laporan
        Route::controller(ReportController::class)->prefix('laporan')->group(function () {
            Route::get('/', 'laporan')->name('laporan');
            Route::get('/export', 'export')->name('laporan.export');
        });
    });
});