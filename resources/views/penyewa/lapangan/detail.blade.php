
@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="container py-5">

    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('penyewa.lapangan') }}"
           class="text-decoration-none text-secondary fw-semibold">
            ← Kembali ke Daftar Lapangan
        </a>
    </div>

    {{-- Header Halaman --}}
    <div class="fh-page-head mb-4">
        <div>
            <div class="text-uppercase small fw-bold mb-2 text-secondary">
                FUTSALHUB / DETAIL LAPANGAN
            </div>

            <h1 class="fw-bold mb-2">
                {{ $lapangan->nama_lapangan }}
            </h1>

            <p class="text-secondary mb-0">
                Kenali fasilitas dan informasi lapangan sebelum memilih jadwal bermain.
            </p>
        </div>
    </div>

    {{-- Konten Detail Lapangan --}}
    <div class="row g-4 align-items-start">

        {{-- Foto dan Deskripsi --}}
        <div class="col-lg-7">

            <div class="fh-field-card overflow-hidden h-100">

                <div class="position-relative"
                     style="height: 380px; background: #e9eee8;">

                    @if (
                        $lapangan->gambar &&
                        file_exists(public_path('assets/img/' . $lapangan->gambar))
                    )

                        <img
                            src="{{ asset('assets/img/' . $lapangan->gambar) }}"
                            alt="{{ $lapangan->nama_lapangan }}"
                            class="w-100 h-100"
                            style="object-fit: cover;"
                        >

                    @else

                        <div class="fh-field-fallback h-100 d-flex align-items-center justify-content-center fs-1">
                            ⚽
                        </div>

                    @endif

                    <div class="position-absolute bottom-0 start-0 m-3">
                        <span class="badge bg-dark bg-opacity-75 px-3 py-2">
                            DETAIL LAPANGAN
                        </span>
                    </div>

                </div>

                <div class="fh-field-body p-4">

                    <h4 class="fw-bold mb-3">
                        Tentang Lapangan
                    </h4>

                    <p class="text-secondary mb-0"
                       style="line-height: 1.8;">
                        {{ $lapangan->deskripsi ?: 'Belum ada deskripsi lapangan.' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Informasi Lapangan --}}
        <div class="col-lg-5">

            <div class="fh-panel p-4">

                <div class="mb-4">
                    <div class="text-uppercase small fw-bold text-secondary mb-2">
                        INFORMASI
                    </div>

                    <h4 class="fw-bold mb-0">
                        Informasi Lapangan
                    </h4>
                </div>

                {{-- Nama Lapangan --}}
                <div class="mb-3">
                    <small class="text-secondary">
                        Nama Lapangan
                    </small>

                    <div class="fw-semibold mt-1">
                        {{ $lapangan->nama_lapangan }}
                    </div>
                </div>

                <hr class="my-3">

                {{-- Ukuran --}}
                <div class="mb-3">
                    <small class="text-secondary">
                        Ukuran Lapangan
                    </small>

                    <div class="fw-semibold mt-1">
                        {{ $lapangan->ukuran ?: 'Belum tersedia' }}
                    </div>
                </div>

                <hr class="my-3">

                {{-- Jenis Lantai --}}
                <div class="mb-3">
                    <small class="text-secondary">
                        Jenis Lantai
                    </small>

                    <div class="fw-semibold mt-1">
                        {{ $lapangan->jenis_lantai ?: 'Belum tersedia' }}
                    </div>
                </div>

                <hr class="my-4">

                {{-- Harga --}}
                <div class="p-3 rounded-3 mb-4"
                     style="background: #f3f6f3;">

                    <small class="text-secondary fw-semibold">
                        HARGA SEWA
                    </small>

                    <div class="mt-1 d-flex align-items-baseline flex-wrap gap-1">
                        <strong class="fs-3 fw-bold">
                            Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                        </strong>

                        <span class="text-secondary">
                            / jam
                        </span>
                    </div>

                    <small class="text-secondary">
                        Harga sewa lapangan per jam.
                    </small>

                </div>

                {{-- Tombol Pilih Jadwal --}}
                <div class="d-flex justify-content-center">
                    <a
                        href="{{ route('penyewa.lapangan.jadwal', $lapangan->id) }}"
                        class="fh-btn text-decoration-none text-center w-100"
                    >
                        Lanjut Pilih Jadwal →
                    </a>
                </div>

            </div>

        </div>

    </div>

    {{-- Rating dan Ulasan --}}
    <div class="fh-panel p-4 mt-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

            <div>
                <div class="text-uppercase small fw-bold text-secondary mb-2">
                    REVIEW PENYEWA
                </div>

                <h4 class="fw-bold mb-1">
                    Rating dan Ulasan
                </h4>

                <p class="text-secondary mb-0">
                    Pengalaman penyewa yang pernah bermain di sini.
                </p>
            </div>

            {{-- Ringkasan Rating --}}
            <div class="text-center rounded-3 px-4 py-3"
                 style="background: #fff8e8;">

                <div class="text-warning fs-3 fw-bold">
                    ★ {{ number_format($rataRataRating, 1, ',', '.') }}/5
                </div>

                <small class="text-secondary">
                    {{ $jumlahUlasan }} ulasan
                </small>

            </div>

        </div>

        <hr>

        {{-- Daftar Ulasan --}}
        @forelse ($ulasan as $item)

            <div class="py-3">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                    <strong>
                        {{ $item->penyewa->name ?? 'Penyewa' }}
                    </strong>

                    <small class="text-secondary">
                        {{ $item->created_at?->format('d/m/Y H:i') }}
                    </small>

                </div>

                {{-- Bintang Rating --}}
                <div class="text-warning my-2 fs-5">
                    @for ($i = 1; $i <= 5; $i++)
                        {{ $i <= $item->rating ? '★' : '☆' }}
                    @endfor
                </div>

                {{-- Komentar --}}
                <p class="text-secondary mb-0"
                   style="line-height: 1.7;">
                    {{ $item->ulasan ?: 'Tidak ada komentar tertulis.' }}
                </p>

            </div>

            @if (!$loop->last)
                <hr>
            @endif

        @empty

            <div class="text-center py-5">

                <div class="fs-1 text-secondary mb-2">
                    ☆
                </div>

                <h6 class="fw-bold">
                    Belum Ada Ulasan
                </h6>

                <p class="text-secondary mb-0">
                    Jadilah penyewa pertama yang memberikan rating dan ulasan.
                </p>

            </div>

        @endforelse

    </div>

</div>

@endsection
