<?php

/**
 * User
 *
 * Model pengguna sistem sekaligus model autentikasi Laravel.
 * Satu tabel `users` menampung dua peran: pembeli dan penjual.
 *
 * Unit kompetensi : J.620100.017.02 - Mengimplementasikan pemrograman terstruktur
 * Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    // Nama tabel dan primary key tidak mengikuti konvensi Laravel (id), jadi wajib dideklarasikan
    protected $table = 'users';

    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama_user',
        'email',
        'password',
        'role',
        'tgl_daftar',
        'foto_profil',
        'status_user',
    ];

    // Disembunyikan dari serialisasi array/JSON agar hash password tidak pernah ikut terkirim ke view
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'tgl_daftar' => 'datetime',
    ];

    /**
     * Produk yang dimiliki user ini ketika berperan sebagai penjual.
     */
    public function produk(): HasMany
    {
        return $this->hasMany(Produk::class, 'penjual_id', 'id_user');
    }

    /**
     * Order yang dibuat user ini ketika berperan sebagai pembeli.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'pembeli_id', 'id_user');
    }

    /**
     * Memeriksa peran user yang sedang login.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}
