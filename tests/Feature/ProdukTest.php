<?php

/**
 * ProdukTest
 *
 * Pengujian otomatis proses Manajemen Produk. Dijalankan dengan
 * `php artisan test` dan memakai basis data sementara (SQLite in-memory)
 * sehingga tidak menyentuh data pada basis data pengembangan.
 *
 * Unit kompetensi : J.620100.025.02 - Melakukan debugging
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace Tests\Feature;

use App\Models\Produk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProdukTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Skenario utama: penjual yang sudah login dapat menambah produk baru
     * dan produk tersebut tersimpan atas namanya sendiri.
     */
    public function test_penjual_dapat_menambah_produk(): void
    {
        $penjual = User::create([
            'nama_user' => 'Toko Uji',
            'email' => 'uji@demo.test',
            'password' => Hash::make('password'),
            'role' => 'penjual',
            'tgl_daftar' => now(),
            'status_user' => 'aktif',
        ]);

        $response = $this->actingAs($penjual)->post(route('penjual.produk.store'), [
            'nama_product' => 'Kaos Polos Katun',
            'kategori' => 'Fashion Pria',
            'harga' => 85000,
            'stok' => 10,
            'status_product' => 'tersedia',
            'deskripsi' => 'Produk hasil pengujian otomatis.',
        ]);

        $response->assertRedirect(route('penjual.produk.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('produk', [
            'nama_product' => 'Kaos Polos Katun',
            'penjual_id' => $penjual->id_user,
            'stok' => 10,
        ]);

        // Memastikan produk benar-benar terikat pada penjual yang login,
        // bukan pada nilai penjual_id yang dikirim dari sisi klien
        $this->assertSame(1, Produk::where('penjual_id', $penjual->id_user)->count());
    }
}
