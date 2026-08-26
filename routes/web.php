<?php

/**
 * Definisi Route Aplikasi MarketPlace Sederhana
 *
 * Route dikelompokkan menurut proses pada naskah soal:
 * publik (landing & detail produk), tamu (pendaftaran & login),
 * dan area penjual (manajemen produk) yang dijaga middleware role.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Penjual\ProdukController;
use Illuminate\Support\Facades\Route;

/*
 |----------------------------------------------------------------------
 | Halaman publik
 |----------------------------------------------------------------------
 */
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/produk/{id}', [LandingController::class, 'show'])->name('produk.show');

/*
 |----------------------------------------------------------------------
 | Proses Pendaftaran dan Login (hanya untuk tamu)
 |----------------------------------------------------------------------
 */
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
 |----------------------------------------------------------------------
 | Proses Manajemen Produk (khusus role penjual)
 |----------------------------------------------------------------------
 */
Route::middleware(['auth', 'role:penjual'])
    ->prefix('penjual')
    ->name('penjual.')
    ->group(function () {
        // show tidak dipakai karena detail produk sudah tersedia di halaman publik
        // parameters() menyelaraskan nama parameter route menjadi {id}
        // sesuai daftar route pada dokumen brief
        Route::resource('produk', ProdukController::class)
            ->parameters(['produk' => 'id'])
            ->except(['show']);
    });
