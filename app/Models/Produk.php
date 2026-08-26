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
     * Harga dalam format Rupiah untuk ditampilkan di view.
     */
    public function hargaRupiah(): string
    {
        return 'Rp '.number_format((float) $this->harga, 0, ',', '.');
    }
}
