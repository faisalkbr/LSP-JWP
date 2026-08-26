{{--
    Halaman 06 - Halaman Order (Checkout)

    Ringkasan belanja di sisi kanan dan formulir alamat pengiriman di sisi kiri.
    Menekan "Buat Pesanan" menyimpan satu baris orders beserta seluruh
    rinciannya dalam satu transaksi.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Checkout - MarketPlace Sederhana')

@section('content')
    <h1 class="h4 mb-3">Checkout</h1>

    <form action="{{ route('checkout.store') }}" method="POST" novalidate>
        @csrf

        <div class="row g-3">
            <div class="col-12 col-lg-7">
                <div class="card border rounded">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Alamat Pengiriman</h2>

                        <label for="alamat_pengiriman" class="form-label">Alamat lengkap</label>
                        <textarea id="alamat_pengiriman" name="alamat_pengiriman" rows="5"
                                  class="form-control @error('alamat_pengiriman') is-invalid @enderror"
                                  placeholder="Nama jalan, nomor rumah, kelurahan, kecamatan, kota, kode pos">{{ old('alamat_pengiriman') }}</textarea>
                        @error('alamat_pengiriman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-5">
                <div class="card border rounded">
                    <div class="card-body">
                        <h2 class="h6 mb-3">Ringkasan Pesanan</h2>

                        <ul class="list-group list-group-flush mb-3">
                            @foreach ($item as $baris)
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>
                                        {{ $baris['produk']->nama_product }}
                                        <span class="text-muted small d-block">
                                            {{ $baris['quantity'] }} x {{ $baris['produk']->hargaRupiah() }}
                                        </span>
                                    </span>
                                    <span class="text-nowrap">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="d-flex justify-content-between fw-bold border-top pt-3">
                            <span>Total</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>

                        <p class="text-muted small mt-3 mb-3">
                            Pesanan dibuat dengan status <x-badge-status status="menunggu_bayar" />.
                            Unggah bukti pembayaran pada halaman Pesanan Saya setelah pesanan tersimpan.
                        </p>

                        <button type="submit" class="btn btn-primary w-100">Buat Pesanan</button>
                        <a href="{{ route('keranjang.index') }}" class="btn btn-light border w-100 mt-2">
                            Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
