
@extends('layouts.app')

@section('content')

<div class="container py-5">

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success rounded-3">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger rounded-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="row g-4 align-items-start">

        {{-- DETAIL PEMESANAN --}}
        <div class="col-lg-7">

            <div class="card border rounded-4 shadow-sm">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        1. Detail Pemesanan
                    </h4>

                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <span class="text-secondary">Kode Pesanan</span>
                        <strong>
                            #FH-{{ str_pad($pesanan->id, 5, '0', STR_PAD_LEFT) }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <span class="text-secondary">Nama Pemesan</span>
                        <strong>{{ $pesanan->nama_pemesan }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <span class="text-secondary">Tanggal</span>
                        <strong>
                            {{ $pesanan->tanggal_sewa->translatedFormat('d F Y') }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <span class="text-secondary">Jam Bermain</span>
                        <strong>
                            {{ substr($pesanan->jam_mulai, 0, 5) }}
                            –
                            {{ \Carbon\Carbon::parse($pesanan->jam_mulai)->addHours($pesanan->durasi_jam)->format('H:i') }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3 gap-3">
                        <span class="text-secondary">Durasi</span>
                        <strong>{{ $pesanan->durasi_jam }} Jam</strong>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <strong>Total Pembayaran</strong>
                        <h4 class="fw-bold text-success mb-0">
                            Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}
                        </h4>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary">Status Pembayaran</span>
                        <strong>
                            {{ $pesanan->status_pembayaran->label() }}
                        </strong>
                    </div>

                </div>
            </div>

        </div>

        {{-- METODE PEMBAYARAN --}}
        <div class="col-lg-5">

            <div class="card border rounded-4 shadow-sm">
                <div class="card-body p-4">

                    <h4 class="fw-bold mb-4">
                        2. Metode Pembayaran
                    </h4>

                    {{-- COUNTDOWN PEMBAYARAN --}}
                    @if (
                        $pesanan->status_pembayaran !== \App\Enums\StatusPembayaran::MenungguVerifikasi
                        && $pesanan->batas_pembayaran
                    )
                        <div
                            class="alert alert-warning rounded-3"
                            id="countdown-container"
                            data-deadline="{{ $pesanan->batas_pembayaran->timestamp * 1000 }}"
                        >
                            <div class="fw-bold mb-1">
                                <i class="bi bi-clock-history me-1"></i>
                                Batas Waktu Pembayaran
                            </div>

                            @if ($pesanan->jenis_pembayaran === \App\Enums\MetodePembayaran::Tunai)
                                <div class="small mb-2">
                                    Pembayaran tunai harus diselesaikan paling lambat 30 menit sebelum waktu bermain.
                                </div>
                            @else
                                <div class="small mb-2">
                                    Segera selesaikan pembayaran dan unggah bukti sebelum waktu habis.
                                </div>
                            @endif

                            <div
                                id="countdown"
                                class="fw-bold fs-3 text-danger"
                            >
                                Menghitung waktu...
                            </div>

                            <div class="small mt-1" id="countdown-message">
                                Waktu tersisa untuk menyelesaikan pembayaran.
                            </div>
                        </div>
                    @endif

                    {{-- STATUS MENUNGGU VERIFIKASI --}}
                    @if (
                        $pesanan->status_pembayaran === \App\Enums\StatusPembayaran::MenungguVerifikasi
                    )
                        <div class="alert alert-info rounded-3">
                            <strong>Bukti pembayaran sudah dikirim.</strong>
                            <div class="small mt-1">
                                Countdown pembayaran dihentikan sementara.
                                Silakan tunggu kasir memeriksa bukti pembayaran.
                            </div>
                        </div>
                    @endif

                    {{-- INFORMASI PENOLAKAN --}}
                    @if (
                        $pesanan->status_pembayaran === \App\Enums\StatusPembayaran::Ditolak
                    )
                        <div class="alert alert-danger rounded-3">
                            <strong>Pembayaran Ditolak</strong>

                            @if ($pesanan->alasan_penolakan)
                                <div class="small mt-2">
                                    Alasan: {{ $pesanan->alasan_penolakan }}
                                </div>
                            @endif

                            <div class="small mt-2">
                                Silakan perbaiki pembayaran dan unggah bukti baru
                                selama waktu pembayaran masih tersedia.
                            </div>
                        </div>
                    @endif

                    {{-- FORM PILIH METODE --}}
                    @if (
                        $pesanan->status_pembayaran !== \App\Enums\StatusPembayaran::MenungguVerifikasi
                    )

                        <form
                            id="form-metode"
                            action="{{ route('penyewa.pembayaran.store', $pesanan->id) }}"
                            method="POST"
                        >
                            @csrf

                            <div class="d-grid gap-3">

                                {{-- QRIS --}}
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-3">
                                    <input
                                        class="form-check-input m-0"
                                        type="radio"
                                        name="jenis_pembayaran"
                                        value="QRIS"
                                        {{ old('jenis_pembayaran', $pesanan->jenis_pembayaran?->value) === 'QRIS' ? 'checked' : '' }}
                                    >

                                    <div>
                                        <strong>QRIS</strong>
                                        <div class="small text-secondary">
                                            Pembayaran menggunakan scan QRIS.
                                        </div>
                                    </div>
                                </label>

                                {{-- TRANSFER BANK --}}
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-3">
                                    <input
                                        class="form-check-input m-0"
                                        type="radio"
                                        name="jenis_pembayaran"
                                        value="Transfer Bank"
                                        {{ old('jenis_pembayaran', $pesanan->jenis_pembayaran?->value) === 'Transfer Bank' ? 'checked' : '' }}
                                    >

                                    <div>
                                        <strong>Transfer Bank</strong>
                                        <div class="small text-secondary">
                                            Pembayaran melalui transfer bank.
                                        </div>
                                    </div>
                                </label>

                                {{-- TUNAI --}}
                                <label class="border rounded-3 p-3 d-flex align-items-center gap-3">
                                    <input
                                        class="form-check-input m-0"
                                        type="radio"
                                        name="jenis_pembayaran"
                                        value="Tunai"
                                        {{ old('jenis_pembayaran', $pesanan->jenis_pembayaran?->value) === 'Tunai' ? 'checked' : '' }}
                                    >

                                    <div>
                                        <strong>Tunai</strong>
                                        <div class="small text-secondary">
                                            Pembayaran langsung kepada kasir.
                                        </div>
                                        <div class="small text-secondary">
                                            Maksimal 30 menit sebelum bermain.
                                        </div>
                                    </div>
                                </label>

                            </div>

                            @error('jenis_pembayaran')
                                <div class="text-danger small mt-3">
                                    {{ $message }}
                                </div>
                            @enderror

                            <button
                                type="submit"
                                id="button-metode"
                                class="btn btn-success w-100 mt-4 py-3 rounded-3"
                            >
                                Simpan Metode Pembayaran
                            </button>

                        </form>

                    @endif

                    {{-- FORM UPLOAD BUKTI --}}
                    @if (
                        $pesanan->jenis_pembayaran &&
                        $pesanan->jenis_pembayaran->butuhBukti()
                    )

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3">
                            3. Upload Bukti Pembayaran
                        </h5>

                        @if (
                            $pesanan->status_pembayaran === \App\Enums\StatusPembayaran::MenungguVerifikasi
                        )

                            <div class="alert alert-warning rounded-3">
                                Bukti pembayaran sudah dikirim.
                                Silakan tunggu verifikasi kasir.
                            </div>

                        @else

                            <form
                                id="form-upload"
                                action="{{ route('penyewa.pembayaran.upload', $pesanan->id) }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
                                        Pilih Foto Bukti Pembayaran
                                    </label>

                                    <input
                                        type="file"
                                        name="bukti_pembayaran"
                                        class="form-control"
                                        accept="image/jpeg,image/png,image/webp"
                                        required
                                    >

                                    <div class="form-text">
                                        Format JPG, PNG, atau WEBP.
                                        Maksimal 5 MB.
                                    </div>

                                </div>

                                @error('bukti_pembayaran')
                                    <div class="text-danger small mb-3">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <button
                                    type="submit"
                                    id="button-upload"
                                    class="btn btn-success w-100 py-3 rounded-3"
                                >
                                    Upload Bukti Pembayaran
                                </button>

                            </form>

                        @endif

                    @elseif (
                        $pesanan->jenis_pembayaran === \App\Enums\MetodePembayaran::Tunai
                    )

                        <hr class="my-4">

                        <div class="alert alert-info rounded-3">
                            <strong>Pembayaran Tunai</strong>
                            <div class="small mt-1">
                                Silakan bayar langsung kepada kasir paling lambat
                                30 menit sebelum jadwal bermain.
                                Tidak perlu upload bukti pembayaran.
                            </div>
                        </div>

                    @endif

                </div>
            </div>

            <a
                href="{{ route('penyewa.dashboard') }}"
                class="btn btn-outline-secondary w-100 mt-3 rounded-3"
            >
                Kembali ke Dashboard
            </a>

        </div>

    </div>

</div>

{{-- SCRIPT COUNTDOWN --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('countdown-container');

    if (!container) {
        return;
    }

    const countdown = document.getElementById('countdown');
    const message = document.getElementById('countdown-message');
    const deadline = Number(container.dataset.deadline);

    const formMetode = document.getElementById('form-metode');
    const formUpload = document.getElementById('form-upload');

    let sudahReload = false;

    function perbaruiCountdown() {
        const sekarang = Date.now();
        const sisa = deadline - sekarang;

        if (sisa <= 0) {
            countdown.textContent = '00:00';
            message.textContent = 'Waktu pembayaran telah habis. Memeriksa status booking...';

            if (formMetode) {
                formMetode.querySelectorAll('input, button').forEach(function (element) {
                    element.disabled = true;
                });
            }

            if (formUpload) {
                formUpload.querySelectorAll('input, button').forEach(function (element) {
                    element.disabled = true;
                });
            }

            if (!sudahReload) {
                sudahReload = true;

                setTimeout(function () {
                    window.location.reload();
                }, 1500);
            }

            return;
        }

        const totalDetik = Math.floor(sisa / 1000);
        const jam = Math.floor(totalDetik / 3600);
        const menit = Math.floor((totalDetik % 3600) / 60);
        const detik = totalDetik % 60;

        const format = (angka) => String(angka).padStart(2, '0');

        countdown.textContent =
            format(jam) + ':' +
            format(menit) + ':' +
            format(detik);
    }

    perbaruiCountdown();
    setInterval(perbaruiCountdown, 1000);
});
</script>

@endsection
