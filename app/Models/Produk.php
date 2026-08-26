<?php

/**
 * Produk
 *
 * Model katalog produk marketplace. Setiap produk selalu terikat pada satu
 * penjual melalui kolom `penjual_id`.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'id_product';

    protected $fillable = [
        'penjual_id',
        'nama_product',
        'deskripsi',
        'harga',
        'stok',
        'kategori',
        'gambar_product',
        'status_product',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
    ];

    /**
     * Penjual pemilik produk.
     */
    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id', 'id_user');
    }

    /**
     * Baris order_detail yang memuat produk ini.
     */
    public function orderDetail(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'id_product');
    }

    /**
     * Menyusun isi keranjang belanja menjadi daftar produk beserta
     * quantity dan subtotalnya.
     *
     * Harga selalu dibaca ulang dari basis data, bukan dari session, agar
     * keranjang yang lama ditinggal tetap memakai harga yang berlaku saat ini.
     *
     * @param  array<int, int>  $keranjang  pasangan id_product => quantity
     * @return Collection<int, array{produk: Produk, quantity: int, subtotal: float}>
     */
    public static function dariKeranjang(array $keranjang): Collection
    {
        if ($keranjang === []) {
            return collect();
        }

        return static::whereIn('id_product', array_keys($keranjang))
            ->get()
            ->map(fn (self $produk) => [
                'produk' => $produk,
                'quantity' => $keranjang[$produk->id_product],
                'subtotal' => (float) $produk->harga * $keranjang[$produk->id_product],
            ]);
    }

    /**
     * Harga dalam format Rupiah untuk ditampilkan di view.
     */
    public function hargaRupiah(): string
    {
        return 'Rp '.number_format((float) $this->harga, 0, ',', '.');
    }
}
