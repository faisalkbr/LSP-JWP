{{--
    Layout Utama Aplikasi

    Kerangka HTML yang dipakai ulang seluruh halaman: pemuatan Bootstrap 5
    lewat CDN, navbar yang menyesuaikan status login, area flash message,
    dan footer.

    Unit kompetensi : J.620100.019.02 - Menggunakan library atau komponen pre-existing
    Studi kasus     : MarketPlace Sederhana - LSP Junior Web Programmer
--}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MarketPlace Sederhana')</title>

    {{-- Bootstrap 5 dimuat dari CDN sehingga proyek tidak memerlukan npm/Vite --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('landing') }}">MarketPlace Sederhana</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuUtama">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuUtama">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('landing') ? 'active' : '' }}"
                       href="{{ route('landing') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('landing') }}#katalog">Katalog Produk</a>
                </li>
                @auth
                    @if (auth()->user()->hasRole('penjual'))
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penjual.produk.*') ? 'active' : '' }}"
                               href="{{ route('penjual.produk.index') }}">Manajemen Produk</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                           href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="btn btn-primary btn-sm mt-lg-1" href="{{ route('register') }}">Daftar</a>
                    </li>
                @else
                    <li class="nav-item d-flex align-items-center me-2">
                        <span class="navbar-text text-white">
                            {{ auth()->user()->nama_user }}
                            <span class="badge rounded-pill bg-secondary text-uppercase ms-1">
                                {{ auth()->user()->role }}
                            </span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-light border btn-sm">Keluar</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4 flex-grow-1">
    {{-- Satu titik penampilan flash message untuk seluruh halaman --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</main>

<footer class="bg-dark text-white-50 py-4 mt-auto">
    <div class="container d-flex flex-wrap justify-content-between">
        <span>&copy; {{ date('Y') }} MarketPlace Sederhana</span>
        <span>Uji Kompetensi LSP Junior Web Programmer</span>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
