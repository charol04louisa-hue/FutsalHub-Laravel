
@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@push('styles')
<style>
    .kasir-dashboard {
        --kasir-green: #168653;
        --kasir-dark: #123d2d;
        --kasir-muted: #718096;
        --kasir-border: #e8eee9;
    }

    .kasir-dashboard .kasir-header {
        background: linear-gradient(120deg, #123d2d, #198754);
        border-radius: 20px;
        padding: 30px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }

    .kasir-dashboard .kasir-header::after {
        content: "";
        position: absolute;
        width: 210px;
        height: 210px;
        border: 35px solid rgba(255,255,255,.08);
        border-radius: 50%;
        right: -45px;
        top: -75px;
    }

    .kasir-dashboard .kasir-header h2 {
        font-weight: 800;
        margin-bottom: 8px;
    }

    .kasir-dashboard .kasir-header p {
        color: rgba(255,255,255,.78);
        margin-bottom: 0;
    }

    .kasir-dashboard .kasir-stat-card {
        background: #fff;
        border: 1px solid var(--kasir-border);
        border-radius: 16px;
        padding: 21px;
        height: 100%;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .kasir-dashboard .kasir-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(20, 60, 40, .07);
    }

    .kasir-dashboard .kasir-stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .kasir-dashboard .kasir-stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 21px;
    }

    .kasir-dashboard .kasir-stat-label {
        color: var(--kasir-muted);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .kasir-dashboard .kasir-stat-number {
        color: #1f2937;
        font-size: 29px;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 7px;
    }

    .kasir-dashboard .kasir-stat-note {
        font-size: 12px;
        color: var(--kasir-muted);
        margin: 0;
    }

    .kasir-dashboard .kasir-panel {
        background: #fff;
        border: 1px solid var(--kasir-border);
        border-radius: 17px;
        padding: 23px;
        height: 100%;
    }

    .kasir-dashboard .kasir-panel-title {
        font-size: 16px;
        font-weight: 800;
        color: #24352b;
        margin-bottom: 5px;
    }

    .kasir-dashboard .kasir-panel-subtitle {
        color: var(--kasir-muted);
        font-size: 12px;
        margin-bottom: 20px;
    }

    .kasir-dashboard .kasir-chart-area {
        position: relative;
        width: 100%;
        height: 270px;
    }

    .kasir-dashboard .kasir-doughnut-area {
        position: relative;
        width: 100%;
        height: 270px;
    }

    .kasir-dashboard .kasir-action-card {
        border: 1px solid var(--kasir-border);
        border-radius: 17px;
        padding: 24px;
        background: #fff;
        height: 100%;
    }

    .kasir-dashboard .kasir-action-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
        margin-bottom: 16px;
    }

    .kasir-dashboard .kasir-action-card h5 {
        font-weight: 800;
        font-size: 16px;
        color: #24352b;
    }

    .kasir-dashboard .kasir-action-card p {
        color: var(--kasir-muted);
        font-size: 13px;
        line-height: 1.7;
    }

    .kasir-dashboard .kasir-action-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--kasir-green);
        color: #fff;
        padding: 10px 17px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        transition: background .2s ease;
    }

    .kasir-dashboard .kasir-action-link:hover {
        background: #116c42;
        color: #fff;
    }

    .kasir-dashboard .kasir-action-link-outline {
        background: #fff;
        border: 1px solid var(--kasir-green);
        color: var(--kasir-green);
    }

    .kasir-dashboard .kasir-action-link-outline:hover {
        background: #edf8f1;
        color: #116c42;
    }

    @media (max-width: 767px) {
        .kasir-dashboard .kasir-header {
            padding: 23px;
        }

        .kasir-dashboard .kasir-header h2 {
            font-size: 23px;
        }

        .kasir-dashboard .kasir-stat-card {
            padding: 17px;
        }

        .kasir-dashboard .kasir-stat-number {
            font-size: 25px;
        }

        .kasir-dashboard .kasir-panel {
            padding: 18px;
        }

        .kasir-dashboard .kasir-chart-area,
        .kasir-dashboard .kasir-doughnut-area {
            height: 240px;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 kasir-dashboard">

    {{-- HEADER --}}
    <x-dashboard-header
    eyebrow="FUTSALHUB MANAGEMENT"
    title="Dashboard Kasir"
    :greeting="'Halo, ' . auth()->user()->nama_lengkap . '!'"
    description="Kelola verifikasi dan riwayat pembayaran lapangan futsal."
/>
    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="kasir-stat-card">
                <div class="kasir-stat-top">
                    <span class="kasir-stat-label">Menunggu Verifikasi</span>
                    <div class="kasir-stat-icon"
                         style="background:#fff5df;color:#d69a24;">
                        ◷
                    </div>
                </div>

                <div class="kasir-stat-number">
                    {{ $menungguVerifikasi }}
                </div>

                <p class="kasir-stat-note">Perlu diperiksa kasir</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="kasir-stat-card">
                <div class="kasir-stat-top">
                    <span class="kasir-stat-label">Pembayaran Berhasil</span>
                    <div class="kasir-stat-icon"
                         style="background:#e8f7ee;color:#168653;">
                        ✓
                    </div>
                </div>

                <div class="kasir-stat-number">
                    {{ $pembayaranBerhasil }}
                </div>

                <p class="kasir-stat-note">Pembayaran lunas</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="kasir-stat-card">
                <div class="kasir-stat-top">
                    <span class="kasir-stat-label">Pembayaran Ditolak</span>
                    <div class="kasir-stat-icon"
                         style="background:#fff0f0;color:#dc5555;">
                        ✕
                    </div>
                </div>

                <div class="kasir-stat-number">
                    {{ $pembayaranDitolak }}
                </div>

                <p class="kasir-stat-note">Perlu tindak lanjut</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="kasir-stat-card">
                <div class="kasir-stat-top">
                    <span class="kasir-stat-label">Total Transaksi</span>
                    <div class="kasir-stat-icon"
                         style="background:#edf2ff;color:#5269b5;">
                        ▤
                    </div>
                </div>

                <div class="kasir-stat-number">
                    {{ $totalTransaksi }}
                </div>

                <p class="kasir-stat-note">Seluruh transaksi</p>
            </div>
        </div>

    </div>

    {{-- GRAFIK --}}
    <div class="row g-3 mb-4">

        <div class="col-lg-8">
            <div class="kasir-panel">
                <h5 class="kasir-panel-title">Aktivitas Transaksi</h5>

                <p class="kasir-panel-subtitle">
                    Jumlah transaksi yang dibuat dalam 7 hari terakhir
                </p>

                <div class="kasir-chart-area">
                    <canvas id="grafikTransaksi"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="kasir-panel">
                <h5 class="kasir-panel-title">Status Pembayaran</h5>

                <p class="kasir-panel-subtitle">
                    Distribusi seluruh transaksi berdasarkan status
                </p>

                <div class="kasir-doughnut-area">
                    <canvas id="grafikStatus"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- MENU KELOLA TRANSAKSI --}}
    <div class="mb-3">
        <h5 class="fw-bold mb-1">Kelola Transaksi</h5>
        <p class="text-muted small mb-0">
            Pilih menu untuk mengelola pembayaran penyewa.
        </p>
    </div>

    <div class="row g-3">

        <div class="col-md-6">
            <div class="kasir-action-card">
                <div class="kasir-action-icon"
                     style="background:#e8f7ee;color:#168653;">
                    ✓
                </div>

                <h5>Verifikasi Pembayaran</h5>

                <p>
                    Periksa bukti pembayaran penyewa dan proses
                    verifikasi transaksi yang menunggu tindakan kasir.
                </p>

                <a href="{{ route('kasir.verifikasi') }}"
                   class="kasir-action-link">
                    Buka Verifikasi
                    <span>→</span>
                </a>
            </div>
        </div>

        <div class="col-md-6">
            <div class="kasir-action-card">
                <div class="kasir-action-icon"
                     style="background:#edf2ff;color:#5269b5;">
                    ▤
                </div>

                <h5>Riwayat Pembayaran</h5>

                <p>
                    Lihat transaksi yang sudah diproses dan periksa
                    kembali catatan pembayaran penyewa.
                </p>

                <a href="{{ route('kasir.riwayat') }}"
                   class="kasir-action-link kasir-action-link-outline">
                    Buka Riwayat
                    <span>→</span>
                </a>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Data aktivitas transaksi dari database
    const labelTransaksi = @json($labelGrafikTransaksi);
    const dataTransaksi = @json($dataGrafikTransaksi);

    // Grafik aktivitas transaksi
    const canvasTransaksi = document.getElementById('grafikTransaksi');

    if (canvasTransaksi) {
        new Chart(canvasTransaksi, {
            type: 'bar',

            data: {
                labels: labelTransaksi,
                datasets: [{
                    label: 'Jumlah Transaksi',
                    data: dataTransaksi,
                    backgroundColor: 'rgba(22, 134, 83, 0.75)',
                    hoverBackgroundColor: '#116c42',
                    borderRadius: 7,
                    borderSkipped: false,
                    maxBarThickness: 38
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        display: false
                    },

                    tooltip: {
                        backgroundColor: '#173d2d',
                        padding: 12,
                        displayColors: false
                    }
                },

                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#718096',
                            font: {
                                size: 11
                            }
                        }
                    },

                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1,
                            color: '#718096'
                        },
                        grid: {
                            color: '#edf1ee',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // Data status pembayaran dari database
    const labelStatus = @json($labelGrafikStatus);
    const dataStatus = @json($dataGrafikStatus);

    // Grafik distribusi status pembayaran
    const canvasStatus = document.getElementById('grafikStatus');

    if (canvasStatus) {
        new Chart(canvasStatus, {
            type: 'doughnut',

            data: {
                labels: labelStatus,

                datasets: [{
                    data: dataStatus,

                    backgroundColor: [
                        '#e6aa35',
                        '#168653',
                        '#94a3b8',
                        '#dc5555'
                    ],

                    hoverOffset: 7,
                    borderWidth: 3,
                    borderColor: '#ffffff'
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '67%',

                plugins: {
                    legend: {
                        position: 'bottom',

                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 15,
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },

                    tooltip: {
                        backgroundColor: '#173d2d',
                        padding: 12
                    }
                }
            }
        });
    }

});
</script>
@endpush
