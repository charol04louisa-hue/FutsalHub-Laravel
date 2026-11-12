
@extends('layouts.app')

@section('content')
<div class="container py-5">

    {{-- Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-1">Detail Transaksi</h2>
            <p class="text-muted mb-0">
                Informasi lengkap pemesanan lapanganmu.
            </p>
        </div>

        <a href="{{ route('penyewa.riwayat') }}"
           class="btn btn-outline-success">
            Kembali ke Riwayat
        </a>
    </div>


{{-- Layout khusus PDF --}}
<div class="print-ticket">

    <div class="print-header">
        <div>
            <div class="print-brand">FUTSALHUB</div>
            <h2>E-Ticket Booking</h2>
            <p>Bukti informasi pemesanan lapangan</p>
        </div>
        <div class="print-code">
            <small>KODE TRANSAKSI</small>
            <h3>FH-{{ str_pad($pesanan->id, 6, '0', STR_PAD_LEFT) }}</h3>
        </div>
    </div>

    <div class="print-status">
        <div>
            <small>STATUS BOOKING</small>
            <strong>{{ $pesanan->status_booking?->label() ?? '-' }}</strong>
        </div>
        <div>
            <small>STATUS PEMBAYARAN</small>
            <strong>{{ $pesanan->status_pembayaran?->label() ?? '-' }}</strong>
        </div>
    </div>

    <h4 class="print-section-title">INFORMASI LAPANGAN</h4>

    <div class="print-court">
        @if ($pesanan->lapangan?->gambar)
            <img
                src="{{ asset('assets/img/' . $pesanan->lapangan->gambar) }}"
                alt="Foto lapangan">
        @endif

        <div>
            <h3>{{ $pesanan->lapangan?->nama_lapangan ?? 'Lapangan tidak tersedia' }}</h3>
            <p>{{ $pesanan->lapangan?->ukuran ?? '-' }}</p>
            <p>{{ $pesanan->lapangan?->jenis_lantai ?? '-' }}</p>
        </div>
    </div>

    <h4 class="print-section-title">INFORMASI PENYEWA</h4>

    <div class="print-grid">
        <div>
            <small>Nama Pemesan</small>
            <strong>{{ $pesanan->nama_pemesan }}</strong>
        </div>
        <div>
            <small>Waktu Pemesanan</small>
            <strong>{{ $pesanan->created_at?->format('d/m/Y H:i') ?? '-' }}</strong>
        </div>
    </div>

    <h4 class="print-section-title">JADWAL BERMAIN</h4>

    <div class="print-grid">
        <div>
            <small>Tanggal</small>
            <strong>{{ $pesanan->tanggal_sewa?->translatedFormat('d F Y') ?? '-' }}</strong>
        </div>
        <div>
            <small>Jam Mulai</small>
            <strong>{{ \Carbon\Carbon::parse($pesanan->jam_mulai)->format('H:i') }}</strong>
        </div>
        <div>
            <small>Jam Selesai</small>
            <strong>{{ \Carbon\Carbon::parse($pesanan->jam_mulai)->addHours($pesanan->durasi_jam)->format('H:i') }}</strong>
        </div>
        <div>
            <small>Durasi</small>
            <strong>{{ $pesanan->durasi_jam }} Jam</strong>
        </div>
    </div>

    <h4 class="print-section-title">INFORMASI PEMBAYARAN</h4>

    <div class="print-grid">
        <div>
            <small>Metode Pembayaran</small>
            <strong>{{ $pesanan->jenis_pembayaran?->label() ?? 'Belum dipilih' }}</strong>
        </div>
        <div>
            <small>Harga per Jam</small>
            <strong>Rp{{ number_format((float) $pesanan->harga_per_jam, 0, ',', '.') }}</strong>
        </div>
    </div>

    <div class="print-total">
        <span>TOTAL PEMBAYARAN</span>
        <strong>Rp{{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}</strong>
    </div>

    @if ($pesanan->alasan_penolakan)
        <div class="print-notice">
            Alasan Penolakan: {{ $pesanan->alasan_penolakan }}
        </div>
    @elseif ($pesanan->status_booking?->value === 'menunggu_verifikasi')
        <div class="print-notice">
            Tiket ini merupakan bukti informasi pemesanan dan belum menjadi bukti booking yang disetujui.
        </div>
    @elseif ($pesanan->status_booking?->value === 'dibatalkan')
        <div class="print-notice">
            Pesanan ini telah dibatalkan.
        </div>
    @endif

    <div class="print-footer">
        FUTSALHUB — DIGITAL BOOKING SYSTEM
    </div>

</div>

    {{-- Tiket Digital --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden ticket-print-area">

        <div class="p-4 text-white" style="background: #12372A;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <small class="opacity-75">FUTSALHUB DIGITAL TICKET</small>
                    <h3 class="fw-bold mt-2 mb-1">E-Ticket Booking</h3>
                    <p class="mb-0 opacity-75">
                        Bukti informasi pemesanan lapangan
                    </p>
                </div>

                <div class="text-end">
                    <small class="opacity-75">Kode Transaksi</small>
                    <h4 class="fw-bold mb-0">
                        FH-{{ str_pad($pesanan->id, 6, '0', STR_PAD_LEFT) }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="card-body p-4 p-md-5">

            {{-- Status --}}
            <div class="mb-4">
                <h6 class="fw-bold mb-3">Status Pemesanan</h6>

                <div class="d-flex flex-wrap gap-2">
                    <span class="badge rounded-pill px-3 py-2
                        {{ $pesanan->status_booking?->value === 'disetujui' ? 'bg-success' :
                            ($pesanan->status_booking?->value === 'ditolak' ? 'bg-danger' :
                            ($pesanan->status_booking?->value === 'dibatalkan' ? 'bg-secondary' : 'bg-warning text-dark')) }}">
                        Booking:
                        {{ $pesanan->status_booking?->label() ?? '-' }}
                    </span>

                    <span class="badge rounded-pill px-3 py-2
                        {{ $pesanan->status_pembayaran?->value === 'lunas' ? 'bg-success' :
                            ($pesanan->status_pembayaran?->value === 'ditolak' ? 'bg-danger' :
                            ($pesanan->status_pembayaran?->value === 'menunggu_verifikasi' ? 'bg-warning text-dark' : 'bg-secondary')) }}">
                        Pembayaran:
                        {{ $pesanan->status_pembayaran?->label() ?? '-' }}
                    </span>
                </div>
            </div>

            <hr>

            {{-- Informasi Lapangan --}}
            <h5 class="fw-bold mb-3">Informasi Lapangan</h5>

            <div class="row g-4 align-items-center mb-4">
                <div class="col-md-4">
                    @if ($pesanan->lapangan?->gambar)
                        <img
                            src="{{ asset('assets/img/' . $pesanan->lapangan->gambar) }}"
                            alt="{{ $pesanan->lapangan->nama_lapangan }}"
                            class="img-fluid rounded-3 w-100"
                            style="height: 190px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center"
                             style="height: 190px;">
                            <span class="text-muted">Foto tidak tersedia</span>
                        </div>
                    @endif
                </div>

                <div class="col-md-8">
                    <h4 class="fw-bold">
                        {{ $pesanan->lapangan?->nama_lapangan ?? 'Lapangan tidak tersedia' }}
                    </h4>

                    <p class="text-muted mb-1">
                        {{ $pesanan->lapangan?->ukuran ?? '-' }}
                    </p>

                    <p class="text-muted mb-0">
                        Jenis lantai:
                        {{ $pesanan->lapangan?->jenis_lantai ?? '-' }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- Informasi Penyewa --}}
            <h5 class="fw-bold mb-3">Informasi Penyewa</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <small class="text-muted">Nama Pemesan</small>
                    <p class="fw-semibold mb-0">
                        {{ $pesanan->nama_pemesan }}
                    </p>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Waktu Pemesanan</small>
                    <p class="fw-semibold mb-0">
                        {{ $pesanan->created_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </div>
            </div>

            <hr>

            {{-- Jadwal --}}
            <h5 class="fw-bold mb-3">Jadwal Bermain</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <small class="text-muted">Tanggal</small>
                    <p class="fw-semibold mb-0">
                        {{ $pesanan->tanggal_sewa?->translatedFormat('d F Y') ?? '-' }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Jam Mulai</small>
                    <p class="fw-semibold mb-0">
                        {{ \Carbon\Carbon::parse($pesanan->jam_mulai)->format('H:i') }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Jam Selesai</small>
                    <p class="fw-semibold mb-0">
                        {{ \Carbon\Carbon::parse($pesanan->jam_mulai)->addHours($pesanan->durasi_jam)->format('H:i') }}
                    </p>
                </div>

                <div class="col-md-4">
                    <small class="text-muted">Durasi</small>
                    <p class="fw-semibold mb-0">
                        {{ $pesanan->durasi_jam }} Jam
                    </p>
                </div>
            </div>

            <hr>

            {{-- Pembayaran --}}
            <h5 class="fw-bold mb-3">Informasi Pembayaran</h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <small class="text-muted">Metode Pembayaran</small>
                    <p class="fw-semibold mb-0">
                        {{ $pesanan->jenis_pembayaran?->label() ?? 'Belum dipilih' }}
                    </p>
                </div>

                <div class="col-md-6">
                    <small class="text-muted">Harga per Jam</small>
                    <p class="fw-semibold mb-0">
                        Rp{{ number_format((float) $pesanan->harga_per_jam, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="rounded-3 p-3 mb-4"
                 style="background: #F7F5EF;">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-semibold">Total Pembayaran</span>
                    <h4 class="fw-bold mb-0" style="color: #1D503A;">
                        Rp{{ number_format((float) $pesanan->total_harga, 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            {{-- Alasan Penolakan --}}
            @if ($pesanan->alasan_penolakan)
                <div class="alert alert-danger">
                    <strong>Alasan Penolakan</strong>
                    <p class="mb-0">{{ $pesanan->alasan_penolakan }}</p>
                </div>
            @endif

            {{-- Informasi Tiket --}}
            @if ($pesanan->status_booking?->value === 'dibatalkan')
                <div class="alert alert-secondary mb-4">
                    <strong>Pesanan Dibatalkan</strong>
                    <p class="mb-0">
                        Pesanan ini telah dibatalkan dan jadwal lapangan
                        kembali tersedia untuk dipesan.
                    </p>
                </div>
            @elseif ($pesanan->status_booking?->value === 'menunggu_verifikasi')
                <div class="alert alert-info mb-4">
                    <strong>Informasi:</strong>
                    Halaman ini merupakan bukti informasi pemesanan.
                    Tiket belum menjadi bukti booking yang disetujui apabila
                    status booking masih menunggu verifikasi.
                </div>
            @endif
            {{-- Rating dan Ulasan Lapangan --}}
            @php
                $waktuSewaSelesai = $pesanan->waktu_selesai
                    && now()->greaterThanOrEqualTo($pesanan->waktu_selesai);
            @endphp

            @if ($pesanan->status_booking?->value === 'disetujui')

                <hr>

                <h5 class="fw-bold mb-3">Rating dan Ulasan Lapangan</h5>

                @if ($pesanan->ulasan)
                    <div class="rounded-4 p-4 mb-4" style="background: #F7F5EF;">
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <strong>Ulasan Kamu</strong>
                            <span class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    {{ $i <= $pesanan->ulasan->rating ? '★' : '☆' }}
                                @endfor
                            </span>
                        </div>

                        <p class="text-muted mt-2 mb-0">
                            {{ $pesanan->ulasan->ulasan ?: 'Tidak ada komentar tertulis.' }}
                        </p>

                        <small class="text-muted d-block mt-2">
                            {{ $pesanan->ulasan->created_at?->format('d/m/Y H:i') }}
                        </small>
                    </div>

                @elseif ($waktuSewaSelesai)

                    <form action="{{ route('penyewa.riwayat.ulasan.store', $pesanan->id) }}"
                          method="POST"
                          class="rounded-4 p-4 mb-4"
                          style="background: #F7F5EF;">

                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Berikan Rating
                            </label>

                            <div class="d-flex gap-3 flex-wrap">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="d-flex align-items-center gap-1">
                                        <input
                                            type="radio"
                                            name="rating"
                                            value="{{ $i }}"
                                            {{ old('rating', 5) == $i ? 'checked' : '' }}
                                            required>
                                        <span class="text-warning fs-5">
                                            {{ $i }} ★
                                        </span>
                                    </label>
                                @endfor
                            </div>

                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="ulasan" class="form-label fw-semibold">
                                Ceritakan Pengalamanmu
                            </label>

                            <textarea
                                name="ulasan"
                                id="ulasan"
                                rows="4"
                                maxlength="1000"
                                class="form-control"
                                placeholder="Bagaimana pengalaman bermain di lapangan ini?">{{ old('ulasan') }}</textarea>

                            @error('ulasan')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">
                            Kirim Rating dan Ulasan
                        </button>
                    </form>

                @else
                    <div class="alert alert-info mb-4">
                        Kamu bisa memberikan rating dan ulasan setelah waktu sewa selesai.
                        <br>
                        <strong>
                            Waktu selesai:
                            {{ $pesanan->waktu_selesai?->format('d/m/Y H:i') ?? '-' }}
                        </strong>
                    </div>
                @endif
            @endif
            {{-- Tombol --}}
            <div class="d-flex flex-wrap gap-2 no-print">

                @php
                    $waktuMulai = \Carbon\Carbon::parse(
                        $pesanan->tanggal_sewa->format('Y-m-d') . ' ' . $pesanan->jam_mulai
                    );

                    $bolehBatalkan =
                        $pesanan->status_booking?->value === 'menunggu_verifikasi'
                        && $pesanan->status_pembayaran?->value !== 'lunas'
                        && now()->lt($waktuMulai);
                @endphp

                @if ($bolehBatalkan)

                    @if (in_array($pesanan->status_pembayaran?->value, ['belum_bayar', 'ditolak'], true))
                        <a href="{{ route('penyewa.pembayaran.show', $pesanan->id) }}"
                           class="btn btn-success">
                            Lanjut Pembayaran
                        </a>
                    @endif

                    <form action="{{ route('penyewa.riwayat.cancel', $pesanan->id) }}"
                          method="POST"
                          onsubmit="return confirm('Apakah kamu yakin ingin membatalkan pesanan ini?');">
                        @csrf

                        <button type="submit" class="btn btn-outline-danger">
                            Batalkan Pesanan
                        </button>
                    </form>

                @endif

                <button type="button"
                        onclick="window.print()"
                        class="btn btn-outline-dark">
                    Cetak / Simpan PDF
                </button>

            </div>

        </div>
    </div>
</div>

<style>


/* Tampilan PDF disembunyikan di website */
.print-ticket {
    display: none;
}

@page {
    size: A4 portrait;
    margin: 10mm;
}

@media print {

    html, body {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }

    body * {
        visibility: hidden !important;
    }

    .print-ticket,
    .print-ticket * {
        visibility: visible !important;
    }

    .print-ticket {
        display: block !important;
        position: absolute !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: auto !important;
        padding: 0 !important;
        margin: 0 !important;
        color: #26352d !important;
        font-family: Arial, sans-serif !important;
        font-size: 11px !important;
        background: white !important;
        box-sizing: border-box !important;
    }

    .print-header {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 22px !important;
        background: #12372A !important;
        color: white !important;
        border-radius: 10px 10px 0 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .print-brand {
        font-size: 12px !important;
        font-weight: bold !important;
        letter-spacing: 2px !important;
    }

    .print-header h2 {
        font-size: 23px !important;
        margin: 8px 0 4px !important;
    }

    .print-header p {
        margin: 0 !important;
        font-size: 11px !important;
    }

    .print-code {
        text-align: right !important;
    }

    .print-code small,
    .print-status small,
    .print-grid small {
        display: block !important;
        font-size: 9px !important;
        color: #68756d !important;
        margin-bottom: 5px !important;
    }

    .print-code small {
        color: #dce8df !important;
    }

    .print-code h3 {
        font-size: 20px !important;
        margin: 5px 0 0 !important;
    }

    .print-status {
        display: flex !important;
        gap: 12px !important;
        padding: 14px 18px !important;
        background: #f1f5f1 !important;
        border: 1px solid #dce5dc !important;
    }

    .print-status > div {
        flex: 1 !important;
    }

    .print-status strong,
    .print-grid strong {
        display: block !important;
        font-size: 11px !important;
    }

    .print-section-title {
        font-size: 11px !important;
        letter-spacing: 1px !important;
        color: #1d503a !important;
        border-bottom: 1px solid #dce5dc !important;
        padding-bottom: 7px !important;
        margin: 18px 0 10px !important;
    }

    .print-court {
        display: flex !important;
        align-items: center !important;
        gap: 18px !important;
    }

    .print-court img {
        width: 125px !important;
        height: 78px !important;
        object-fit: cover !important;
        border-radius: 7px !important;
    }

    .print-court h3 {
        font-size: 16px !important;
        margin: 0 0 7px !important;
    }

    .print-court p {
        margin: 3px 0 !important;
        font-size: 10px !important;
        color: #647067 !important;
    }

    .print-grid {
        display: grid !important;
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 13px 20px !important;
    }

    .print-total {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 17px 20px !important;
        margin-top: 18px !important;
        background: #f2f5ed !important;
        border: 1px solid #dce5dc !important;
        border-radius: 8px !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .print-total span {
        font-weight: bold !important;
        font-size: 11px !important;
    }

    .print-total strong {
        color: #1d503a !important;
        font-size: 20px !important;
    }

    .print-notice {
        margin-top: 14px !important;
        padding: 10px 13px !important;
        background: #f1f5f1 !important;
        border-left: 3px solid #1d503a !important;
        font-size: 10px !important;
        line-height: 1.5 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    .print-footer {
        margin-top: 20px !important;
        padding-top: 10px !important;
        border-top: 1px solid #dce5dc !important;
        text-align: center !important;
        color: #68756d !important;
        font-size: 9px !important;
        letter-spacing: 1px !important;
    }
}


</style>
@endsection
