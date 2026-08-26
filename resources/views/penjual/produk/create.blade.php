{{--
    Halaman Tambah Produk (Penjual)

    Formulir menggunakan aturan ProdukRequest yang sama dengan halaman edit.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Tambah Produk - MarketPlace Sederhana')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border rounded">
                <div class="card-body p-4">
                    <h1 class="h4 mb-4">Tambah Produk</h1>

                    <form action="{{ route('penjual.produk.store') }}" method="POST"
                          enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="nama_product" class="form-label">Nama Produk</label>
                            <input type="text" id="nama_product" name="nama_product"
                                   class="form-control @error('nama_product') is-invalid @enderror"
                                   value="{{ old('nama_product') }}">
                            @error('nama_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" id="kategori" name="kategori"
                                   class="form-control @error('kategori') is-invalid @enderror"
                                   value="{{ old('kategori') }}" placeholder="Contoh: Fashion Pria">
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="harga" class="form-label">Harga (Rp)</label>
                                <input type="number" id="harga" name="harga" min="0" step="1"
                                       class="form-control @error('harga') is-invalid @enderror"
                                       value="{{ old('harga') }}">
                                @error('harga')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input type="number" id="stok" name="stok" min="0" step="1"
                                       class="form-control @error('stok') is-invalid @enderror"
                                       value="{{ old('stok') }}">
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status_product" class="form-label">Status Produk</label>
                            <select id="status_product" name="status_product"
                                    class="form-select @error('status_product') is-invalid @enderror">
                                <option value="tersedia" @selected(old('status_product', 'tersedia') === 'tersedia')>Tersedia</option>
                                <option value="habis" @selected(old('status_product') === 'habis')>Habis</option>
                            </select>
                            @error('status_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea id="deskripsi" name="deskripsi" rows="4"
                                      class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="gambar_product" class="form-label">
                                Gambar Produk <span class="text-muted">(opsional, maks. 2 MB)</span>
                            </label>
                            <input type="file" id="gambar_product" name="gambar_product" accept="image/*"
                                   class="form-control @error('gambar_product') is-invalid @enderror">
                            @error('gambar_product')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                        <a href="{{ route('penjual.produk.index') }}" class="btn btn-light border">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
