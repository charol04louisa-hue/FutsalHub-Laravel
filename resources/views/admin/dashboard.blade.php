
@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .admin-dashboard {
        --admin-green: #168653;
        --admin-muted: #718096;
        --admin-border: #e8eee9;
    }

    .admin-dashboard .stat-card,
    .admin-dashboard .chart-card,
    .admin-dashboard .activity-card,
    .admin-dashboard .filter-card {
        background: #fff;
        border: 1px solid var(--admin-border);
        border-radius: 16px;
        padding: 21px;
        height: 100%;
    }

    .admin-dashboard .stat-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .admin-dashboard .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(20, 60, 40, .07);
    }

    .admin-dashboard .stat-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        margin-bottom: 17px;
    }

    .admin-dashboard .stat-label {
        color: var(--admin-muted);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 7px;
    }

    .admin-dashboard .stat-icon {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        font-size: 20px;
        flex-shrink: 0;
    }

    .admin-dashboard .stat-number {
        color: #1f2937;
        font-size: 25px;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 7px;
        overflow-wrap: anywhere;
    }

    .admin-dashboard .stat-note {
        font-size: 12px;
        color: var(--admin-muted);
        margin: 0;
    }

    .admin-dashboard .section-title {
        font-size: 16px;
        font-weight: 800;
        color: #24352b;
        margin-bottom: 5px;
    }

    .admin-dashboard .section-subtitle {
        color: var(--admin-muted);
        font-size: 12px;
        margin-bottom: 20px;
    }

    .admin-dashboard .filter-card {
        height: auto;
    }

    .admin-dashboard .filter-label {
        display: block;
        color: #526154;
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 7px;
    }

    .admin-dashboard .filter-card .form-control {
        border: 1px solid #dfe7e1;
        border-radius: 9px;
        min-height: 42px;
        font-size: 13px;
    }

    .admin-dashboard .btn-filter {
        background: var(--admin-green);
        color: #fff;
        border: none;
        border-radius: 9px;
        min-height: 42px;
        padding: 0 20px;
        font-size: 13px;
        font-weight: 700;
    }

    .admin-dashboard .btn-filter:hover {
        background: #116c42;
        color: #fff;
    }

    .admin-dashboard .btn-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 16px;
        border: 1px solid #dfe7e1;
        border-radius: 9px;
        color: #64748b;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .admin-dashboard .chart-area {
        position: relative;
        width: 100%;
        height: 310px;
    }

    .admin-dashboard .activity-table {
        min-width: 850px;
        margin-bottom: 0;
        vertical-align: middle;
    }

    .admin-dashboard .activity-table thead th {
        background: #f7faf8;
        color: #718096;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .4px;
        padding: 13px 12px;
        border-bottom: 1px solid var(--admin-border);
        white-space: nowrap;
    }

    .admin-dashboard .activity-table tbody td {
        padding: 15px 12px;
        border-bottom: 1px solid #f0f3f1;
        font-size: 12px;
        color: #374151;
    }

    .admin-dashboard .activity-table tbody tr:last-child td {
        border-bottom: none;
    }

    .admin-dashboard .activity-name {
        font-weight: 700;
        color: #24352b;
        margin-bottom: 3px;
    }

    .admin-dashboard .activity-muted {
        font-size: 11px;
        color: #8a96a3;
    }

    .admin-dashboard .status-badge {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        white-space: nowrap;
    }

    .admin-dashboard .status-menunggu {
        background: #fff5df;
        color: #b7791f;
    }

    .admin-dashboard .empty-activity {
        text-align: center;
        padding: 40px 15px;
        color: #8a96a3;
        font-size: 13px;
    }

    @media (max-width: 767px) {
        .admin-dashboard .stat-card,
        .admin-dashboard .chart-card,
        .admin-dashboard .activity-card,
        .admin-dashboard .filter-card {
            padding: 17px;
        }

        .admin-dashboard .stat-number {
            font-size: 21px;
        }

        .admin-dashboard .chart-area {
            height: 250px;
        }
    }
</style>
@endpush

@section('content')
<div class="container py-4 admin-dashboard">

    {{-- HEADER --}}
    <x-dashboard-header
        eyebrow="FUTSALHUB MANAGEMENT"
        title="Dashboard Admin"
        :greeting="'Halo, ' . auth()->user()->nama_lengkap . '!'"
        description="Pantau aktivitas, transaksi, dan performa seluruh lapangan FutsalHub."
    />

    {{-- FILTER TANGGAL --}}
    <div class="filter-card mb-4">
        <h5 class="section-title">Filter Periode Laporan</h5>
        <p class="section-subtitle">
            Pilih periode untuk melihat statistik dan aktivitas transaksi.
        </p>

        <form action="{{ route('admin.dashboard') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <label for="tanggal_awal" class="filter-label">
                        Tanggal Awal
                    </label>
                    <input
                        type="date"
                        name="tanggal_awal"
                        id="tanggal_awal"
                        class="form-control"
                        value="{{ $tanggalAwal }}"
                        required
                    >
                </div>

                <div class="col-12 col-md-4">
                    <label for="tanggal_akhir" class="filter-label">
                        Tanggal Akhir
                    </label>
                    <input
                        type="date"
                        name="tanggal_akhir"
                        id="tanggal_akhir"
                        class="form-control"
                        value="{{ $tanggalAkhir }}"
                        min="{{ $tanggalAwal }}"
                        required
                    >
                </div>

                
<div class="col-12 d-flex flex-wrap gap-2">
    <button type="submit" class="btn-filter">
        Terapkan Filter
    </button>

    <a
        href="{{ route('admin.dashboard') }}"
        class="btn-reset"
    >
        Reset
    </a>

    <button
        type="submit"
        class="btn btn-danger"
        formaction="{{ route('admin.export.pdf') }}"
        formmethod="GET"
    >
        <i class="bi bi-file-earmark-pdf me-1"></i>
        Export PDF
    </button>

    <button
        type="submit"
        class="btn btn-success"
        formaction="{{ route('admin.export.excel') }}"
        formmethod="GET"
    >
        <i class="bi bi-file-earmark-excel me-1"></i>
        Export Excel
    </button>
</div>


    {{-- STATISTIK --}}
    <div class="row g-3 mb-4">

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Pendapatan</span>
                    <div class="stat-icon" style="background:#e8f7ee;color:#168653;">
                        Rp
                    </div>
                </div>

                <div class="stat-number">
                    Rp {{ number_format((float) $totalPendapatan, 0, ',', '.') }}
                </div>

                <p class="stat-note">Dari pembayaran yang sudah lunas</p>
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

                <div class="stat-number">
                    {{ number_format($totalTransaksi, 0, ',', '.') }}
                </div>

                <p class="stat-note">Transaksi dalam periode terpilih</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Total Pengguna</span>
                    <div class="stat-icon" style="background:#edf2ff;color:#5269b5;">
                        ♟
                    </div>
                </div>

                <div class="stat-number">
                    {{ number_format($totalPengguna, 0, ',', '.') }}
                </div>

                <p class="stat-note">Seluruh akun terdaftar</p>
            </div>
        </div>

        <div class="col-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Menunggu Verifikasi</span>
                    <div class="stat-icon" style="background:#fff5df;color:#d69a24;">
                        !
                    </div>
                </div>

                <div class="stat-number">
                    {{ number_format($bookingMenunggu, 0, ',', '.') }}
                </div>

                <p class="stat-note">Booking dalam periode terpilih</p>
            </div>
        </div>

    </div>

    {{-- GRAFIK PENDAPATAN --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="chart-card">
                <h5 class="section-title">Grafik Pendapatan</h5>
                <p class="section-subtitle">
                    Pendapatan harian dari transaksi lunas pada
                    {{ \Carbon\Carbon::parse($tanggalAwal)->format('d M Y') }}
                    sampai
                    {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d M Y') }}.
                </p>

                <div class="chart-area">
                    <canvas id="grafikPendapatan"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- MONITORING BOOKING --}}
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="activity-card">

                <h5 class="section-title">
                    Monitoring Booking Menunggu Verifikasi
                </h5>

                <p class="section-subtitle">
                    Daftar booking yang belum diverifikasi dalam periode terpilih.
                </p>

                <div class="table-responsive">
                    <table class="table activity-table">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Penyewa</th>
                                <th>Lapangan</th>
                                <th>Tanggal Pemesanan</th>
                                <th>Total Harga</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($daftarBookingMenunggu as $booking)
                                <tr>
                                    <td>
                                        <div class="activity-name">
                                            #{{ $booking->id }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            {{ $booking->nama_pemesan }}
                                        </div>
                                        <div class="activity-muted">
                                            {{ $booking->penyewa?->username ?? 'Akun tidak tersedia' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            {{ $booking->lapangan?->nama_lapangan ?? 'Lapangan tidak tersedia' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            {{ $booking->created_at?->format('d M Y') ?? '-' }}
                                        </div>
                                        <div class="activity-muted">
                                            {{ $booking->created_at?->format('H:i') ?? '-' }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="activity-name">
                                            Rp {{ number_format((float) $booking->total_harga, 0, ',', '.') }}
                                        </div>
                                    </td>

                                    <td>
                                        {{ $booking->jenis_pembayaran?->label() ?? '-' }}
                                    </td>

                                    <td>
                                        <span class="status-badge status-menunggu">
                                            Menunggu Verifikasi
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-activity">
                                            Tidak ada booking yang menunggu verifikasi
                                            pada periode ini.
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

    const grafik = @json($grafikPendapatan);

    const labels = grafik.map(item => {
        const tanggal = new Date(item.tanggal + 'T00:00:00');

        return tanggal.toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short'
        });
    });

    const data = grafik.map(item => Number(item.pendapatan));

    const canvas = document.getElementById('grafikPendapatan');

    if (canvas && typeof Chart !== 'undefined') {
        new Chart(canvas, {
            type: 'bar',

            data: {
                labels: labels,

                datasets: [{
                    label: 'Pendapatan',
                    data: data,
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
                                return 'Rp ' +
                                    new Intl.NumberFormat('id-ID').format(
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

                        max: Math.max(
                            500000,
                            Math.ceil(Math.max(0, ...data) / 100000) * 100000
                        ),

                        ticks: {
                            stepSize: 100000,
                            color: '#718096',

                            callback: function(value) {
                                return 'Rp ' +
                                    new Intl.NumberFormat('id-ID').format(value);
                            }
                        },

                        grid: {
                            color: '#edf1ee'
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
</script>
@endpush
