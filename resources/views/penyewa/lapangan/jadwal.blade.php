
@extends('layouts.app')
@section('title', 'Pilih Jadwal')
@section('content')

<div class="container py-5">

    <div class="mb-4">
        <a href="{{ route('penyewa.lapangan.detail', $lapangan->id) }}"
           class="text-decoration-none text-secondary">
            ← Kembali ke Detail Lapangan
        </a>
    </div>

    <div class="fh-page-head mb-4">
        <div>
            <div class="text-uppercase small fw-bold mb-2">
                FUTSALHUB / PEMESANAN
            </div>

            <h1 class="fw-bold mb-2">Pilih Jadwal Bermain</h1>

            <p class="text-secondary mb-0">
                {{ $lapangan->nama_lapangan }} ·
                Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}/jam
            </p>
        </div>
    </div>

    <div class="fh-panel p-4 mb-4">
        <h5 class="fw-bold mb-3">1. Pilih Tanggal Bermain</h5>

        <form method="GET"
              action="{{ route('penyewa.lapangan.jadwal', $lapangan->id) }}">

            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="tanggal" class="form-label fw-semibold">
                        Tanggal
                    </label>

                    <input type="date"
                           id="tanggal"
                           name="tanggal"
                           class="form-control"
                           min="{{ now()->toDateString() }}"
                           value="{{ $tanggal }}"
                           required>
                </div>

                <div class="col-md-3">
                    <button type="submit" class="fh-btn w-100">
                        Lihat Jadwal
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="fh-panel p-4 mb-4">

        <h5 class="fw-bold mb-3">2. Ketersediaan Jadwal</h5>

        <p class="text-secondary">
            Jadwal tanggal
            <strong>
                {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
            </strong>
        </p>

        <div class="d-flex flex-wrap gap-3 mb-4">
            <span><span class="badge bg-success">&nbsp;</span> Tersedia</span>
            <span><span class="badge bg-secondary">&nbsp;</span> Sudah dipesan</span>
            <span><span class="badge bg-primary">&nbsp;</span> Dipilih</span>
            <span><span class="badge bg-dark">&nbsp;</span> Sudah lewat</span>
        </div>

        @php
            $jamTerpakai = [];

            foreach ($pesanan as $booking) {
                $mulaiBooking = \Carbon\Carbon::parse($booking->waktu_mulai);
                $selesaiBooking = \Carbon\Carbon::parse($booking->waktu_selesai);

                for ($jamCek = 8; $jamCek < 23; $jamCek++) {
                    $mulaiSlot = \Carbon\Carbon::parse(
                        $tanggal . ' ' . sprintf('%02d:00:00', $jamCek)
                    );

                    $selesaiSlot = $mulaiSlot->copy()->addHour();

                    if (
                        $mulaiSlot->lt($selesaiBooking) &&
                        $selesaiSlot->gt($mulaiBooking)
                    ) {
                        $jamTerpakai[] = $jamCek;
                    }
                }
            }

            $jamTerpakai = array_unique($jamTerpakai);
        @endphp

        <div class="row g-2">

            @for ($jam = 8; $jam < 23; $jam++)

                @php
                    $waktu = sprintf('%02d:00', $jam);

                    $mulaiSlot = \Carbon\Carbon::parse(
                        $tanggal . ' ' . $waktu
                    );

                    $terpakai = in_array($jam, $jamTerpakai);
                    $sudahLewat = $mulaiSlot->lte(now());

                    $tidakTersedia = $terpakai || $sudahLewat;
                    $dipilih = $jamDipilih === $waktu;
                @endphp

                <div class="col-4 col-md-3 col-lg-2">

                    @if ($terpakai)

                        <button type="button"
                                class="btn btn-secondary w-100 py-3"
                                disabled>
                            <strong>{{ $waktu }}</strong>
                            <br>
                            <small>Dipesan</small>
                        </button>

                    @elseif ($sudahLewat)

                        <button type="button"
                                class="btn btn-dark w-100 py-3"
                                disabled>
                            <strong>{{ $waktu }}</strong>
                            <br>
                            <small>Sudah lewat</small>
                        </button>

                    @else

                        <a href="{{ route('penyewa.lapangan.jadwal', [
                            'id' => $lapangan->id,
                            'tanggal' => $tanggal,
                            'jam' => $waktu
                        ]) }}"
                           class="btn w-100 py-3 {{ $dipilih ? 'btn-primary' : 'btn-outline-success' }}">
                            <strong>{{ $waktu }}</strong>
                            <br>
                            <small>{{ $dipilih ? 'Dipilih' : 'Tersedia' }}</small>
                        </a>

                    @endif

                </div>

            @endfor

        </div>
    </div>

    @if ($jamDipilih)

        <div class="fh-panel p-4 mb-4">

            <h5 class="fw-bold mb-3">3. Pilih Durasi Bermain</h5>

            <form method="GET"
                  action="{{ route('penyewa.lapangan.jadwal', $lapangan->id) }}">

                <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                <input type="hidden" name="jam" value="{{ $jamDipilih }}">

                <div class="mb-3">
                    <label for="durasi" class="form-label fw-semibold">
                        Durasi
                    </label>

                    <select name="durasi"
                            id="durasi"
                            class="form-select"
                            required>

                        <option value="">Pilih durasi bermain</option>

                        @foreach ([1, 2, 3] as $durasi)

                            @php
                                $mulaiPilihan = \Carbon\Carbon::parse(
                                    $tanggal . ' ' . $jamDipilih
                                );

                                $selesaiPilihan = $mulaiPilihan->copy()
                                    ->addHours($durasi);

                                $bentrok = false;

                                // Tidak boleh memilih waktu yang sudah lewat.
                                if ($mulaiPilihan->lte(now())) {
                                    $bentrok = true;
                                }

                                // Jam operasional berakhir pukul 23.00.
                                if ($selesaiPilihan->gt(
                                    \Carbon\Carbon::parse($tanggal . ' 23:00:00')
                                )) {
                                    $bentrok = true;
                                }

                                // Periksa bentrok dengan seluruh booking aktif.
                                foreach ($pesanan as $booking) {
                                    $mulaiBooking = \Carbon\Carbon::parse(
                                        $booking->waktu_mulai
                                    );

                                    $selesaiBooking = \Carbon\Carbon::parse(
                                        $booking->waktu_selesai
                                    );

                                    if (
                                        $mulaiPilihan->lt($selesaiBooking) &&
                                        $selesaiPilihan->gt($mulaiBooking)
                                    ) {
                                        $bentrok = true;
                                        break;
                                    }
                                }
                            @endphp

                            <option value="{{ $durasi }}"
                                {{ (string) $durasiDipilih === (string) $durasi ? 'selected' : '' }}
                                {{ $bentrok ? 'disabled' : '' }}>

                                {{ $durasi }} Jam
                                {{ $bentrok ? '(Tidak tersedia)' : '' }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div class="bg-light rounded-3 p-3 mb-3">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Jam mulai</span>
                        <strong>{{ $jamDipilih }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Harga per jam</span>
                        <strong>
                            Rp{{ number_format($lapangan->harga_per_jam, 0, ',', '.') }}
                        </strong>
                    </div>

                    <div class="d-flex justify-content-between">
                        <span>Estimasi total</span>
                        <strong id="totalHarga" class="text-success">
                            Pilih durasi
                        </strong>
                    </div>

                </div>

                <button type="submit" class="fh-btn w-100">
                    Hitung Total Harga
                </button>

            </form>

            @if ($durasiDipilih)

                <div class="alert alert-success mt-3">
                    <strong>Jadwal pilihan:</strong>
                    {{ $jamDipilih }} –
                    {{ sprintf('%02d:00', (int) substr($jamDipilih, 0, 2) + (int) $durasiDipilih) }}

                    <br>

                    <strong>Total:</strong>
                    Rp{{ number_format($lapangan->harga_per_jam * (int) $durasiDipilih, 0, ',', '.') }}
                </div>

                <a href="{{ route('penyewa.booking.confirm', [
                    'id' => $lapangan->id,
                    'tanggal' => $tanggal,
                    'jam' => $jamDipilih,
                    'durasi' => $durasiDipilih
                ]) }}"
                   class="fh-btn w-100 text-decoration-none text-center d-block">
                    Lanjutkan Pemesanan →
                </a>

            @endif

        </div>

    @endif

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const durasi = document.getElementById('durasi');
    const totalHarga = document.getElementById('totalHarga');

    if (!durasi || !totalHarga) return;

    const hargaPerJam = {{ (int) $lapangan->harga_per_jam }};

    function hitungHarga() {

        if (!durasi.value) {
            totalHarga.textContent = 'Pilih durasi';
            return;
        }

        const total = hargaPerJam * Number(durasi.value);

        totalHarga.textContent = 'Rp' + total.toLocaleString('id-ID');
    }

    durasi.addEventListener('change', hitungHarga);

    hitungHarga();

});
</script>

@endsection
