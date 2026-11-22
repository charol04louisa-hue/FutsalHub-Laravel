
@extends('layouts.app')

@section('title', 'Jadwal Lapangan Hari Ini')

@section('content')

<div class="container py-5">

    <x-dashboard-header
        class="fh-dashboard-hero--penyewa"
        eyebrow="FUTSALHUB / JADWAL"
        title="Jadwal Lapangan Hari Ini"
        description="Lihat jam bermain yang masih tersedia di setiap lapangan."
    />

    <div class="fh-panel p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="fw-bold mb-1">Ketersediaan Hari Ini</h5>
                <p class="text-secondary mb-0">
                    {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                </p>
            </div>

            <span class="badge rounded-pill bg-light text-dark px-3 py-2">
                {{ $lapangan->count() }} Lapangan
            </span>
        </div>
    </div>

    @forelse ($lapangan as $item)

        <div class="fh-panel p-4 mb-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h4 class="fw-bold mb-1">
                        {{ $item->nama_lapangan }}
                    </h4>

                    <div class="d-flex flex-wrap gap-2">
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
                </div>

                <div class="fh-price">
                    <strong>
                        Rp{{ number_format((float) $item->harga_per_jam, 0, ',', '.') }}
                    </strong>
                    <span>/ jam</span>
                </div>
            </div>

            <p class="text-secondary mb-3">
                {{ $item->deskripsi ?: 'Belum ada deskripsi lapangan.' }}
            </p>

            <div class="d-flex flex-wrap gap-2 mb-3">
                <span>
                    <span class="badge bg-success">&nbsp;</span>
                    Tersedia
                </span>
            </div>

            @if (count($jamTersedia[$item->id] ?? []) > 0)

                <div class="row g-2">

                    @foreach ($jamTersedia[$item->id] as $waktu)

                        <div class="col-4 col-md-3 col-lg-2">
                            <div class="btn btn-outline-success w-100 py-3">
                                <strong>{{ $waktu }}</strong>
                                <br>
                                <small>Tersedia</small>
                            </div>
                        </div>

                    @endforeach

                </div>

            @else

                <div class="alert alert-light border mb-0">
                    <strong>Belum ada slot tersedia.</strong>
                    <p class="text-secondary mb-0 mt-1">
                        Semua jadwal hari ini sudah lewat atau telah dipesan.
                    </p>
                </div>

            @endif

            <a
                href="{{ route('login') }}"
                class="fh-btn fh-btn-primary text-decoration-none d-inline-block mt-3"
            >
                Pesan Lapangan
                <span aria-hidden="true">→</span>
            </a>

        </div>

    @empty

        <div class="fh-panel text-center p-5">
            <div class="fs-1 mb-3">⚽</div>

            <h3 class="fw-bold">Belum Ada Lapangan</h3>

            <p class="text-secondary mb-0">
                Saat ini belum ada lapangan yang tersedia.
            </p>
        </div>

    @endforelse

</div>

@endsection
