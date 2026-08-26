<?php

/**
 * KeranjangController
 *
 * Menangani keranjang belanja pembeli: menambah produk, mengubah quantity,
 * dan menghapus item.
 *
 * Keranjang disimpan di session, bukan tabel tersendiri, karena naskah soal
 * tidak mencantumkan tabel keranjang. Yang disimpan hanya pasangan
 * id_product => quantity; data produk selalu dibaca ulang dari basis data.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Controllers;

use App\Http\Requests\KeranjangRequest;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KeranjangController extends Controller
{
    /** Kunci penyimpanan keranjang pada session. */
    private const SESSION_KEY = 'keranjang';

    /**
     * Halaman keranjang beserta rincian subtotal dan total belanja.
     */
    public function index(): View
    {
        $item = Produk::dariKeranjang($this->isiKeranjang());

        return view('keranjang.index', [
            'item' => $item,
            'total' => $item->sum('subtotal'),
        ]);
    }

    /**
     * Memasukkan satu produk ke keranjang.
     */
    public function store(int $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);

        if ($produk->status_product !== 'tersedia' || $produk->stok < 1) {
            return back()->with('error', 'Produk sedang tidak tersedia.');
        }

        $keranjang = $this->isiKeranjang();
        $quantity = ($keranjang[$id] ?? 0) + 1;

        // Penambahan berulang dibatasi stok agar keranjang tidak melampaui persediaan
        if ($quantity > $produk->stok) {
            return back()->with('error', 'Jumlah pesanan melebihi stok yang tersedia.');
        }

        $keranjang[$id] = $quantity;
        session([self::SESSION_KEY => $keranjang]);

        return redirect()->route('keranjang.index')
            ->with('success', $produk->nama_product.' ditambahkan ke keranjang.');
    }

    /**
     * Mengubah quantity satu item keranjang.
     */
    public function update(KeranjangRequest $request, int $id): RedirectResponse
    {
        $keranjang = $this->isiKeranjang();

        if (! isset($keranjang[$id])) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Produk tersebut tidak ada di keranjang.');
        }

        $keranjang[$id] = (int) $request->validated()['quantity'];
        session([self::SESSION_KEY => $keranjang]);

        return redirect()->route('keranjang.index')
            ->with('success', 'Jumlah pesanan diperbarui.');
    }

    /**
     * Mengeluarkan satu produk dari keranjang.
     */
    public function destroy(int $id): RedirectResponse
    {
        $keranjang = $this->isiKeranjang();
        unset($keranjang[$id]);
        session([self::SESSION_KEY => $keranjang]);

        return redirect()->route('keranjang.index')
            ->with('success', 'Produk dikeluarkan dari keranjang.');
    }

    /**
     * Isi keranjang pada session dalam bentuk id_product => quantity.
     *
     * @return array<int, int>
     */
    private function isiKeranjang(): array
    {
        return session(self::SESSION_KEY, []);
    }
}
