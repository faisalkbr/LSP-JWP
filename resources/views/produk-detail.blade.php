{{--
    Halaman 04 - Detail Produk

    Menampilkan satu produk lengkap dengan deskripsi, stok, dan penjualnya.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', $produk->nama_product . ' - MarketPlace Sederhana')

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('landing') }}">Beranda</a></li>
            <li class="breadcrumb-item active">{{ $produk->nama_product }}</li>
        </ol>
    </nav>

    <div class="card border rounded">
        <div class="row g-0">
            <div class="col-md-5">
                @if ($produk->gambar_product)
                    <img src="{{ asset('storage/' . $produk->gambar_product) }}"
                         class="img-fluid rounded-start w-100" alt="{{ $produk->nama_product }}"
                         style="height: 360px; object-fit: cover;">
                @else
                    <div class="bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary h-100"
                         style="min-height: 360px;">
                        <span>Tanpa Gambar</span>
                    </div>
                @endif
            </div>

            <div class="col-md-7">
                <div class="card-body">
                    <h1 class="h3">{{ $produk->nama_product }}</h1>
                    <p class="text-muted mb-2">
                        Kategori: {{ $produk->kategori ?? 'Umum' }} &middot;
                        Penjual: {{ $produk->penjual->nama_user }}
                    </p>

                    <p class="h4 fw-bold text-primary">{{ $produk->hargaRupiah() }}</p>

                    <p class="mb-3">
                        <x-badge-status :status="$produk->status_product" />
                        <span class="ms-2 text-muted small">Stok: {{ $produk->stok }}</span>
                    </p>

                    <h2 class="h6">Deskripsi</h2>
                    <p class="text-muted">{{ $produk->deskripsi ?? 'Penjual belum menuliskan deskripsi produk ini.' }}</p>

                    {{-- Tombol beli disembunyikan dari penjual karena keranjang hanya untuk pembeli --}}
                    @if (! auth()->check() || auth()->user()->hasRole('pembeli'))
                        @if ($produk->status_product === 'tersedia' && $produk->stok > 0)
                            <form action="{{ route('keranjang.store', $produk->id_product) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">Tambah ke Keranjang</button>
                            </form>
                        @else
                            <button type="button" class="btn btn-primary" disabled>Stok Habis</button>
                        @endif
                    @endif

                    <a href="{{ route('landing') }}" class="btn btn-light border">Kembali ke Katalog</a>
                </div>
            </div>
        </div>
    </div>
@endsection
