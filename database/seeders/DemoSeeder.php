<?php

/**
 * DemoSeeder
 *
 * Mengisi data contoh sesuai Langkah Kerja naskah soal: satu akun penjual
 * dengan lima produk, ditambah satu akun pembeli agar alur login kedua
 * peran dapat diperagakan.
 *
 * Unit kompetensi : J.620100.004.02 - Menggunakan struktur data
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $penjual = User::create([
            'nama_user' => 'Toko Sinar Jaya',
            'email' => 'penjual@demo.test',
            // Password contoh pun tetap melewati Hash::make(), tidak pernah disimpan polos
            'password' => Hash::make('password'),
            'role' => 'penjual',
            'tgl_daftar' => now(),
            'status_user' => 'aktif',
        ]);

        User::create([
            'nama_user' => 'Budi Santoso',
            'email' => 'pembeli@demo.test',
            'password' => Hash::make('password'),
            'role' => 'pembeli',
            'tgl_daftar' => now(),
            'status_user' => 'aktif',
        ]);

        $daftarProduk = [
            ['Kemeja Flanel Pria',     'Fashion Pria', 149000, 24, 'tersedia'],
            ['Sepatu Sneakers Casual', 'Sepatu',       320000,  8, 'tersedia'],
            ['Tas Ransel Laptop 15"',  'Tas',          210000,  0, 'habis'],
            ['Jam Tangan Analog',      'Aksesoris',    275000, 12, 'tersedia'],
            ['Topi Baseball Katun',    'Aksesoris',     75000, 30, 'tersedia'],
        ];

        foreach ($daftarProduk as [$nama, $kategori, $harga, $stok, $status]) {
            Produk::create([
                'penjual_id' => $penjual->id_user,
                'nama_product' => $nama,
                'deskripsi' => 'Produk contoh '.$nama.' untuk keperluan demonstrasi aplikasi.',
                'harga' => $harga,
                'stok' => $stok,
                'kategori' => $kategori,
                // Dibiarkan null; view sudah menyediakan placeholder untuk produk tanpa gambar
                'gambar_product' => null,
                'status_product' => $status,
            ]);
        }
    }
}
