
@extends('layouts.app')

@section('content')
    <div class="container py-5">

        <div class="mb-4">
            <h2 class="fw-bold">Riwayat Transaksi</h2>
            <p class="text-muted">
                Lihat seluruh riwayat pemesanan dan pembayaran lapanganmu.
            </p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($riwayat->isEmpty())
            <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                <h5 class="fw-bold">Belum Ada Transaksi</h5>
                <p class="text-muted">
                    Kamu belum memiliki riwayat pemesanan lapangan.
                </p>
                <div>
                    <a href="{{ route('penyewa.lapangan') }}"
                        class="btn btn-success">
                        Cari Lapangan
                    </a>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">
                    Total {{ $riwayat->count() }} transaksi
                </span>
            </div>

            <div class="row g-4">
                @foreach ($riwayat as $item)
                    <div class="col-12">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-body p-4">

                                <div class="d-flex flex-wrap justify-content-between gap-3 mb-3">
                                    <div>
                                        <small class="text-muted">
                                            Kode Transaksi
                                        </small>
                                        <h6 class="fw-bold mb-1">
                                            FH-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                        </h6>
                                        <small class="text-muted">
                                            {{ $item->created_at?->format('d/m/Y H:i') }}
                                        </small>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 align-items-start">
                                        <span class="badge rounded-pill
                                            {{ $item->status_booking?->value === 'disetujui' ? 'bg-success' :
                                                ($item->status_booking?->value === 'ditolak' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                            Booking:
                                            {{ $item->status_booking?->label() ?? '-' }}
                                        </span>

                                        <span class="badge rounded-pill
                                            {{ $item->status_pembayaran?->value === 'lunas' ? 'bg-success' :
                                                ($item->status_pembayaran?->value === 'ditolak' ? 'bg-danger' :
                                                ($item->status_pembayaran?->value === 'menunggu_verifikasi' ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                                            Pembayaran:
                                            {{ $item->status_pembayaran?->label() ?? '-' }}
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="row g-3 align-items-center">
                                    <div class="col-md-2">
                                        @if ($item->lapangan?->gambar)
                                            <img
                                                src="{{ asset('assets/img/' . $item->lapangan->gambar) }}"
                                                alt="{{ $item->lapangan->nama_lapangan }}"
                                                class="img-fluid rounded-3 w-100"
                                                style="height: 110px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                                                style="height: 110px;">
                                                <small class="text-muted">Foto tidak tersedia</small>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-7">
                                        <h5 class="fw-bold mb-2">
                                            {{ $item->lapangan?->nama_lapangan ?? 'Lapangan tidak tersedia' }}
                                        </h5>

                                        <p class="text-muted mb-1">
                                            <strong>Tanggal:</strong>
                                            {{ $item->tanggal_sewa?->translatedFormat('d F Y') ?? '-' }}
                                        </p>

                                        <p class="text-muted mb-1">
                                            <strong>Jam:</strong>
                                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }}
                                            -
                                            {{ \Carbon\Carbon::parse($item->jam_mulai)->addHours($item->durasi_jam)->format('H:i') }}
                                        </p>

                                        <p class="text-muted mb-0">
                                            <strong>Metode pembayaran:</strong>
                                            {{ $item->jenis_pembayaran?->label() ?? 'Belum dipilih' }}
                                        </p>
                                    </div>

                                    <div class="col-md-3 text-md-end">
                                        <small class="text-muted">Total transaksi</small>

                                        <h5 class="fw-bold text-success mt-1">
                                            Rp{{ number_format((float) $item->total_harga, 0, ',', '.') }}
                                        </h5>

                                        @if (
                                            $item->status_booking?->value === 'menunggu_verifikasi'
                                            && in_array($item->status_pembayaran?->value, ['belum_bayar', 'ditolak'], true)
                                        )
                                            <a href="{{ route('penyewa.pembayaran.show', $item->id) }}"
                                                class="btn btn-success btn-sm mt-2">
                                                Lanjut Pembayaran
                                            </a>

                                        @elseif (
                                            $item->status_booking?->value === 'menunggu_verifikasi'
                                            && $item->status_pembayaran?->value === 'menunggu_verifikasi'
                                        )
                                            <a href="{{ route('penyewa.pembayaran.show', $item->id) }}"
                                                class="btn btn-outline-success btn-sm mt-2">
                                                Lihat Pembayaran
                                            </a>
                                        @endif

                                        <div class="mt-2">
                                            <a href="{{ route('penyewa.riwayat.detail', $item->id) }}"
                                                class="btn btn-outline-dark btn-sm">
                                                Detail / E-Ticket
                                            </a>
                                        </div>
                                    </div>
                                </div>
<div class="mt-2 d-flex flex-wrap gap-2 justify-content-md-end">

    <a href="{{ route('penyewa.riwayat.detail', $item->id) }}"
        class="btn btn-outline-dark btn-sm">
        Detail / E-Ticket
    </a>

    @if ($item->lapangan)
        <a href="{{ route('penyewa.lapangan.jadwal', $item->lapangan->id) }}"
            class="btn btn-success btn-sm">
            <i class="bi bi-arrow-repeat me-1"></i>
            Booking Ulang
        </a>
    @endif

</div>
                                @if ($item->alasan_penolakan)
                                    <div class="alert alert-danger mt-3 mb-0">
                                        <strong>Alasan penolakan:</strong>
                                        {{ $item->alasan_penolakan }}
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
@endsection
