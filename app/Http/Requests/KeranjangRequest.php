<?php

/**
 * KeranjangRequest
 *
 * Aturan validasi perubahan quantity pada halaman Keranjang.
 * Batas atas quantity diambil dari stok produk yang bersangkutan.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Requests;

use App\Models\Produk;
use Illuminate\Foundation\Http\FormRequest;

class KeranjangRequest extends FormRequest
{
    /**
     * Akses sudah dibatasi middleware `role:pembeli` pada definisi route.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $stok = Produk::where('id_product', $this->route('id'))->value('stok') ?? 0;

        return [
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$stok],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'quantity.required' => 'Jumlah pesanan wajib diisi.',
            'quantity.integer' => 'Jumlah pesanan harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah pesanan minimal 1.',
            'quantity.max' => 'Jumlah pesanan melebihi stok yang tersedia.',
        ];
    }
}
