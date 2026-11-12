
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') — FutsalHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/futsalhub.css') }}">

    @stack('styles')
</head>

<body class="fh-body">

<nav class="navbar navbar-expand-lg fh-nav">
    <div class="container">

        <a class="fh-brand" href="{{ url('/') }}">
            <span class="fh-brand-mark">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7l4 3-1.5 4.5h-5L8 10z"/>
                    <path d="M12 3v4M21 10l-5 0M3 10h5M7 20l2.5-5.5M17 20l-2.5-5.5"/>
                </svg>
            </span>

            <span>Futsal<span class="fh-brand-accent">Hub</span></span>
        </a>

        <button class="navbar-toggler fh-nav-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Buka menu">

            <svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round">
                <path d="M4 7h16M4 12h16M4 17h10"/>
            </svg>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            @auth

                @php
                    $role = auth()->user()->getRawOriginal('role');

                    $jumlahNotifikasiBelumDibaca = $role === 'penyewa'
                        ? auth()->user()->notifikasi()
                            ->where('sudah_dibaca', false)
                            ->count()
                        : 0;
                @endphp

                <ul class="navbar-nav ms-auto align-items-lg-center">

                    {{-- MENU PENYEWA --}}
                    @if($role === 'penyewa')

                        {{-- DASHBOARD --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penyewa.dashboard') ? 'active' : '' }}"
                               href="{{ route('penyewa.dashboard') }}">
                                Dashboard
                            </a>
                        </li>

                        {{-- CARI LAPANGAN --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penyewa.lapangan') || request()->routeIs('penyewa.lapangan.*') ? 'active' : '' }}"
                               href="{{ route('penyewa.lapangan') }}">
                                Cari Lapangan
                            </a>
                        </li>

                        {{-- FAVORIT --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penyewa.favorit*') ? 'active' : '' }}"
                               href="{{ route('penyewa.favorit') }}">
                                Favorit

                            </a>
                        </li>

                        {{-- RIWAYAT TRANSAKSI --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penyewa.riwayat*') ? 'active' : '' }}"
                               href="{{ route('penyewa.riwayat') }}">
                                Riwayat Transaksi
                            </a>
                        </li>

                        {{-- NOTIFIKASI --}}
                        <li class="nav-item">
                            <a href="{{ route('penyewa.notifikasi') }}"
                                class="nav-link d-flex align-items-center gap-2 {{ request()->routeIs('penyewa.notifikasi*') ? 'active' : '' }}">

                                Notifikasi

                                @if($jumlahNotifikasiBelumDibaca > 0)
                                    <span class="badge rounded-pill bg-danger"
                                        style="font-size: 9px;">
                                        {{ $jumlahNotifikasiBelumDibaca > 99 ? '99+' : $jumlahNotifikasiBelumDibaca }}
                                    </span>
                                @endif
                            </a>
                        </li>

                        {{-- PROFIL --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('penyewa.profil*') ? 'active' : '' }}"
                               href="{{ route('penyewa.profil') }}">
                                Profil
                            </a>
                        </li>

                    @endif

                    {{-- INFORMASI AKUN --}}
                    <li class="nav-item ms-lg-3">
                        <span class="nav-link">
                            Halo, {{ auth()->user()->nama_lengkap ?? auth()->user()->username }}
                        </span>
                    </li>

                    {{-- KELUAR --}}
                    <li class="nav-item ms-lg-2">
                        <form action="{{ url('/logout') }}" method="POST">
                            @csrf

                            <button type="submit" class="fh-btn fh-btn-primary fh-btn-sm">
                                Keluar
                            </button>
                        </form>
                    </li>

                </ul>

            @else

                <div class="ms-auto">
                    <a href="{{ url('/login') }}" class="fh-btn fh-btn-primary fh-btn-sm">
                        Masuk
                    </a>
                </div>

            @endauth

        </div>
    </div>
</nav>

<main class="fh-main">
    @yield('content')
</main>

<footer class="py-4 border-top">
    <div class="container d-flex flex-wrap justify-content-between gap-2">

        <small class="fh-text-muted">
            &copy; {{ date('Y') }} FutsalHub. Semua hak dilindungi.
        </small>

        <small class="fh-text-muted">
            Main futsal, tanpa ribet.
        </small>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')

</body>
</html>
