{{--
    Halaman 05 - Halaman Keranjang

    Menampilkan isi keranjang yang tersimpan di session: daftar produk,
    pengubah quantity, tombol hapus item, dan total belanja.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Keranjang - MarketPlace Sederhana')

@section('content')
    <h1 class="h4 mb-3">Keranjang Belanja</h1>

    @if ($item->isEmpty())
        <div class="card border rounded">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Keranjang Anda masih kosong.</p>
                <a href="{{ route('landing') }}" class="btn btn-primary">Lihat Katalog</a>
            </div>
        </div>
    @else
        <div class="card border rounded mb-3">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 90px;">Gambar</th>
                            <th>Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-center" style="width: 190px;">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center" style="width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item as $baris)
                            @php($produk = $baris['produk'])
                            <tr>
                                <td>
                                    @if ($produk->gambar_product)
                                        <img src="{{ asset('storage/'.$produk->gambar_product) }}"
                                             alt="{{ $produk->nama_product }}" class="rounded"
                                             style="width: 64px; height: 64px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary-subtle rounded d-flex align-items-center justify-content-center text-secondary"
                                             style="width: 64px; height: 64px; font-size: .7rem;">
                                            N/A
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    {{ $produk->nama_product }}
                                    <span class="d-block text-muted small">Stok tersisa: {{ $produk->stok }}</span>
                                </td>
                                <td class="text-end">{{ $produk->hargaRupiah() }}</td>
                                <td class="text-center">
                                    <form action="{{ route('keranjang.update', $produk->id_product) }}"
                                          method="POST" class="d-flex justify-content-center gap-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="number" name="quantity" min="1" max="{{ $produk->stok }}"
                                               value="{{ $baris['quantity'] }}"
                                               class="form-control form-control-sm" style="width: 80px;">
                                        <button type="submit" class="btn btn-light border btn-sm">Ubah</button>
                                    </form>
                                </td>
                                <td class="text-end">Rp {{ number_format($baris['subtotal'], 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <form action="{{ route('keranjang.destroy', $produk->id_product) }}"
                                          method="POST"
                                          onsubmit="return confirm('Keluarkan {{ $produk->nama_product }} dari keranjang?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="4" class="text-end">Total</th>
                            <th class="text-end">Rp {{ number_format($total, 0, ',', '.') }}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @error('quantity')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="d-flex justify-content-between">
            <a href="{{ route('landing') }}" class="btn btn-light border">Lanjut Belanja</a>
            <a href="{{ route('checkout.create') }}" class="btn btn-primary">Checkout</a>
        </div>
    @endif
@endsection
