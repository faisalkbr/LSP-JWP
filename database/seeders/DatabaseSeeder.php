<?php

/**
 * DatabaseSeeder
 *
 * Titik masuk seeding. Memanggil DemoSeeder yang berisi data contoh
 * akun dan produk marketplace.
 *
 * Unit kompetensi : J.620100.004.02 - Menggunakan struktur data
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DemoSeeder::class,
        ]);
    }
}
