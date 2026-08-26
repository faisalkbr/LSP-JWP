{{--
    Halaman 02 - Formulir Pendaftaran

    Validasi dijalankan RegisterRequest; pesan kesalahan ditampilkan di bawah
    setiap field dan nilai lama dikembalikan lewat old().

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Daftar Akun - MarketPlace Sederhana')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card border rounded">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Daftar Akun Baru</h1>
                    <p class="text-muted small mb-4">Lengkapi data berikut untuk mulai berbelanja atau berjualan.</p>

                    <form action="{{ route('register.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="nama_user" class="form-label">Nama Lengkap</label>
                            <input type="text" id="nama_user" name="nama_user"
                                   class="form-control @error('nama_user') is-invalid @enderror"
                                   value="{{ old('nama_user') }}">
                            @error('nama_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                   class="form-control">
                        </div>

                        <div class="mb-3">
                            <span class="form-label d-block">Daftar Sebagai</span>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('role') is-invalid @enderror" type="radio"
                                       name="role" id="role_pembeli" value="pembeli"
                                       @checked(old('role', 'pembeli') === 'pembeli')>
                                <label class="form-check-label" for="role_pembeli">Pembeli</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input @error('role') is-invalid @enderror" type="radio"
                                       name="role" id="role_penjual" value="penjual"
                                       @checked(old('role') === 'penjual')>
                                <label class="form-check-label" for="role_penjual">Penjual</label>
                            </div>
                            @error('role')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="foto_profil" class="form-label">
                                Foto Profil <span class="text-muted">(opsional)</span>
                            </label>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*"
                                   class="form-control @error('foto_profil') is-invalid @enderror">
                            @error('foto_profil')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Daftar</button>
                    </form>

                    <p class="text-center text-muted small mt-3 mb-0">
                        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
