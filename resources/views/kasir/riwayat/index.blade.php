
@extends('layouts.app')

@section('content')
<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold mb-1">Riwayat Pembayaran</h2>
            <p class="text-muted mb-0">
                Daftar transaksi yang sudah diproses oleh kasir.
            </p>
        </div>

        <a href="{{ route('kasir.dashboard') }}" class="btn btn-outline-secondary">
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('kasir.riwayat') }}" method="GET">
                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label for="tanggal" class="form-label fw-semibold">
                            Tanggal Sewa
                        </label>
                        <input
                            type="date"
                            name="tanggal"
                            id="tanggal"
                            class="form-control"
                            value="{{ request('tanggal') }}"
                        >
                    </div>

                    <div class="col-md-4">
                        <label for="metode" class="form-label fw-semibold">
                            Metode Pembayaran
                        </label>
                        <select name="metode" id="metode" class="form-select">
                            <option value="">Semua Metode</option>

                            @foreach ($daftarMetode as $metode)
                                <option
                                    value="{{ $metode->value }}"
                                    {{ request('metode') === $metode->value ? 'selected' : '' }}
                                >
                                    {{ $metode->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Filter
                        </button>

                        <a href="{{ route('kasir.riwayat') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Transaksi</h5>
                <span class="text-muted small">
                    {{ $riwayat->total() }} transaksi
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Penyewa</th>
                            <th>Lapangan</th>
                            <th>Tanggal Sewa</th>
                            <th>Metode</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Diverifikasi Oleh</th>
                            <th>Waktu Verifikasi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($riwayat as $item)

                            @php
                                $status = $item->status_pembayaran;

                                $statusClass = match ($status?->value) {
                                    'menunggu_verifikasi' => 'bg-warning text-dark',
                                    'lunas' => 'bg-success',
                                    'belum_bayar' => 'bg-secondary',
                                    'ditolak' => 'bg-danger',
                                    default => 'bg-light text-dark',
                                };
                            @endphp

                            <tr>
                                <td class="fw-semibold">
                                    #{{ $item->id }}
                                </td>

                                <td>
                                    {{ $item->penyewa?->nama_lengkap ?? $item->nama_pemesan }}
                                </td>

                                <td>
                                    {{ $item->lapangan?->nama_lapangan ?? '-' }}
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d/m/Y') }}
                                </td>

                                <td>
                                    {{ $item->jenis_pembayaran?->label() ?? '-' }}
                                </td>

                                <td class="text-nowrap">
                                    Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                                </td>

                                <td>
                                    <span class="badge {{ $statusClass }}">
                                        {{ $status?->label() ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $item->verifikator?->nama_lengkap ?? '-' }}
                                </td>

                                <td class="text-nowrap">
                                    {{ $item->diverifikasi_pada?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <h6 class="fw-semibold">Belum ada riwayat pembayaran</h6>
                                        <p class="mb-0">
                                            Transaksi yang sudah diproses kasir akan muncul di sini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $riwayat->links() }}
            </div>

        </div>
    </div>

</div>
@endsection


