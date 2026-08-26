<?php

/**
 * RegisterRequest
 *
 * Aturan validasi proses Pendaftaran pengguna baru. Validasi dipisahkan dari
 * controller agar aturan dapat diuji dan diubah tanpa menyentuh alur proses.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Pendaftaran terbuka untuk umum sehingga tidak ada pembatasan otorisasi.
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
            'nama_user' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:pembeli,penjual'],
            'foto_profil' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_user.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Silakan pilih peran akun.',
            'role.in' => 'Peran akun hanya boleh pembeli atau penjual.',
            'foto_profil.image' => 'Foto profil harus berupa berkas gambar.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
        ];
    }
}
