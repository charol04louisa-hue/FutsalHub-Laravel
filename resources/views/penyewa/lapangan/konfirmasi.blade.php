@extends('layouts.app')
@section('title', 'Konfirmasi Pemesanan')
@section('content')

<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('penyewa.lapangan.jadwal', [
            'id' => $lapangan->id,
            'tanggal' => $tanggal,
            'jam' => $jam,
            'durasi' => $durasi
        ]) }}" class="text-decoration-none text-secondary">
            ← Kembali ke Jadwal
        </a>
    </div>

    <div class="fh-page-head mb-4">
        <div>
            <div class="text-uppercase small fw-bold mb-2">
                FUTSALHUB / PEMESANAN
            </div>

            <h1 class="fw-bold mb-2">Konfirmasi Pemesanan</h1>

            <p class="text-secondary mb-0">
                Periksa kembali detail pesanan sebelum melanjutkan.
            </p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="fh-panel p-4 h-100">

                <h5 class="fw-bold mb-4">Detail Pemesanan</h5>

                <div class="mb-3">
                    <small class="text-secondary">Nama Pemesan</small>
                    <div class="fw-semibold">
                        {{ auth()->user()->nama_lengkap }}
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <small class="text-secondary">Lapangan</small>
                    <div class="fw-semibold">
                        {{ $lapangan->nama_lapangan }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-secondary">Tanggal Bermain</small>
                    <div class="fw-semibold">
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-secondary">Jam Bermain</small>
                    <div class="fw-semibold">
                        {{ $jam }} – {{ $selesai->format('H:i') }}
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-secondary">Durasi</small>
                    <div class="fw-semibold">
                        {{ $durasi }} jam
                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-5">
            <div class="fh-panel p-4">

                <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">Harga per jam</span>
                    <strong>
                        Rp{{ number_format($hargaPerJam, 0, ',', '.') }}
                    </strong>
                </div>

                <div class="d-flex justify-content-between mb-3">
                    <span class="text-secondary">Durasi</span>
                    <strong>{{ $durasi }} jam</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="fw-bold">Total Pembayaran</span>
                    <h4 class="fw-bold text-success mb-0">
                        Rp{{ number_format($totalHarga, 0, ',', '.') }}
                    </h4>
                </div>

                <div class="alert alert-info">
                    <small>
                        Status booking setelah konfirmasi adalah
                        <strong>Menunggu Verifikasi</strong>.
                        Pembayaran akan dilakukan pada tahap berikutnya.
                    </small>
                </div>

                <form method="POST"
                      action="{{ route('penyewa.booking.store', $lapangan->id) }}">

                    @csrf

                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="jam" value="{{ $jam }}">
                    <input type="hidden" name="durasi" value="{{ $durasi }}">

                    <button type="submit" class="fh-btn w-100">
                        Konfirmasi Pemesanan
                    </button>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection
