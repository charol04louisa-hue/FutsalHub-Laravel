@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard Kasir</h2>
        <p class="text-muted mb-0">
            Selamat datang! Pantau dan kelola transaksi pembayaran lapangan.
        </p>
    </div>

    {{-- Ringkasan Transaksi --}}
    <div class="row g-3 mb-4">

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Menunggu Verifikasi</p>
                    <h3 class="fw-bold mb-0">
                        {{ $menungguVerifikasi }}
                    </h3>
                    <small class="text-warning">Perlu diperiksa</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Pembayaran Berhasil</p>
                    <h3 class="fw-bold mb-0">
                        {{ $pembayaranBerhasil }}
                    </h3>
                    <small class="text-success">Pembayaran lunas</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Pembayaran Ditolak</p>
                    <h3 class="fw-bold mb-0">
                        {{ $pembayaranDitolak }}
                    </h3>
                    <small class="text-danger">Perlu tindak lanjut</small>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Total Transaksi</p>
                    <h3 class="fw-bold mb-0">
                        {{ $totalTransaksi }}
                    </h3>
                    <small class="text-muted">Seluruh transaksi</small>
                </div>
            </div>
        </div>

    </div>

    {{-- Menu Utama --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-2">Kelola Transaksi</h5>

            <p class="text-muted mb-3">
                Periksa bukti pembayaran dan kelola status transaksi penyewa.
            </p>

            <div class="d-flex flex-wrap gap-2">

                <a
                    href="{{ route('kasir.verifikasi') }}"
                    class="btn btn-success"
                >
                    Verifikasi Pembayaran
                </a>

                <button
                    type="button"
                    class="btn btn-outline-success"
                    disabled
                >
                    Riwayat Pembayaran
                </button>

            </div>

            <small class="text-muted d-block mt-2">
                Gunakan menu Verifikasi Pembayaran untuk memeriksa transaksi penyewa.
            </small>

        </div>
    </div>

</div>
@endsection
