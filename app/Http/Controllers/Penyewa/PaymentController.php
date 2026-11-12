<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Enums\MetodePembayaran;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Menampilkan halaman pembayaran.
    public function show($id)
    {
        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->kedaluwarsakanJikaPerlu($pesanan);

        $pesanan->refresh();

        if ($pesanan->status_booking !== StatusBooking::MenungguVerifikasi) {
            return redirect()
                ->route('penyewa.dashboard')
                ->with('error', 'Pesanan ini tidak dapat melakukan pembayaran.');
        }

        return view('penyewa.pembayaran', compact('pesanan'));
    }

    // Menyimpan pilihan metode pembayaran.
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_pembayaran' => [
                'required',
                'in:' . implode(',', array_column(
                    MetodePembayaran::cases(),
                    'value'
                )),
            ],
        ]);

        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->kedaluwarsakanJikaPerlu($pesanan);
        $pesanan->refresh();

        if (
            $pesanan->status_booking !== StatusBooking::MenungguVerifikasi ||
            !in_array($pesanan->status_pembayaran, [
                StatusPembayaran::BelumBayar,
                StatusPembayaran::Ditolak,
            ], true)
        ) {
            return redirect()
                ->route('penyewa.dashboard')
                ->with('error', 'Metode pembayaran tidak dapat diubah.');
        }

        $metode = MetodePembayaran::from(
            $validated['jenis_pembayaran']
        );

        if ($metode === MetodePembayaran::Tunai) {
            // Tunai harus dibayar paling lambat 30 menit sebelum bermain.
            $batasPembayaran = Carbon::parse($pesanan->waktu_mulai)
                ->subMinutes(30);

            if ($batasPembayaran->lessThanOrEqualTo(now())) {
                return back()->with(
                    'error',
                    'Pembayaran tunai hanya tersedia jika jadwal bermain masih lebih dari 30 menit lagi.'
                );
            }
        } else {
            // QRIS dan Transfer menggunakan deadline sejak booking dibuat.
            $batasPembayaran = $pesanan->created_at
                ->copy()
                ->addMinutes(15);

            if ($batasPembayaran->lessThanOrEqualTo(now())) {
                $pesanan->batas_pembayaran = $batasPembayaran;
                $pesanan->save();

                $this->kedaluwarsakanJikaPerlu($pesanan);

                return redirect()
                    ->route('penyewa.dashboard')
                    ->with(
                        'error',
                        'Waktu pembayaran sudah habis. Silakan buat booking baru.'
                    );
            }
        }

        $pesanan->jenis_pembayaran = $metode;
        $pesanan->batas_pembayaran = $batasPembayaran;
        $pesanan->save();

        return redirect()
            ->route('penyewa.pembayaran.show', $pesanan->id)
            ->with('success', 'Metode pembayaran berhasil dipilih.');
    }

    // Mengunggah bukti pembayaran.
    public function upload(Request $request, $id)
    {
        $pesanan = Pesanan::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->kedaluwarsakanJikaPerlu($pesanan);
        $pesanan->refresh();

        if (
            $pesanan->status_booking !== StatusBooking::MenungguVerifikasi ||
            !in_array($pesanan->status_pembayaran, [
                StatusPembayaran::BelumBayar,
                StatusPembayaran::Ditolak,
            ], true)
        ) {
            return redirect()
                ->route('penyewa.pembayaran.show', $pesanan->id)
                ->with('error', 'Pesanan ini tidak dapat mengunggah bukti pembayaran.');
        }

        if (
            !$pesanan->jenis_pembayaran ||
            !$pesanan->jenis_pembayaran->butuhBukti()
        ) {
            return back()->with(
                'error',
                'Metode pembayaran ini tidak membutuhkan bukti upload.'
            );
        }

        if (
            !$pesanan->batas_pembayaran ||
            $pesanan->batas_pembayaran->lessThanOrEqualTo(now())
        ) {
            $this->kedaluwarsakanJikaPerlu($pesanan);

            return redirect()
                ->route('penyewa.dashboard')
                ->with(
                    'error',
                    'Waktu pembayaran sudah habis. Silakan buat booking baru.'
                );
        }

        $validated = $request->validate([
            'bukti_pembayaran' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ], [
            'bukti_pembayaran.required' => 'Silakan pilih gambar bukti pembayaran.',
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'bukti_pembayaran.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $path = $request->file('bukti_pembayaran')
            ->store('bukti-pembayaran', 'local');

        if ($pesanan->bukti_pembayaran) {
            Storage::disk('local')->delete($pesanan->bukti_pembayaran);
        }

        $pesanan->bukti_pembayaran = $path;
        $pesanan->status_pembayaran = StatusPembayaran::MenungguVerifikasi;
        $pesanan->alasan_penolakan = null;
        $pesanan->save();

        Notifikasi::create([
            'user_id' => Auth::id(),
            'pesanan_id' => $pesanan->id,
            'jenis' => 'pembayaran',
            'judul' => 'Bukti Pembayaran Berhasil Diunggah',
            'pesan' => 'Bukti pembayaran untuk pesanan #' . $pesanan->id .
                ' berhasil diunggah. Silakan tunggu verifikasi kasir.',
            'sudah_dibaca' => false,
        ]);

        return redirect()
            ->route('penyewa.pembayaran.show', $pesanan->id)
            ->with(
                'success',
                'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi kasir.'
            );
    }

    // Mengubah booking menjadi kedaluwarsa jika batas pembayaran habis.
    private function kedaluwarsakanJikaPerlu(Pesanan $pesanan): void
    {
        DB::transaction(function () use ($pesanan) {
            $booking = Pesanan::whereKey($pesanan->id)
                ->lockForUpdate()
                ->first();

            if (!$booking) {
                return;
            }

            if (
                $booking->status_booking !== StatusBooking::MenungguVerifikasi ||
                !$booking->batas_pembayaran ||
                $booking->batas_pembayaran->isFuture()
            ) {
                return;
            }

            // Bukti yang sedang diperiksa kasir tidak kedaluwarsa.
            if (
                $booking->status_pembayaran === StatusPembayaran::MenungguVerifikasi &&
                $booking->bukti_pembayaran
            ) {
                return;
            }

            if ($booking->status_pembayaran === StatusPembayaran::Lunas) {
                return;
            }

            $booking->status_booking = StatusBooking::Kedaluwarsa;
            $booking->save();

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'pesanan_id' => $booking->id,
                'jenis' => 'pembayaran',
                'judul' => 'Booking Kedaluwarsa',
                'pesan' => 'Booking #' . $booking->id .
                    ' kedaluwarsa karena batas pembayaran telah habis.',
                'sudah_dibaca' => false,
            ]);
        });
    }
}
