{{--
    Halaman 03 - Halaman Login

    Pesan gagal login sengaja dibuat umum ("Email atau password salah")
    dan ditampilkan lewat flash message pada layout.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Masuk - MarketPlace Sederhana')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card border rounded">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1">Masuk</h1>
                    <p class="text-muted small mb-4">Gunakan email dan password akun Anda.</p>

                    <form action="{{ route('login.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>

                    <p class="text-center text-muted small mt-3 mb-0">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
