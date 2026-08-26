<?php

/**
 * Migration: orders
 *
 * Header pesanan pembeli. Nama tabel dijamakkan menjadi `orders` karena
 * ORDER adalah reserved word MySQL.
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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id_order');
            $table->unsignedInteger('pembeli_id');
            $table->dateTime('tgl_order');
            $table->decimal('total_harga', 14, 2)->default(0);
            $table->enum('status_order', [
                'menunggu_bayar',
                'menunggu_konfirmasi',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan',
            ])->default('menunggu_bayar');
            $table->text('alamat_pengiriman');
            $table->string('bukti_bayar', 255)->nullable();
            $table->timestamps();

            $table->index('pembeli_id', 'idx_orders_pembeli');

            $table->foreign('pembeli_id', 'fk_orders_pembeli')
                ->references('id_user')->on('users')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
