@extends('layouts.app')

@section('title', 'Lihat Lapangan')

@section('content')

<div class="container py-5">

    <div class="fh-page-head mb-4">
        <div>
            <div class="text-uppercase small fw-bold mb-2">
                FUTSALHUB / LAPANGAN
            </div>

            <h1 class="fw-bold mb-2">Pilih Lapangan Favoritmu</h1>

            <p class="text-secondary mb-0">
                Temukan lapangan futsal yang sesuai dengan kebutuhanmu.
            </p>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Daftar Lapangan</h4>

        <span class="badge bg-light text-dark rounded-pill px-3 py-2">
            {{ $lapangan->count() }} Lapangan
        </span>
    </div>

    <div class="row g-4">

        @forelse ($lapangan as $item)

            <div class="col-md-6 col-lg-4">

                <div class="fh-field-card h-100">

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
                            >

                        @else

                            <div class="fh-field-fallback">
                                ⚽
                            </div>

                        @endif

                        <span class="fh-field-chip">
                            Tersedia
                        </span>

                        {{-- Tombol Favorit --}}
                        <form
                            action="{{ route('penyewa.favorit.toggle', $item->id) }}"
                            method="POST"
                            class="position-absolute top-0 end-0 m-3"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px; font-size: 20px;"
                                title="{{ in_array($item->id, $favoritIds) ? 'Hapus dari favorit' : 'Tambah ke favorit' }}"
                            >
                                @if (in_array($item->id, $favoritIds))
                                    <span class="text-danger">♥</span>
                                @else
                                    <span class="text-secondary">♡</span>
                                @endif
                            </button>
                        </form>

                    </div>

                    <div class="fh-field-body">

                        <h5 class="fw-bold mb-2">
                            {{ $item->nama_lapangan }}
                        </h5>

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
                                Rp{{ number_format($item->harga_per_jam, 0, ',', '.') }}
                            </strong>

                            <span class="text-secondary">/ jam</span>
                        </div>

                        <a
                            href="{{ route('penyewa.lapangan.detail', $item->id) }}"
                            class="fh-btn text-decoration-none text-center"
                        >
                            Pilih Lapangan
                        </a>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-12">
                <div class="fh-panel text-center p-5">
                    <div class="fs-1 mb-3">⚽</div>

                    <h5 class="fw-bold">Belum Ada Lapangan</h5>

                    <p class="text-secondary mb-0">
                        Saat ini belum ada lapangan yang tersedia.
                    </p>
                </div>
            </div>

        @endforelse

    </div>

</div>

@endsection
