<?php

/**
 * AuthController
 *
 * Menangani dua proses sekaligus: Pendaftaran pengguna baru serta
 * Login dan Logout. Autentikasi dibangun manual di atas facade Auth
 * bawaan Laravel, tanpa starter kit tambahan.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Menampilkan formulir pendaftaran.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Menyimpan pengguna baru. Masukan sudah tervalidasi oleh RegisterRequest
     * sebelum method ini dijalankan.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $fotoProfil = null;
        if ($request->hasFile('foto_profil')) {
            $fotoProfil = $request->file('foto_profil')->store('profil', 'public');
        }

        User::create([
            'nama_user' => $data['nama_user'],
            'email' => $data['email'],
            // Password wajib melewati Hash::make(); nilai polos tidak pernah menyentuh basis data
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'tgl_daftar' => now(),
            'foto_profil' => $fotoProfil,
            'status_user' => 'aktif',
        ]);

        return redirect()->route('login')
            ->with('success', 'Pendaftaran berhasil. Silakan masuk menggunakan akun Anda.');
    }

    /**
     * Menampilkan formulir login.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Memeriksa kredensial lalu mengarahkan pengguna sesuai perannya.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $kredensial = $request->only('email', 'password');

        if (! Auth::attempt($kredensial)) {
            // Pesan sengaja dibuat umum agar tidak membocorkan email mana yang terdaftar
            return redirect()->route('login')
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        $user = Auth::user();

        // Akun nonaktif tetap ditolak walau kredensialnya benar
        if ($user->status_user !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();

            // Tujuan ditulis eksplisit karena back() kehilangan URL sebelumnya
            // begitu sesi di-invalidate
            return redirect()->route('login')
                ->with('error', 'Akun Anda tidak aktif. Hubungi pengelola marketplace.');
        }

        $request->session()->regenerate();

        return $this->redirectSesuaiRole($user->role)
            ->with('success', 'Selamat datang, '.$user->nama_user.'.');
    }

    /**
     * Mengakhiri sesi login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('success', 'Anda telah keluar.');
    }

    /**
     * Menentukan halaman tujuan setelah login berhasil.
     * Penjual langsung diarahkan ke area kerjanya, pembeli kembali ke katalog.
     */
    private function redirectSesuaiRole(string $role): RedirectResponse
    {
        return $role === 'penjual'
            ? redirect()->route('penjual.produk.index')
            : redirect()->route('landing');
    }
}
