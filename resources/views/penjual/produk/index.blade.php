{{--
    Halaman 08 - Manajemen Produk (Penjual)

    Tabel produk milik penjual yang sedang login beserta aksi Edit dan Hapus.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Manajemen Produk - MarketPlace Sederhana')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Manajemen Produk</h1>
            <p class="text-muted small mb-0">Kelola produk toko {{ auth()->user()->nama_user }}.</p>
        </div>
        <a href="{{ route('penjual.produk.create') }}" class="btn btn-primary">Tambah Produk</a>
    </div>

    <div class="card border rounded">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">Gambar</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-end">Harga</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Status</th>
                        <th class="text-center" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($produk as $item)
                        <tr>
                            <td>
                                @if ($item->gambar_product)
                                    <img src="{{ asset('storage/' . $item->gambar_product) }}"
                                         alt="{{ $item->nama_product }}" class="rounded"
                                         style="width: 64px; height: 64px; object-fit: cover;">
                                @else
                                    {{-- Kotak abu-abu menjaga tinggi baris tetap rapi saat gambar kosong --}}
                                    <div class="bg-secondary-subtle rounded d-flex align-items-center justify-content-center text-secondary"
                                         style="width: 64px; height: 64px; font-size: .7rem;">
                                        N/A
                                    </div>
                                @endif
                            </td>
                            <td>{{ $item->nama_product }}</td>
                            <td>{{ $item->kategori ?? '-' }}</td>
                            <td class="text-end">{{ $item->hargaRupiah() }}</td>
                            <td class="text-center">{{ $item->stok }}</td>
                            <td class="text-center">
                                <x-badge-status :status="$item->status_product" />
                            </td>
                            <td class="text-center">
                                <a href="{{ route('penjual.produk.edit', $item->id_product) }}"
                                   class="btn btn-light border btn-sm">Edit</a>

                                <form action="{{ route('penjual.produk.destroy', $item->id_product) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus produk {{ $item->nama_product }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada produk. Klik <strong>Tambah Produk</strong> untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
