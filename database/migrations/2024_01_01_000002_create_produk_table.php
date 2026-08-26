<?php

/**
 * Migration: produk
 *
 * Katalog produk marketplace. Relasi one-to-many terhadap `users`:
 * satu penjual dapat memiliki banyak produk.
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
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_product');
            $table->unsignedInteger('penjual_id');
            $table->string('nama_product', 150);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 12, 2)->default(0);
            $table->integer('stok')->default(0);
            $table->string('kategori', 50)->nullable();
            $table->string('gambar_product', 255)->nullable();
            $table->enum('status_product', ['tersedia', 'habis'])->default('tersedia');
            $table->timestamps();

            $table->index('penjual_id', 'idx_produk_penjual');

            // RESTRICT dipilih agar akun penjual tidak bisa dihapus selama produknya masih ada
            $table->foreign('penjual_id', 'fk_produk_penjual')
                ->references('id_user')->on('users')
                ->onDelete('restrict')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
