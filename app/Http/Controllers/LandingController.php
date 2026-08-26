<?php

/**
 * LandingController
 *
 * Menampilkan halaman depan marketplace beserta katalog produk yang
 * diambil langsung dari basis data.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\View\View;

class LandingController extends Controller
{
    /**
     * Halaman depan: hero + grid katalog seluruh produk.
     */
    public function index(): View
    {
        // Relasi penjual di-eager load agar nama toko pada kartu produk
        // tidak memicu query tambahan per baris (N+1)
        $produk = Produk::with('penjual')->latest('id_product')->get();

        return view('landing', compact('produk'));
    }

    /**
     * Halaman detail satu produk.
     */
    public function show(int $id): View
    {
        $produk = Produk::with('penjual')->findOrFail($id);

        return view('produk-detail', compact('produk'));
    }
}
