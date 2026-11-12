@extends('layouts.app')

@section('title', 'Favorit Lapangan')

@section('content')

<div class="container py-5">

    <div class="fh-page-head mb-4">
        <div>
            <div class="text-uppercase small fw-bold mb-2">
                FUTSALHUB / FAVORIT
            </div>

            <h1 class="fw-bold mb-2">Lapangan Favorit</h1>

            <p class="text-secondary mb-0">
                Kumpulan lapangan yang kamu simpan untuk booking nanti.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Favorit</h4>

        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
            {{ $favorit->count() }} Lapangan
        </span>
    </div>

    <div class="row g-4">

        @forelse ($favorit as $item)

            @php
                $lapangan = $item->lapangan;
            @endphp

            <div class="col-md-6 col-lg-4">

                <div class="fh-field-card h-100">

                    <div class="fh-field-media">

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

                            <div class="fh-field-fallback">
                                ⚽
                            </div>

                        @endif

                        <span class="fh-field-chip">
                            Favorit ♥
                        </span>

                    </div>

                    <div class="fh-field-body">

                        <h5 class="fw-bold mb-2">
                            {{ $lapangan->nama_lapangan }}
                        </h5>

                        <div class="d-flex flex-wrap gap-2 mb-3">

                            @if ($lapangan->ukuran)
                                <span class="badge bg-light text-dark">
                                    {{ $lapangan->ukuran }}
                                </span>
                            @endif

                            @if ($lapangan->jenis_lantai)
                                <span class="badge bg-light text-dark">
                                    {{ $lapangan->jenis_lantai }}
                                </span>
                            @endif

                        </div>

                        <p class="text-secondary mb-3">
                            {{ $lapangan->deskripsi ?: 'Belum ada deskripsi lapangan.' }}
                        </p>

                        <div class="fh-price mb-3">
                            <strong>
                                Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                            </strong>
                            <span class="text-secondary">/ jam</span>
                        </div>

                        <div class="d-flex gap-2">

                            <a
                                href="{{ route('penyewa.lapangan.detail', $lapangan->id) }}"
                                class="fh-btn text-decoration-none text-center flex-grow-1"
                            >
                                Lihat Lapangan
                            </a>

                            <form
                                action="{{ route('penyewa.favorit.toggle', $lapangan->id) }}"
                                method="POST"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger"
                                    title="Hapus dari favorit"
                                    onclick="return confirm('Hapus lapangan ini dari favorit?')"
                                >
                                    ♥
                                </button>
                            </form>

                        </div>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">
                <div class="fh-panel text-center p-5">
                    <div class="fs-1 mb-3">♡</div>

                    <h5 class="fw-bold">Belum Ada Lapangan Favorit</h5>

                    <p class="text-secondary mb-3">
                        Kamu belum menyimpan lapangan apa pun.
                        Yuk, cari lapangan yang kamu suka!
                    </p>

                    <a
                        href="{{ route('penyewa.lapangan') }}"
                        class="fh-btn text-decoration-none"
                    >
                        Jelajahi Lapangan
                    </a>
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection
