<?php

/**
 * Migration: order_detail
 *
 * Baris rincian pesanan. Tabel ini tidak ada pada daftar tabel di naskah soal,
 * tetapi wajib ada karena satu order dapat memuat lebih dari satu produk
 * sehingga quantity per produk tidak mungkin disimpan di tabel `orders`.
 *
 * Unit kompetensi : J.620100.004.02 - Menggunakan struktur data
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_detail', function (Blueprint $table) {
            $table->increments('id_order_detail');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();

            $table->index('order_id', 'idx_detail_order');
            $table->index('product_id', 'idx_detail_produk');

            // CASCADE: rincian tidak punya arti tanpa order induknya
            $table->foreign('order_id', 'fk_detail_order')
                ->references('id_order')->on('orders')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->foreign('product_id', 'fk_detail_produk')
                ->references('id_product')->on('produk')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_detail');
    }
};
