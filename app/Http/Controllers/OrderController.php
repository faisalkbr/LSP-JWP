<?php

/**
 * OrderController
 *
 * Menangani proses Checkout dan Order dari sisi pembeli: menampilkan ringkasan
 * belanja, membuat pesanan, menampilkan riwayat pesanan, dan menerima unggahan
 * bukti pembayaran.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Controllers;

use App\Http\Requests\BuktiBayarRequest;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Produk;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OrderController extends Controller
{
    /** Kunci penyimpanan keranjang pada session. */
    private const SESSION_KERANJANG = 'keranjang';

    /**
     * Halaman checkout: ringkasan belanja dan formulir alamat pengiriman.
     */
    public function create(): View|RedirectResponse
    {
        $item = Produk::dariKeranjang(session(self::SESSION_KERANJANG, []));

        if ($item->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        return view('checkout', [
            'item' => $item,
            'total' => $item->sum('subtotal'),
        ]);
    }

    /**
     * Menyimpan pesanan beserta rinciannya.
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        $item = Produk::dariKeranjang(session(self::SESSION_KERANJANG, []));

        if ($item->isEmpty()) {
            return redirect()->route('keranjang.index')
                ->with('error', 'Keranjang masih kosong.');
        }

        $alamat = $request->validated()['alamat_pengiriman'];

        // Header dan seluruh rinciannya ditulis dalam satu transaksi supaya tidak
        // pernah tersimpan order tanpa baris rincian bila terjadi kegagalan di tengah
        DB::transaction(function () use ($item, $alamat) {
            $order = Order::create([
                'pembeli_id' => auth()->id(),
                'tgl_order' => now(),
                'total_harga' => $item->sum('subtotal'),
                'status_order' => 'menunggu_bayar',
                'alamat_pengiriman' => $alamat,
            ]);

            foreach ($item as $baris) {
                OrderDetail::create([
                    'order_id' => $order->id_order,
                    'product_id' => $baris['produk']->id_product,
                    // Harga direkam saat transaksi terjadi agar nilai order tidak
                    // ikut berubah ketika penjual memperbarui harga produknya
                    'harga_satuan' => $baris['produk']->harga,
                    'quantity' => $baris['quantity'],
                    'subtotal' => $baris['subtotal'],
                ]);
            }
        });

        session()->forget(self::SESSION_KERANJANG);

        return redirect()->route('order.index')
            ->with('success', 'Pesanan berhasil dibuat. Silakan unggah bukti pembayaran.');
    }

    /**
     * Riwayat pesanan milik pembeli yang sedang login.
     */
    public function index(): View
    {
        $order = Order::with('detail.produk')
            ->where('pembeli_id', auth()->id())
            ->latest('id_order')
            ->get();

        return view('pesanan.index', compact('order'));
    }

    /**
     * Menerima unggahan bukti pembayaran lalu memajukan status pesanan.
     */
    public function uploadBukti(BuktiBayarRequest $request, int $id): RedirectResponse
    {
        $order = $this->cariOrderMilikPembeli($id);

        if ($order->status_order !== 'menunggu_bayar') {
            return redirect()->route('order.index')
                ->with('error', 'Bukti pembayaran hanya dapat diunggah pada pesanan yang menunggu pembayaran.');
        }

        $order->update([
            'bukti_bayar' => $request->file('bukti_bayar')->store('bukti', 'public'),
            'status_order' => 'menunggu_konfirmasi',
        ]);

        return redirect()->route('order.index')
            ->with('success', 'Bukti pembayaran terkirim. Pesanan menunggu konfirmasi penjual.');
    }

    /**
     * Mengambil satu pesanan dengan jaminan kepemilikan.
     * Pesanan milik pembeli lain menghasilkan 404.
     */
    private function cariOrderMilikPembeli(int $id): Order
    {
        return Order::where('id_order', $id)
            ->where('pembeli_id', auth()->id())
            ->firstOrFail();
    }
}
