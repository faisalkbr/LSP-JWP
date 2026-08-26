<?php

/**
 * CheckoutRequest
 *
 * Aturan validasi pembuatan pesanan pada halaman Checkout.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'alamat_pengiriman' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'alamat_pengiriman.required' => 'Alamat pengiriman wajib diisi.',
            'alamat_pengiriman.min' => 'Alamat pengiriman terlalu singkat, tuliskan minimal 10 karakter.',
        ];
    }
}
