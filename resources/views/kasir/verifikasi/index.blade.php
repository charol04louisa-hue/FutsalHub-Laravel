
@extends('layouts.app')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<div class="container py-4">

    <div class="mb-4">
        <h2 class="fw-bold">Verifikasi Pembayaran</h2>
        <p class="text-muted mb-0">
            Periksa pembayaran QRIS, Transfer Bank, dan Tunai dari penyewa.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- PEMBAYARAN QRIS DAN TRANSFER --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Pembayaran QRIS / Transfer Bank</h5>
                    <small class="text-muted">
                        {{ $pembayaran->total() }} transaksi menunggu verifikasi
                    </small>
                </div>

                <span class="badge bg-warning text-dark px-3 py-2">
                    Menunggu Verifikasi
                </span>
            </div>

            @if ($pembayaran->isEmpty())
                <div class="text-center py-4">
                    <h6 class="fw-semibold">Belum Ada Pembayaran</h6>
                    <p class="text-muted mb-0">
                        Tidak ada bukti pembayaran yang perlu diperiksa.
                    </p>
                </div>
            @else
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
                                <th>Bukti</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pembayaran as $item)
                                <tr>
                                    <td class="fw-bold">#{{ $item->id }}</td>

                                    <td>
                                        {{ $item->penyewa?->nama_lengkap ?? $item->nama_pemesan }}
                                    </td>

                                    <td>
                                        {{ $item->lapangan?->nama_lapangan ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($item->tanggal_sewa)->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $item->jam_mulai }}
                                        </small>
                                    </td>

                                    <td>
                                        {{ $item->jenis_pembayaran?->label() ?? '-' }}
                                    </td>

                                    <td class="fw-bold">
                                        Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <a
                                            href="{{ route('kasir.verifikasi.bukti', $item->id) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-success"
                                        >
                                            Lihat Bukti
                                        </a>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column gap-2">

                                            <form
                                                action="{{ route('kasir.verifikasi.setujui', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menyetujui pembayaran ini?')"
                                            >
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-success w-100"
                                                >
                                                    Setujui
                                                </button>
                                            </form>

                                            <button
                                                type="button"
                                                class="btn btn-sm btn-outline-danger w-100"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalTolak{{ $item->id }}"
                                            >
                                                Tolak
                                            </button>

                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Penolakan --}}
                                <div
                                    class="modal fade"
                                    id="modalTolak{{ $item->id }}"
                                    tabindex="-1"
                                    aria-hidden="true"
                                >
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <form
                                                action="{{ route('kasir.verifikasi.tolak', $item->id) }}"
                                                method="POST"
                                            >
                                                @csrf

                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold">
                                                        Tolak Pembayaran #{{ $item->id }}
                                                    </h5>

                                                    <button
                                                        type="button"
                                                        class="btn-close"
                                                        data-bs-dismiss="modal"
                                                    ></button>
                                                </div>

                                                <div class="modal-body">
                                                    <p class="text-muted">
                                                        Masukkan alasan penolakan agar penyewa mengetahui
                                                        mengapa pembayaran tidak dapat diterima.
                                                    </p>

                                                    <label class="form-label fw-semibold">
                                                        Alasan Penolakan
                                                    </label>

                                                    <textarea
                                                        name="alasan_penolakan"
                                                        class="form-control"
                                                        rows="4"
                                                        minlength="5"
                                                        maxlength="1000"
                                                        placeholder="Contoh: Bukti pembayaran tidak sesuai dengan nominal transaksi."
                                                        required
                                                    ></textarea>

                                                    <small class="text-muted">
                                                        Minimal 5 karakter dan maksimal 1000 karakter.
                                                    </small>
                                                </div>

                                                <div class="modal-footer">
                                                    <button
                                                        type="button"
                                                        class="btn btn-light"
                                                        data-bs-dismiss="modal"
                                                    >
                                                        Batal
                                                    </button>

                                                    <button
                                                        type="submit"
                                                        class="btn btn-danger"
                                                    >
                                                        Konfirmasi Penolakan
                                                    </button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $pembayaran->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- PEMBAYARAN TUNAI --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Pembayaran Tunai</h5>
                    <small class="text-muted">
                        {{ $pembayaranTunai->total() }} booking menunggu pembayaran di kasir
                    </small>
                </div>

                <span class="badge bg-info text-dark px-3 py-2">
                    Menunggu Pembayaran
                </span>
            </div>

            @if ($pembayaranTunai->isEmpty())
                <div class="text-center py-4">
                    <h6 class="fw-semibold">Belum Ada Pembayaran Tunai</h6>
                    <p class="text-muted mb-0">
                        Tidak ada booking tunai yang menunggu pembayaran saat ini.
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Penyewa</th>
                                <th>Lapangan</th>
                                <th>Tanggal Sewa</th>
                                <th>Batas Pembayaran</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pembayaranTunai as $tunai)
                                <tr>
                                    <td class="fw-bold">
                                        #{{ $tunai->id }}
                                    </td>

                                    <td>
                                        {{ $tunai->penyewa?->nama_lengkap ?? $tunai->nama_pemesan }}
                                    </td>

                                    <td>
                                        {{ $tunai->lapangan?->nama_lapangan ?? '-' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($tunai->tanggal_sewa)->format('d/m/Y') }}
                                        <br>
                                        <small class="text-muted">
                                            {{ $tunai->jam_mulai }}
                                        </small>
                                    </td>

                                    <td>
                                        <span class="text-danger fw-semibold">
                                            {{ $tunai->batas_pembayaran?->format('d/m/Y H:i') ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="fw-bold">
                                        Rp{{ number_format($tunai->total_harga, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        <span class="badge bg-warning text-dark">
                                            Belum Dibayar
                                        </span>
                                    </td>

                                    <td>
                                        <form
                                            action="{{ route('kasir.tunai.konfirmasi', $tunai->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Pastikan uang tunai sebesar Rp{{ number_format($tunai->total_harga, 0, ',', '.') }} sudah diterima. Konfirmasi pembayaran ini?')"
                                        >
                                            @csrf

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-success"
                                            >
                                                <i class="bi bi-check-circle me-1"></i>
                                                Konfirmasi Tunai
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $pembayaranTunai->links() }}
                </div>
            @endif

        </div>
    </div>

</div>
@endsection
