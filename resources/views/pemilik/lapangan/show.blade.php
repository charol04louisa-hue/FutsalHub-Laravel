
@extends('layouts.app')

@section('title', 'Detail Lapangan')

@push('styles')
<style>
    .owner-detail .detail-card {
        background: #fff;
        border: 1px solid #e8eee9;
        border-radius: 18px;
        overflow: hidden;
    }

    .owner-detail .detail-image {
        width: 100%;
        height: 320px;
        object-fit: cover;
        background: #f1f5f2;
    }

    .owner-detail .detail-label {
        color: #87938b;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .owner-detail .detail-value {
        color: #26382d;
        font-weight: 700;
        font-size: 14px;
    }

    .owner-detail .detail-item {
        padding: 15px;
        background: #f8faf8;
        border-radius: 12px;
        height: 100%;
    }

    .owner-detail .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 10px;
        background: #e8f7ee;
        color: #168653;
        text-decoration: none;
        font-weight: 700;
        font-size: 13px;
    }

    .owner-detail .back-button:hover {
        background: #d8f0e2;
        color: #116c42;
    }
</style>
@endpush

@section('content')
<div class="container py-4 owner-detail">

    <x-dashboard-header
        title="Detail Lapangan"
        subtitle="Informasi lengkap lapangan futsal milikmu."
    />

    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <a href="{{ route('pemilik.lapangan.index') }}" class="back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Daftar
        </a>

        <span class="badge rounded-pill px-3 py-2"
              style="background:#e8f7ee;color:#168653;">
            <i class="bi bi-eye me-1"></i>
            Mode Lihat
        </span>
    </div>

    <div class="detail-card shadow-sm">

        @if ($lapangan->gambar)
            <img
                src="{{ asset('assets/img/' . $lapangan->gambar) }}"
                alt="{{ $lapangan->nama_lapangan }}"
                class="detail-image"
            >
        @else
            <div class="detail-image d-flex align-items-center justify-content-center">
                <i class="bi bi-image text-secondary" style="font-size:60px;"></i>
            </div>
        @endif

        <div class="p-4 p-md-5">

            <div class="mb-4">
                <span class="text-success small fw-bold">
                    INFORMASI LAPANGAN
                </span>

                <h3 class="fw-bold mt-2 mb-1">
                    {{ $lapangan->nama_lapangan }}
                </h3>

                <p class="text-muted small mb-0">
                    ID Lapangan #{{ $lapangan->id }}
                </p>
            </div>

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Ukuran Lapangan</div>
                        <div class="detail-value">
                            {{ $lapangan->ukuran ?: '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Jenis Lantai</div>
                        <div class="detail-value">
                            {{ $lapangan->jenis_lantai ?: '-' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Harga Sewa per Jam</div>
                        <div class="detail-value text-success">
                            Rp{{ number_format((float) $lapangan->harga_per_jam, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="detail-item">
                        <div class="detail-label">Pemilik</div>
                        <div class="detail-value">
                            {{ auth()->user()->nama_lengkap }}
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-4">
                <h6 class="fw-bold mb-2">Deskripsi Lapangan</h6>

                <p class="text-muted mb-0" style="line-height:1.8;">
                    {{ $lapangan->deskripsi ?: 'Belum ada deskripsi untuk lapangan ini.' }}
                </p>
            </div>

        </div>
    </div>

</div>
@endsection
