<?php

/**
 * EnsureRole
 *
 * Middleware pembatas akses berdasarkan kolom `role` pada tabel users.
 * Dipakai untuk menutup seluruh route area penjual dari akun pembeli.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * @param  string  $role  Peran yang diizinkan, dikirim dari definisi route (mis. `role:penjual`)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan masuk terlebih dahulu.');
        }

        // Peran yang tidak sesuai dikembalikan ke landing page, bukan diberi halaman 403,
        // supaya alur demo tidak terputus di layar error
        if (! Auth::user()->hasRole($role)) {
            return redirect()->route('landing')
                ->with('error', 'Halaman tersebut hanya dapat diakses oleh '.$role.'.');
        }

        return $next($request);
    }
}
