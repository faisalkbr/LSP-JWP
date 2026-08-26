{{--
    Halaman Pesanan Saya (Pembeli)

    Riwayat pesanan beserta rinciannya. Pesanan yang masih menunggu pembayaran
    menampilkan formulir unggah bukti bayar.

    Unit kompetensi : J.620100.005.02 - Mengimplementasikan user interface
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@extends('layouts.app')

@section('title', 'Pesanan Saya - MarketPlace Sederhana')

@section('content')
    <h1 class="h4 mb-3">Pesanan Saya</h1>

    @error('bukti_bayar')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @forelse ($order as $pesanan)
        <div class="card border rounded mb-3">
            <div class="card-header bg-white d-flex flex-wrap justify-content-between align-items-center">
                <div>
                    <strong>Pesanan #{{ $pesanan->id_order }}</strong>
                    <span class="text-muted small ms-2">{{ $pesanan->tgl_order->format('d/m/Y H:i') }}</span>
                </div>
                <x-badge-status :status="$pesanan->status_order" />
            </div>

            <div class="card-body">
                <div class="table-responsive mb-3">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesanan->detail as $rincian)
                                <tr>
                                    <td>{{ $rincian->produk->nama_product }}</td>
                                    <td class="text-end">Rp {{ number_format($rincian->harga_satuan, 0, ',', '.') }}</td>
                                    <td class="text-center">{{ $rincian->quantity }}</td>
                                    <td class="text-end">Rp {{ number_format($rincian->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-end">{{ $pesanan->totalRupiah() }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <p class="mb-3">
                    <span class="text-muted small d-block">Alamat pengiriman</span>
                    {{ $pesanan->alamat_pengiriman }}
                </p>

                @if ($pesanan->status_order === 'menunggu_bayar')
                    <form action="{{ route('order.bukti', $pesanan->id_order) }}" method="POST"
                          enctype="multipart/form-data" class="row g-2 align-items-end">
                        @csrf
                        <div class="col-12 col-md-6">
                            <label for="bukti_bayar_{{ $pesanan->id_order }}" class="form-label">
                                Bukti Pembayaran <span class="text-muted">(gambar, maks. 2 MB)</span>
                            </label>
                            <input type="file" id="bukti_bayar_{{ $pesanan->id_order }}" name="bukti_bayar"
                                   accept="image/*" class="form-control">
                        </div>
                        <div class="col-12 col-md-auto">
                            <button type="submit" class="btn btn-success">Kirim Bukti Bayar</button>
                        </div>
                    </form>
                @elseif ($pesanan->bukti_bayar)
                    <div>
                        <span class="text-muted small d-block mb-1">Bukti pembayaran terkirim</span>
                        <img src="{{ asset('storage/'.$pesanan->bukti_bayar) }}" alt="Bukti pembayaran"
                             class="rounded border" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="card border rounded">
            <div class="card-body text-center py-5">
                <p class="text-muted mb-3">Belum ada pesanan.</p>
                <a href="{{ route('landing') }}" class="btn btn-primary">Mulai Belanja</a>
            </div>
        </div>
    @endforelse
@endsection
