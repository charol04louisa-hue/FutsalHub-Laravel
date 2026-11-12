<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FutsalHub — Main Futsal, Tanpa Ribet</title>
    <meta name="description" content="FutsalHub — temukan lapangan futsal, cek jadwal yang tersedia, dan pesan dengan mudah.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/futsalhub.css') }}">
</head>

<body class="fh-body">

<nav class="navbar navbar-expand-lg fh-nav">
    <div class="container">

        <a class="fh-brand" href="{{ url('/') }}" aria-label="FutsalHub beranda">
            <span class="fh-brand-mark" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 7l4 3-1.5 4.5h-5L8 10z"/>
                    <path d="M12 3v4M21 10l-5 0M3 10h5M7 20l2.5-5.5M17 20l-2.5-5.5"/>
                </svg>
            </span>
            <span>Futsal<span class="fh-brand-accent">Hub</span></span>
        </a>

        <button class="navbar-toggler fh-nav-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
            aria-controls="navbarNav"
            aria-expanded="false"
            aria-label="Buka menu">

            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M4 7h16M4 12h16M4 17h10"/>
            </svg>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="{{ url('/') }}">
                        Beranda
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Lapangan
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Jadwal
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#">
                        Riwayat <small class="fh-text-muted">(Admin)</small>
                    </a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="fh-btn fh-btn-outline fh-btn-sm fh-nav-cta" href="{{ url('/login') }}">
                        Masuk
                    </a>
                </li>

                <li class="nav-item ms-lg-2">
                    <a class="fh-btn fh-btn-primary fh-btn-sm fh-nav-cta" href="#">
                        Daftar
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>

<main class="fh-main">

    <!-- HERO SECTION -->
    <section class="fh-hero-wrap">
        <div class="fh-hero" style="background-image: url('https://images.unsplash.com/photo-1575361204480-aadea25e6e68?q=80&w=1200');">

            <div class="container fh-hero-inner">

                <span class="fh-hero-badge">
                    Premium Futsal Arena
                </span>

                <h1>
                    Main Futsal,
                    <em>Tanpa Ribet.</em>
                </h1>

                <p>
                    Temukan lapangan favoritmu, pilih jadwal yang tersedia,
                    dan pesan dengan lebih mudah melalui FutsalHub.
                </p>

                <div class="fh-hero-actions">

                    <a class="fh-btn fh-btn-lime fh-btn-lg" href="#">
                        Cari Lapangan

                        <svg width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor"
                            stroke-width="2.4" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>

                    <a class="fh-btn fh-btn-ghost-light fh-btn-lg" href="#">
                        Lihat Jadwal
                    </a>

                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="fh-section">
        <div class="container">

            <div class="row align-items-end g-4 mb-5">

                <div class="col-lg-7">
                    <span class="fh-eyebrow">
                        Kenapa FutsalHub
                    </span>

                    <h2 class="fh-title">
                        Mengapa harus booking di FutsalHub?
                    </h2>
                </div>

                <div class="col-lg-5">
                    <p class="fh-lead mb-0">
                        Kami menyediakan fasilitas terbaik untuk kenyamanan
                        pertandingan tim kamu.
                    </p>
                </div>

            </div>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="fh-feature">

                        <span class="fh-feature-num">01</span>

                        <div class="fh-feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="5" width="18" height="14" rx="2"/>
                                <path d="M12 5v14"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                        </div>

                        <h3>Rumput &amp; Vinil Premium</h3>

                        <p>
                            Pilihan lapangan rumput sintetis interlock dan
                            vinil standar internasional yang empuk dan anti-slip
                            demi mengurangi risiko cedera.
                        </p>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="fh-feature">

                        <span class="fh-feature-num">02</span>

                        <div class="fh-feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M13 2L4 14h7l-1 8 9-12h-7z"/>
                            </svg>
                        </div>

                        <h3>Konfirmasi Instan</h3>

                        <p>
                            Bayar dengan QRIS atau transfer bank, lalu unggah
                            bukti pembayaran langsung di form pemesanan.
                        </p>

                    </div>
                </div>

                <div class="col-md-4">
                    <div class="fh-feature">

                        <span class="fh-feature-num">03</span>

                        <div class="fh-feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 18h6M10 22h4"/>
                                <path d="M12 2a7 7 0 0 0-4 12.7V17h8v-2.3A7 7 0 0 0 12 2z"/>
                            </svg>
                        </div>

                        <h3>Pencahayaan LED Terang</h3>

                        <p>
                            Sistem pencahayaan stadium yang terang di malam
                            hari, membuat suasana main malam terasa seperti
                            liga profesional.
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <section class="pb-5 mb-5">
        <div class="container">

            <div class="fh-cta">

                <div>
                    <span class="fh-eyebrow fh-eyebrow-light mb-2">
                        Booking sekarang
                    </span>

                    <h2 class="mt-2">
                        Siap Main Hari Ini?
                    </h2>

                    <p>
                        Temukan lapangan yang sesuai dan rencanakan
                        pertandinganmu bersama FutsalHub.
                    </p>
                </div>

                <a href="#" class="fh-btn fh-btn-lime fh-btn-lg">
                    Pesan Lapangan
                </a>

            </div>
        </div>
    </section>

</main>

<!-- FOOTER -->
<footer class="fh-footer fh-footer-tight">
    <div class="container">

        <div class="row g-4">

            <div class="col-lg-5">

                <a class="fh-brand mb-3" href="{{ url('/') }}">
                    <span class="fh-brand-mark" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7l4 3-1.5 4.5h-5L8 10z"/>
                        </svg>
                    </span>
                    FutsalHub
                </a>

                <p class="mt-3 mb-0" style="max-width:360px">
                    Platform penyewaan lapangan futsal. Pilih lapangan,
                    cek jadwal, dan pesan dalam beberapa langkah.
                </p>

            </div>

            <div class="col-6 col-lg-3 offset-lg-1">

                <h6>Navigasi</h6>

                <ul>
                    <li><a href="{{ url('/') }}">Beranda</a></li>
                    <li><a href="#">Lapangan</a></li>
                    <li><a href="#">Jadwal</a></li>
                    <li><a href="#">Riwayat</a></li>
                </ul>

            </div>

            <div class="col-6 col-lg-3">

                <h6>Mulai</h6>

                <ul>
                    <li><a href="#">Pesan Lapangan</a></li>
                    <li><a href="#">Cek Slot Kosong</a></li>
                </ul>

            </div>

        </div>

        <div class="fh-footer-bottom">
            <span>&copy; {{ date('Y') }} FutsalHub. Semua hak dilindungi.</span>
            <span>Main futsal, tanpa ribet.</span>
        </div>

    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
