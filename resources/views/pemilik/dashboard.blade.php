
@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@push('styles')
<style>
    .pemilik-dashboard {
        --pemilik-green: #168653;
        --pemilik-muted: #718096;
        --pemilik-border: #e8eee9;
    }

    .pemilik-dashboard .stat-card {
        background: #fff;
        border: 1px solid var(--pemilik-border);
        border-radius: 16px;
        padding: 21px;
        height: 100%;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .pemilik-dashboard .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(20, 60, 40, .07);
    }

    .pemilik-dashboard .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .pemilik-dashboard .stat-label {
        color: var(--pemilik-muted);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .pemilik-dashboard .stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 21px;
        flex-shrink: 0;
    }

    .pemilik-dashboard .stat-number {
        color: #1f2937;
        font-size: 27px;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 7px;
        overflow-wrap: anywhere;
    }

    .pemilik-dashboard .stat-note {
        font-size: 12px;
        color: var(--pemilik-muted);
        margin: 0;
    }

    .pemilik-dashboard .chart-card,
    .pemilik-dashboard .activity-card {
        background: #fff;
        border: 1px solid var(--pemilik-border);
        border-radius: 16px;
        padding: 23px;
    }

    .pemilik-dashboard .chart-title,
    .pemilik-dashboard .activity-title {
        font-size: 16px;
        font-weight: 800;
        color: #24352b;
        margin-bottom: 5px;
    }

    .pemilik-dashboard .chart-subtitle,
    .pemilik-dashboard .activity-subtitle {
        color: var(--pemilik-muted);
        font-size: 12px;
        margin-bottom: 20px;
    }

    .pemilik-dashboard .chart-area {
        position: relative;
        width: 100%;
        height: 300px;
    }

    .pemilik-dashboard .activity-table {
        min-width: 850px;
        margin-bottom: 0;
        vertical-align: middle;
    }

    .pemilik-dashboard .activity-table thead th {
        background: #f7faf8;
        color: #718096;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 13px 12px;
        border-bottom: 1px solid var(--pemilik-border);
        white-space: nowrap;
    }

    .pemilik-dashboard .activity-table tbody td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f3f1;
        font-size: 12px;
        color: #374151;
    }

    .pemilik-dashboard .activity-table tbody tr:last-child td {
        border-bottom: none;
    }

    .pemilik-dashboard .activity-name {
        font-weight: 700;
        color: #24352b;
        margin-bottom: 3px;
    }

    .pemilik-dashboard .activity-muted {
        font-size: 11px;
        color: #8a96a3;
    }

    .pemilik-dashboard .status-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .pemilik-dashboard .status-menunggu {
        background: #fff5df;
        color: #b7791f;
    }

    .pemilik-dashboard .status-disetujui,
    .pemilik-dashboard .status-lunas {
        background: #e8f7ee;
        color: #168653;
    }

    .pemilik-dashboard .status-ditolak {
        background: #feecec;
        color: #c53030;
    }

    .pemilik-dashboard .status-dibatalkan,
    .pemilik-dashboard .status-kedaluwarsa,
    .pemilik-dashboard .status-belum-bayar {
        background: #edf2f7;
        color: #64748b;
    }

    .pemilik-dashboard .empty-activity {
        text-align: center;
        padding: 40px 15px;
        color: #8a96a3;
        font-size: 13px;
    }

    .pemilik-dashboard .report-form .form-label {
        color: #374151;
        font-size: 12px;
    }

    .pemilik-dashboard .report-form .form-control {
        border-color: var(--pemilik-border);
        border-radius: 9px;
        min-height: 43px;
        font-size: 13px;
    }

    .pemilik-dashboard .report-button {
        min-height: 43px;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        padding: 10px 15px;
    }

    @media (max-width: 767px) {
        .pemilik-dashboard .stat-card {
            padding: 17px;
        }

        .pemilik-dashboard .stat-number {
            font-size: 23px;
        }

        .pemilik-dashboard .chart-card,
        .pemilik-dashboard .activity-card {
            padding: 18px;
        }

        .pemilik-dashboard .chart-area {
            height: 250px;
        }

        .pemilik-dashboard .report-button {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 pemilik-dashboard">

    {{-- HEADER --}}
    <x-dashboard-header
        eyebrow="FUTSALHUB MANAGEMENT"
        title="Dashboard Pemilik"
        :greeting="'Halo, ' . auth()->user()->nama_lengkap . '!'"
        description="Pantau dan kelola operasional lapangan futsal milikmu."
    />

    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Lapangan</span>
                    <div class="stat-icon" style="background:#e8f7ee;color:#168653;">
                        ⚽
                    </div>
                </div>
                <div class="stat-number">{{ $totalLapangan }}</div>
                <p class="stat-note">Lapangan yang dimiliki</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Karyawan</span>
                    <div class="stat-icon" style="background:#edf2ff;color:#5269b5;">
                        ♟
                    </div>
                </div>
                <div class="stat-number">{{ $totalKaryawan }}</div>
                <p class="stat-note">Admin dan kasir terdaftar</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Transaksi</span>
                    <div class="stat-icon" style="background:#fff5df;color:#d69a24;">
                        ▤
                    </div>
                </div>
                <div class="stat-number">{{ $totalTransaksi }}</div>
                <p class="stat-note">Seluruh transaksi penyewaan</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Pendapatan</span>
                    <div class="stat-icon" style="background:#e8f7ee;color:#168653;">
                        Rp
                    </div>
                </div>
                <div class="stat-number">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
                <p class="stat-note">Dari transaksi yang sudah lunas</p>
            </div>
        </div>

    </div>

    {{-- GRAFIK PENDAPATAN --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h5 class="chart-title">Grafik Pendapatan</h5>
                <p class="chart-subtitle">
                    Pendapatan dari transaksi lunas dalam 7 hari terakhir
                </p>

                <div class="chart-area">
                    <canvas id="grafikPendapatan"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- LAPORAN TRANSAKSI --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="activity-card">

                <h5 class="activity-title">Laporan Transaksi</h5>

                <p class="activity-subtitle">
                    Unduh laporan transaksi dan pendapatan dari seluruh lapangan milikmu.
                </p>

                <form id="formLaporanPemilik" class="row g-3 align-items-end report-form">

                    <div class="col-md-4">
                        <label for="tanggal_awal" class="form-label fw-semibold">
                            Tanggal Awal
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="tanggal_awal"
                            name="tanggal_awal"
                            value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                            required
                        >
                    </div>

                    <div class="col-md-4">
                        <label for="tanggal_akhir" class="form-label fw-semibold">
                            Tanggal Akhir
                        </label>

                        <input
                            type="date"
                            class="form-control"
                            id="tanggal_akhir"
                            name="tanggal_akhir"
                            value="{{ now()->format('Y-m-d') }}"
                            required
                        >
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex flex-wrap gap-2">

                            <button
                                type="button"
                                class="btn btn-danger report-button"
                                onclick="unduhLaporanPemilik('pdf')"
                            >
                                Download PDF
                            </button>

                            <button
                                type="button"
                                class="btn btn-success report-button"
                                onclick="unduhLaporanPemilik('excel')"
                            >
                                Download Excel
                            </button>

                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- AKTIVITAS PENYEWAAN TERBARU --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="activity-card">

                <h5 class="activity-title">Aktivitas Penyewaan Terbaru</h5>

                <p class="activity-subtitle">
                    Lima transaksi terbaru dari seluruh lapangan milikmu
                </p>

                <div class="table-responsive">
                    <table class="table activity-table">
                        <thead>
                            <tr>
                                <th>Penyewa</th>
                                <th>Lapangan</th>
                                <th>Jadwal Penyewaan</th>
                                <th>Total Harga</th>
                                <th>Status Booking</th>
                                <th>Jenis Pembayaran</th>
                                <th>Status Pembayaran</th>
                                <th>Diverifikasi Oleh</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($aktivitasTerbaru as $aktivitas)
                                <tr>
                                    <td>
                                        <div class="activity-name">
                                            {{ $aktivitas->nama_pemesan }}
                                        </div>
                                        <div class="activity-muted">
                                            Transaksi #{{ $aktivitas->id }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            {{ $aktivitas->lapangan?->nama_lapangan ?? 'Lapangan tidak tersedia' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            {{ $aktivitas->tanggal_sewa->format('d M Y') }}
                                        </div>
                                        <div class="activity-muted">
                                            {{ \Carbon\Carbon::parse($aktivitas->jam_mulai)->format('H:i') }}
                                            · {{ $aktivitas->durasi_jam }} jam
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            Rp {{ number_format((float) $aktivitas->total_harga, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    {{-- STATUS BOOKING --}}
                                    <td>
                                        @php
                                            $statusBooking = $aktivitas->status_booking;
                                            $statusBookingValue = $statusBooking?->value ?? '';
                                            $statusBookingLabel = $statusBooking?->label() ?? 'Tidak diketahui';

                                            $kelasBooking = match ($statusBookingValue) {
                                                'menunggu_verifikasi' => 'status-menunggu',
                                                'disetujui' => 'status-disetujui',
                                                'ditolak' => 'status-ditolak',
                                                'dibatalkan' => 'status-dibatalkan',
                                                'kedaluwarsa' => 'status-kedaluwarsa',
                                                default => 'status-belum-bayar',
                                            };
                                        @endphp

                                        <span class="status-badge {{ $kelasBooking }}">
                                            {{ $statusBookingLabel }}
                                        </span>
                                    </td>

                                    {{-- JENIS PEMBAYARAN --}}
                                    <td>
                                        <div class="activity-name">
                                            {{ $aktivitas->jenis_pembayaran?->label() ?? '-' }}
                                        </div>
                                    </td>

                                    {{-- STATUS PEMBAYARAN --}}
                                    <td>
                                        @php
                                            $statusPembayaran = $aktivitas->status_pembayaran;
                                            $statusPembayaranValue = $statusPembayaran?->value ?? '';
                                            $statusPembayaranLabel = $statusPembayaran?->label() ?? 'Tidak diketahui';

                                            $kelasPembayaran = match ($statusPembayaranValue) {
                                                'lunas' => 'status-lunas',
                                                'menunggu_verifikasi' => 'status-menunggu',
                                                'ditolak' => 'status-ditolak',
                                                'belum_bayar' => 'status-belum-bayar',
                                                default => 'status-belum-bayar',
                                            };
                                        @endphp

                                        <span class="status-badge {{ $kelasPembayaran }}">
                                            {{ $statusPembayaranLabel }}
                                        </span>
                                    </td>

                                    {{-- KARYAWAN YANG MEMVERIFIKASI --}}
                                    <td>
                                        @if ($aktivitas->verifikator)
                                            <div class="activity-name">
                                                {{ $aktivitas->verifikator->nama_lengkap }}
                                            </div>
                                            <div class="activity-muted">
                                                {{ ucfirst($aktivitas->verifikator->role->value ?? $aktivitas->verifikator->role) }}
                                            </div>
                                        @else
                                            <span class="activity-muted">
                                                Belum diverifikasi
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-activity">
                                            Belum ada aktivitas penyewaan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const labelPendapatan = @json($labelGrafikPendapatan);
    const dataPendapatan = @json($dataGrafikPendapatan);

    const canvasPendapatan = document.getElementById('grafikPendapatan');

    if (canvasPendapatan && typeof Chart !== 'undefined') {

        new Chart(canvasPendapatan, {
            type: 'bar',

            data: {
                labels: labelPendapatan,

                datasets: [{
                    label: 'Pendapatan',
                    data: dataPendapatan,
                    backgroundColor: 'rgba(22, 134, 83, 0.75)',
                    hoverBackgroundColor: '#116c42',
                    borderRadius: 7,
                    borderSkipped: false,
                    maxBarThickness: 48
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
                        displayColors: false,

                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(
                                    context.parsed.y
                                );
                            }
                        }
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
                            stepSize: 50000,
                            color: '#718096',

                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
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

});

// UNDUH LAPORAN PEMILIK
function unduhLaporanPemilik(format) {

    const tanggalAwal = document.getElementById('tanggal_awal').value;
    const tanggalAkhir = document.getElementById('tanggal_akhir').value;

    if (!tanggalAwal || !tanggalAkhir) {
        alert('Silakan lengkapi tanggal awal dan tanggal akhir.');
        return;
    }

    if (tanggalAkhir < tanggalAwal) {
        alert('Tanggal akhir tidak boleh lebih awal dari tanggal awal.');
        return;
    }

    const route = format === 'pdf'
        ? "{{ route('pemilik.export.pdf') }}"
        : "{{ route('pemilik.export.excel') }}";

    const params = new URLSearchParams({
        tanggal_awal: tanggalAwal,
        tanggal_akhir: tanggalAkhir
    });

    window.location.href = route + '?' + params.toString();
}
</script>
@endpush
