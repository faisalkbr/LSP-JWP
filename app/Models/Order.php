<?php

/**
 * Order
 *
 * Header pesanan pembeli. Total harga disimpan di tabel ini agar nilai
 * transaksi tidak berubah ketika harga produk diperbarui penjual di kemudian hari.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $primaryKey = 'id_order';

    protected $fillable = [
        'pembeli_id',
        'tgl_order',
        'total_harga',
        'status_order',
        'alamat_pengiriman',
        'bukti_bayar',
    ];

    protected $casts = [
        'tgl_order' => 'datetime',
        'total_harga' => 'decimal:2',
    ];

    /**
     * Pembeli yang membuat order.
     */
    public function pembeli(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pembeli_id', 'id_user');
    }

    /**
     * Rincian produk pada order ini.
     */
    public function detail(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id_order');
    }

    /**
     * Total harga dalam format Rupiah untuk ditampilkan di view.
     */
    public function totalRupiah(): string
    {
        return 'Rp '.number_format((float) $this->total_harga, 0, ',', '.');
    }
}
