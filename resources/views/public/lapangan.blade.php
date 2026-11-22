
@extends('layouts.app')

@section('title', 'Daftar Lapangan')

@section('content')

<div class="container py-5">

    <x-dashboard-header
        class="fh-dashboard-hero--penyewa"
        eyebrow="FUTSALHUB / LAPANGAN"
        title="Temukan Lapangan Favoritmu"
        description="Lihat pilihan lapangan futsal dan temukan tempat bermain yang sesuai dengan kebutuhanmu."
    />

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <h2 class="fw-bold mb-0">Daftar Lapangan</h2>

        <span class="badge rounded-pill bg-light text-dark px-3 py-2">
            {{ $lapangan->count() }} Lapangan
        </span>
    </div>

    <div class="row g-4">

        @forelse ($lapangan as $item)

            <div class="col-md-6 col-lg-4">

                <article class="fh-field-card h-100">

                    <div class="fh-field-media position-relative">

                        @if (
                            $item->gambar &&
                            file_exists(public_path('assets/img/' . $item->gambar))
                        )
                            <img
                                src="{{ asset('assets/img/' . $item->gambar) }}"
                                alt="{{ $item->nama_lapangan }}"
                                class="w-100 h-100"
                                style="object-fit: cover;"
                                loading="lazy"
                            >
                        @else
                            <div class="fh-field-fallback">
                                ⚽
                            </div>
                        @endif

                        <span class="fh-field-chip">
                            Lapangan
                        </span>

                    </div>

                    <div class="fh-field-body">

                        <h3>{{ $item->nama_lapangan }}</h3>

                        <div class="d-flex flex-wrap gap-2 mb-3">

                            @if ($item->ukuran)
                                <span class="badge bg-light text-dark">
                                    {{ $item->ukuran }}
                                </span>
                            @endif

                            @if ($item->jenis_lantai)
                                <span class="badge bg-light text-dark">
                                    {{ $item->jenis_lantai }}
                                </span>
                            @endif

                        </div>

                        <p class="text-secondary mb-3">
                            {{ $item->deskripsi ?: 'Belum ada deskripsi lapangan.' }}
                        </p>

                        <div class="fh-price mb-3">
                            <strong>
                                Rp{{ number_format((float) $item->harga_per_jam, 0, ',', '.') }}
                            </strong>
                            <span>/ jam</span>
                        </div>

                        <a
                            href="{{ route('login') }}"
                            class="fh-btn fh-btn-primary fh-btn-block text-decoration-none mt-auto"
                        >
                            Pesan Lapangan
                            <span aria-hidden="true">→</span>
                        </a>

                    </div>

                </article>

            </div>

        @empty

            <div class="col-12">
                <div class="fh-panel text-center p-5">
                    <div class="fs-1 mb-3">⚽</div>

                    <h3 class="fw-bold">Belum Ada Lapangan</h3>

                    <p class="text-secondary mb-0">
                        Saat ini belum ada lapangan yang tersedia.
                    </p>
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection
