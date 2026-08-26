<?php

/**
 * Migration: users
 *
 * Membuat tabel pengguna sistem MarketPlace Sederhana. Satu tabel dipakai
 * bersama oleh dua peran (pembeli dan penjual) yang dibedakan kolom `role`,
 * sesuai PDM hasil Kelompok Pekerjaan 1.
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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user');
            $table->string('nama_user', 100);
            $table->string('email', 100)->unique('uq_users_email');
            $table->string('password', 255);
            $table->enum('role', ['pembeli', 'penjual']);
            $table->dateTime('tgl_daftar');
            $table->string('foto_profil', 255)->nullable();
            $table->enum('status_user', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
