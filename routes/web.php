<?php

/**
 * Definisi Route Aplikasi MarketPlace Sederhana
 *
 * Route dikelompokkan menurut proses pada naskah soal.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;

/*
 |----------------------------------------------------------------------
 | Halaman publik
 |----------------------------------------------------------------------
 */
Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/produk/{id}', [LandingController::class, 'show'])->name('produk.show');
