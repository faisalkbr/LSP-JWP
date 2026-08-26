{{--
    Komponen: badge-status

    Menerjemahkan nilai ENUM status (produk maupun order) menjadi badge
    Bootstrap berwarna. Dibuat sebagai komponen agar pemetaan warna
    hanya ditulis satu kali dan dipakai ulang di seluruh view.

    Pemakaian: <x-badge-status :status="$produk->status_product" />

    Unit kompetensi : J.620100.016.01 - Menulis kode dengan prinsip sesuai guidelines dan best practices
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
@props(['status'])

@php
    $petaWarna = [
        'tersedia'            => 'bg-success',
        'selesai'             => 'bg-success',
        'habis'               => 'bg-danger',
        'dibatalkan'          => 'bg-danger',
        'menunggu_bayar'      => 'bg-warning text-dark',
        'menunggu_konfirmasi' => 'bg-info text-dark',
        'diproses'            => 'bg-primary',
        'dikirim'             => 'bg-dark',
    ];

    $warna = $petaWarna[$status] ?? 'bg-secondary';
    $label = ucwords(str_replace('_', ' ', $status));
@endphp

<span {{ $attributes->merge(['class' => 'badge rounded-pill ' . $warna]) }}>{{ $label }}</span>
