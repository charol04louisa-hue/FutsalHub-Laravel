
@extends('layouts.app')

@section('title', 'Dashboard Penyewa')

@section('content')

{{-- HEADER DASHBOARD --}}
<section class="fh-page-head">
    <div class="container">
        <span class="text-uppercase fw-bold small">
            FUTSALHUB / DASHBOARD
        </span>

        <h1>Halo, {{ $user->nama_lengkap }}! 👋</h1>

        <p>
            Selamat datang kembali. Siap menemukan lapangan
            dan bermain futsal hari ini?
        </p>
    </div>
</section>

<div class="container py-5">

    {{-- STATISTIK --}}
    <div class="row g-3 mb-5">

        <div class="col-md-4">
            <div class="fh-stat-card h-100">
                <span>Booking Aktif</span>
                <strong>{{ $bookingAktif }}</strong>
                <small>Pesanan yang belum selesai</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="fh-stat-card h-100">
                <span>Jadwal Mendatang</span>
                <strong>{{ $jadwalMendatang->count() }}</strong>
                <small>Jadwal permainan berikutnya</small>
            </div>
        </div>

        <div class="col-md-4">
            <div class="fh-stat-card h-100">
                <span>Total Transaksi Lunas</span>
                <strong>
                    Rp{{ number_format((float) $totalTransaksi, 0, ',', '.') }}
                </strong>
                <small>Total pembayaran berhasil</small>
            </div>
        </div>

    </div>

    {{-- DAFTAR LAPANGAN --}}
    <section class="mb-5">

        <div class="d-flex flex-wrap justify-content-between
                    align-items-end gap-2 mb-4">

            <div>
                <span class="text-uppercase small fw-bold">
                    PILIH TEMPAT BERMAIN
                </span>

                <h2 class="fw-bold mt-2">
                    Lapangan Tersedia
                </h2>

                <p class="text-muted mb-0">
                    Temukan lapangan favoritmu.
                </p>
            </div>

            <span class="badge rounded-pill text-bg-light">
                {{ $daftarLapangan->count() }} Lapangan
            </span>

        </div>

        <div class="row g-4">

            @forelse ($daftarLapangan as $lapangan)

                <div class="col-md-6 col-lg-3">

                    <article class="fh-field-card">

                        {{-- FOTO LAPANGAN --}}
                        <div class="fh-field-media">

                            @if ($lapangan->gambar &&
                                file_exists(public_path('assets/img/' . $lapangan->gambar)))

                                <img
                                    src="{{ asset('assets/img/' . $lapangan->gambar) }}"
                                    alt="{{ $lapangan->nama_lapangan }}"
                                    loading="lazy"
                                >

                            @else

                                <div class="fh-field-fallback">
                                    <span style="font-size: 5rem;">⚽</span>
                                </div>

                            @endif

                            <span class="fh-field-chip">
                                Tersedia
                            </span>

                        </div>

                        {{-- INFORMASI LAPANGAN --}}
                        <div class="fh-field-body">

                            <h3>{{ $lapangan->nama_lapangan }}</h3>

                            <p class="text-muted small mb-2">
                                {{ $lapangan->ukuran ?? 'Ukuran belum tersedia' }}
                            </p>

                            <p class="text-muted small mb-3">
                                {{ $lapangan->jenis_lantai ?? 'Jenis lantai belum tersedia' }}
                            </p>

                            <div class="fh-price mt-auto">
                                <strong>
                                    Rp{{ number_format((float) $lapangan->harga_per_jam, 0, ',', '.') }}
                                </strong>

                                <span>/ jam</span>
                            </div>

                            {{-- TOMBOL PESAN SEKARANG --}}
                            <a
                                href="{{ route('penyewa.lapangan.detail', $lapangan->id) }}"
                                class="btn btn-success w-100 mt-3"
                                style="background-color: #12372A; border-color: #12372A;"
                            >
                                Pesan Sekarang
                                <span class="ms-1">→</span>
                            </a>

                        </div>

                    </article>

                </div>

            @empty

                <div class="col-12">
                    <div class="fh-panel fh-panel-body text-center">

                        <h5>Belum ada lapangan</h5>

                        <p class="text-muted mb-0">
                            Lapangan akan muncul di sini ketika tersedia.
                        </p>

                    </div>
                </div>

            @endforelse

        </div>

    </section>

    {{-- JADWAL BOOKING --}}
    <section>

        <div class="fh-panel">

            <div class="fh-table-heading">
                <h2>Jadwal Booking Mendatang</h2>
            </div>

            <div class="fh-panel-body">

                @forelse ($jadwalMendatang as $pesanan)

                    <div class="border-bottom py-3">

                        <div class="d-flex flex-wrap
                                    justify-content-between gap-2">

                            <div>

                                <h5 class="fw-bold">
                                    {{ $pesanan->lapangan?->nama_lapangan
                                        ?? 'Lapangan tidak tersedia' }}
                                </h5>

                                <p class="text-muted mb-1">
                                    {{ $pesanan->tanggal_sewa->format('d M Y') }}
                                    · {{ $pesanan->jam_mulai }}
                                    · {{ $pesanan->durasi_jam }} jam
                                </p>

                                <p class="mb-0">
                                    Rp{{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
                                </p>

                            </div>

                            <span class="badge text-bg-light align-self-start">
                                {{ str_replace('_', ' ', $pesanan->status_booking->value) }}
                            </span>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-4">

                        <div style="font-size: 2.5rem;">📅</div>

                        <h5 class="fw-bold mt-3">
                            Belum ada jadwal booking
                        </h5>

                        <p class="text-muted mb-0">
                            Yuk, temukan lapangan dan rencanakan
                            permainan berikutnya!
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </section>

</div>

@endsection
