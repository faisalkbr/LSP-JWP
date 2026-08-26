<?php

/**
 * BuktiBayarRequest
 *
 * Aturan validasi unggahan bukti pembayaran pada halaman Pesanan Saya.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuktiBayarRequest extends FormRequest
{
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
            'bukti_bayar' => ['required', 'image', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'bukti_bayar.required' => 'Berkas bukti pembayaran wajib diunggah.',
            'bukti_bayar.image' => 'Bukti pembayaran harus berupa berkas gambar.',
            'bukti_bayar.max' => 'Ukuran bukti pembayaran maksimal 2 MB.',
        ];
    }
}
