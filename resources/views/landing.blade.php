{{--
    Halaman 01 - Landing Page

    Hero singkat disusul grid katalog produk. Seluruh kartu produk dibangun
    dari data tabel `produk`, bukan data statis.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Beranda - MarketPlace Sederhana')

@section('content')
    <div class="card border rounded mb-4">
        <div class="card-body text-center py-5">
            <h1 class="fw-bold">Belanja Mudah di MarketPlace Sederhana</h1>
            <p class="text-muted mb-4">
                Temukan produk pilihan dari penjual terpercaya, atau buka toko Anda sendiri hari ini.
            </p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-2">Daftar Sekarang</a>
            @endguest
            <a href="#katalog" class="btn btn-light border btn-lg">Lihat Katalog</a>
        </div>
    </div>

    <h2 class="h4 mb-3" id="katalog">Katalog Produk</h2>

    @forelse ($produk->chunk(4) as $baris)
        <div class="row g-3 mb-3">
            @foreach ($baris as $item)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card border rounded h-100">
                        @if ($item->gambar_product)
                            <img src="{{ asset('storage/' . $item->gambar_product) }}"
                                 class="card-img-top" alt="{{ $item->nama_product }}"
                                 style="height: 180px; object-fit: cover;">
                        @else
                            {{-- Placeholder abu-abu agar tinggi kartu tetap sama saat gambar kosong --}}
                            <div class="bg-secondary-subtle d-flex align-items-center justify-content-center text-secondary"
                                 style="height: 180px;">
                                <span class="small">Tanpa Gambar</span>
                            </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h3 class="h6 card-title">{{ $item->nama_product }}</h3>
                            <p class="text-muted small mb-2">{{ $item->kategori ?? 'Umum' }}</p>
                            <p class="fw-bold mb-2">{{ $item->hargaRupiah() }}</p>
                            <p class="mb-3">
                                <x-badge-status :status="$item->status_product" />
                            </p>
                            <a href="{{ route('produk.show', $item->id_product) }}"
                               class="btn btn-primary btn-sm mt-auto">Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="alert alert-info">Belum ada produk pada katalog.</div>
    @endforelse
@endsection
