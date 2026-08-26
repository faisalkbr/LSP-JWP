<?php

/**
 * OrderDetail
 *
 * Rincian satu baris produk di dalam sebuah order. Kolom `harga_satuan`
 * merekam harga pada saat transaksi terjadi, bukan mengambil dari tabel produk.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'order_detail';

    protected $primaryKey = 'id_order_detail';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Order induk dari rincian ini.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id_order');
    }

    /**
     * Produk yang dirujuk rincian ini.
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'product_id', 'id_product');
    }
}
