{{--
    Halaman Ubah Produk (Penjual)

    Nilai form diisi dari data lama; unggahan gambar bersifat opsional dan
    gambar sebelumnya dipertahankan bila tidak diganti.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Ubah Produk - MarketPlace Sederhana')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border rounded">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Ubah Produk</h1>

                    <form action="{{ route('penjual.produk.update', $produk->id_product) }}" method="POST"
                          enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_product" class="form-label">Nama Produk</label>
                            <input type="text" id="nama_product" name="nama_product"
                                   class="form-control @error('nama_product') is-invalid @enderror"
                                   value="{{ old('nama_product', $produk->nama_product) }}">
                            @error('nama_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" id="kategori" name="kategori"
                                   class="form-control @error('kategori') is-invalid @enderror"
                                   value="{{ old('kategori', $produk->kategori) }}">
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <input type="number" id="harga" name="harga" min="0" step="1"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga', (int) $produk->harga) }}">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" id="stok" name="stok" min="0" step="1"
                                       class="form-control @error('stok') is-invalid @enderror"
                                       value="{{ old('stok', $produk->stok) }}">
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status_product" class="form-label">Status Produk</label>
                            <select id="status_product" name="status_product"
                                    class="form-select @error('status_product') is-invalid @enderror">
                                <option value="tersedia" @selected(old('status_product', $produk->status_product) === 'tersedia')>Tersedia</option>
                                <option value="habis" @selected(old('status_product', $produk->status_product) === 'habis')>Habis</option>
                            </select>
                            @error('status_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $produk->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gambar_product" class="form-label">
                                Ganti Gambar <span class="text-muted">(kosongkan bila tidak diubah)</span>
                            </label>
                            <input type="file" id="gambar_product" name="gambar_product" accept="image/*"
                                   class="form-control @error('gambar_product') is-invalid @enderror">
                            @error('gambar_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if ($produk->gambar_product)
                                <div class="mt-2">
                                    <span class="d-block text-muted small mb-1">Gambar saat ini:</span>
                                    <img src="{{ asset('storage/' . $produk->gambar_product) }}"
                                         alt="{{ $produk->nama_product }}" class="rounded border"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('penjual.produk.index') }}" class="btn btn-light border">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
