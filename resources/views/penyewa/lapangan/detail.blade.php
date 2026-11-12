
@extends('layouts.app')

@section('title', 'Detail Lapangan')

@section('content')

<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('penyewa.lapangan') }}"
           class="text-decoration-none text-secondary">
            ← Kembali ke Daftar Lapangan
        </a>
    </div>

    <div class="fh-page-head mb-4">
        <div>
            <div class="text-uppercase small fw-bold mb-2">
                FUTSALHUB / DETAIL LAPANGAN
            </div>

            <h1 class="fw-bold mb-2">
                {{ $lapangan->nama_lapangan }}
            </h1>

            <p class="text-secondary mb-0">
                Informasi lengkap lapangan sebelum kamu melakukan pemesanan.
            </p>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">

            <div class="fh-field-card overflow-hidden">

                <div style="height: 380px; background: #e9eee8;">

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

                </div>

                <div class="fh-field-body">

                    <h4 class="fw-bold mb-3">Deskripsi Lapangan</h4>

                    <p class="text-secondary mb-0">
                        {{ $lapangan->deskripsi ?: 'Belum ada deskripsi lapangan.' }}
                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-5">

            <div class="fh-panel p-4 mb-4">

                <h5 class="fw-bold mb-4">Informasi Lapangan</h5>

                <div class="mb-3">
                    <small class="text-secondary">Nama Lapangan</small>
                    <div class="fw-semibold">
                        {{ $lapangan->nama_lapangan }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-secondary">Ukuran Lapangan</small>
                    <div class="fw-semibold">
                        {{ $lapangan->ukuran ?: 'Belum tersedia' }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-secondary">Jenis Lantai</small>
                    <div class="fw-semibold">
                        {{ $lapangan->jenis_lantai ?: 'Belum tersedia' }}
                    </div>
                </div>

                <hr>

                <div class="mb-4">
                    <small class="text-secondary">Harga Sewa</small>

                    <div class="fh-price mt-1">
                        <strong>
                            Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                        </strong>
                        <span class="text-secondary">/ jam</span>
                    </div>
                </div>

                <a
    href="{{ route('penyewa.lapangan.jadwal', $lapangan->id) }}"
    class="fh-btn text-decoration-none text-center"
>
    Lanjut Pilih Jadwal
</a>

                <small class="text-secondary d-block text-center mt-3">
                    Fitur pemesanan akan segera diaktifkan.
                </small>

            </div>

        </div>

    </div>

</div>
    {{-- Rating dan Ulasan Lapangan --}}
    <div class="fh-panel p-4 mt-4">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div>
                <h4 class="fw-bold mb-1">Rating dan Ulasan</h4>
                <p class="text-secondary mb-0">
                    Pengalaman penyewa yang pernah bermain di sini.
                </p>
            </div>

            <div class="text-end">
                <div class="text-warning fs-3 fw-bold">
                    ★ {{ number_format($rataRataRating, 1, ',', '.') }}/5
                </div>
                <small class="text-secondary">
                    {{ $jumlahUlasan }} ulasan
                </small>
            </div>
        </div>

        <hr>

        @forelse ($ulasan as $item)
            <div class="py-3">
                <div class="d-flex justify-content-between flex-wrap gap-2">
                    <strong>
                        {{ $item->penyewa->name ?? 'Penyewa' }}
                    </strong>

                    <small class="text-secondary">
                        {{ $item->created_at?->format('d/m/Y H:i') }}
                    </small>
                </div>

                <div class="text-warning my-2">
                    @for ($i = 1; $i <= 5; $i++)
                        {{ $i <= $item->rating ? '★' : '☆' }}
                    @endfor
                </div>

                <p class="text-secondary mb-0">
                    {{ $item->ulasan ?: 'Tidak ada komentar tertulis.' }}
                </p>
            </div>

            @if (!$loop->last)
                <hr>
            @endif
        @empty
            <div class="text-center py-4">
                <div class="fs-1 mb-2">☆</div>
                <h6 class="fw-bold">Belum Ada Ulasan</h6>
                <p class="text-secondary mb-0">
                    Jadilah penyewa pertama yang memberikan rating dan ulasan.
                </p>
            </div>
        @endforelse

    </div>
@endsection
