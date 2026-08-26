<?php

/**
 * ProdukRequest
 *
 * Aturan validasi proses Manajemen Produk, dipakai bersama oleh aksi
 * simpan (store) dan ubah (update).
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdukRequest extends FormRequest
{
    /**
     * Akses sudah dibatasi middleware `role:penjual` pada definisi route.
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
        return [
            'nama_product' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'kategori' => ['nullable', 'string', 'max:50'],
            'gambar_product' => ['nullable', 'image', 'max:2048'],
            'status_product' => ['required', 'in:tersedia,habis'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_product.required' => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'harga.min' => 'Harga tidak boleh kurang dari 0.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa bilangan bulat.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'gambar_product.image' => 'Berkas yang diunggah harus berupa gambar.',
            'gambar_product.max' => 'Ukuran gambar maksimal 2 MB.',
            'status_product.required' => 'Status produk wajib dipilih.',
            'status_product.in' => 'Status produk hanya boleh tersedia atau habis.',
        ];
    }
}
