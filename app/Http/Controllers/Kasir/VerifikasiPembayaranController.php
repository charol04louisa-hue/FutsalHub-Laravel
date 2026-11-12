<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use App\Enums\MetodePembayaran;
use App\Enums\StatusPembayaran;
use App\Enums\StatusBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VerifikasiPembayaranController extends Controller
{
    public function index(Request $request)
    {
        // Pembayaran QRIS dan Transfer yang menunggu verifikasi.
        $pembayaran = Pesanan::with(['penyewa', 'lapangan'])
            ->where('status_pembayaran', StatusPembayaran::MenungguVerifikasi)
            ->whereNotNull('bukti_pembayaran')
            ->latest()
            ->paginate(10);

        // Booking Tunai yang belum dibayar dan belum kedaluwarsa.
        $pembayaranTunai = Pesanan::with(['penyewa', 'lapangan'])
            ->where('jenis_pembayaran', MetodePembayaran::Tunai->value)
            ->where('status_pembayaran', StatusPembayaran::BelumBayar->value)
            ->where('status_booking', StatusBooking::MenungguVerifikasi->value)
            ->whereNotNull('batas_pembayaran')
            ->where('batas_pembayaran', '>', now())
            ->latest()
            ->paginate(10, ['*'], 'tunai_page');

        return view(
            'kasir.verifikasi.index',
            compact('pembayaran', 'pembayaranTunai')
        );
    }

    public function lihatBukti(Pesanan $pesanan)
    {
        $path = $pesanan->bukti_pembayaran;

        abort_unless(
            $path && Storage::disk('local')->exists($path),
            404,
            'Bukti pembayaran tidak ditemukan.'
        );

        $fullPath = Storage::disk('local')->path($path);
        $mimeType = Storage::disk('local')->mimeType($path)
            ?: 'application/octet-stream';

        return response()->stream(
            function () use ($fullPath) {
                while (ob_get_level() > 0) {
                    ob_end_clean();
                }

                readfile($fullPath);
            },
            200,
            [
                'Content-Type' => $mimeType,
                'Content-Length' => filesize($fullPath),
                'Content-Disposition' => 'inline',
                'Cache-Control' => 'private, no-store',
                'X-Content-Type-Options' => 'nosniff',
            ]
        );
    }

    // Menyetujui pembayaran QRIS dan Transfer.
    public function setujui(Pesanan $pesanan)
    {
        DB::transaction(function () use ($pesanan) {
            $pesanan = Pesanan::whereKey($pesanan->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $pesanan->status_pembayaran === StatusPembayaran::MenungguVerifikasi
                && $pesanan->bukti_pembayaran
                && Storage::disk('local')->exists($pesanan->bukti_pembayaran),
                422,
                'Pembayaran tidak dapat diverifikasi.'
            );

            $pesanan->status_pembayaran = StatusPembayaran::Lunas;
            $pesanan->status_booking = StatusBooking::Disetujui;
            $pesanan->alasan_penolakan = null;
            $pesanan->diverifikasi_oleh = auth()->id();
            $pesanan->diverifikasi_pada = now();
            $pesanan->save();

            Notifikasi::create([
                'user_id' => $pesanan->user_id,
                'pesanan_id' => $pesanan->id,
                'jenis' => 'pembayaran_disetujui',
                'judul' => 'Pembayaran Berhasil Diverifikasi',
                'pesan' => 'Pembayaran untuk pesanan #' . $pesanan->id .
                    ' telah disetujui. Booking lapangan kamu sudah dikonfirmasi.',
                'sudah_dibaca' => false,
            ]);
        });

        return redirect()
            ->route('kasir.verifikasi')
            ->with('success', 'Pembayaran berhasil disetujui.');
    }

    // Mengonfirmasi pembayaran Tunai yang diterima kasir.
    public function konfirmasiTunai(Pesanan $pesanan)
    {
        $berhasil = DB::transaction(function () use ($pesanan) {
            $booking = Pesanan::whereKey($pesanan->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $booking->jenis_pembayaran === MetodePembayaran::Tunai,
                422,
                'Pesanan ini bukan pembayaran tunai.'
            );

            if ($booking->status_booking === StatusBooking::Kedaluwarsa) {
                return false;
            }

            abort_unless(
                $booking->status_booking === StatusBooking::MenungguVerifikasi
                && $booking->status_pembayaran === StatusPembayaran::BelumBayar,
                422,
                'Pembayaran tunai tidak dapat dikonfirmasi.'
            );

            if (
                !$booking->batas_pembayaran ||
                $booking->batas_pembayaran->lessThanOrEqualTo(now())
            ) {
                $booking->status_booking = StatusBooking::Kedaluwarsa;
                $booking->save();

                Notifikasi::create([
                    'user_id' => $booking->user_id,
                    'pesanan_id' => $booking->id,
                    'jenis' => 'pembayaran',
                    'judul' => 'Booking Kedaluwarsa',
                    'pesan' => 'Booking #' . $booking->id .
                        ' kedaluwarsa karena batas pembayaran tunai telah habis.',
                    'sudah_dibaca' => false,
                ]);

                return false;
            }

            $booking->status_pembayaran = StatusPembayaran::Lunas;
            $booking->status_booking = StatusBooking::Disetujui;
            $booking->alasan_penolakan = null;
            $booking->diverifikasi_oleh = auth()->id();
            $booking->diverifikasi_pada = now();
            $booking->save();

            Notifikasi::create([
                'user_id' => $booking->user_id,
                'pesanan_id' => $booking->id,
                'jenis' => 'pembayaran_disetujui',
                'judul' => 'Pembayaran Tunai Dikonfirmasi',
                'pesan' => 'Pembayaran tunai untuk pesanan #' . $booking->id .
                    ' telah diterima oleh kasir. Booking lapangan kamu sudah dikonfirmasi.',
                'sudah_dibaca' => false,
            ]);

            return true;
        });

        if (!$berhasil) {
            return redirect()
                ->route('kasir.verifikasi')
                ->with(
                    'error',
                    'Batas pembayaran tunai telah lewat. Booking sudah kedaluwarsa.'
                );
        }

        return redirect()
            ->route('kasir.verifikasi')
            ->with('success', 'Pembayaran tunai berhasil dikonfirmasi.');
    }

    // Menolak pembayaran QRIS dan Transfer.
    public function tolak(Request $request, Pesanan $pesanan)
    {
        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ]);

        DB::transaction(function () use ($pesanan, $validated) {
            $pesanan = Pesanan::whereKey($pesanan->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $pesanan->status_pembayaran === StatusPembayaran::MenungguVerifikasi
                && $pesanan->bukti_pembayaran
                && Storage::disk('local')->exists($pesanan->bukti_pembayaran),
                422,
                'Pembayaran tidak dapat diverifikasi.'
            );

            $pesanan->status_pembayaran = StatusPembayaran::Ditolak;
            $pesanan->alasan_penolakan = $validated['alasan_penolakan'];
            $pesanan->diverifikasi_oleh = auth()->id();
            $pesanan->diverifikasi_pada = now();

            if (
                !$pesanan->batas_pembayaran ||
                $pesanan->batas_pembayaran->lessThanOrEqualTo(now())
            ) {
                $pesanan->status_booking = StatusBooking::Kedaluwarsa;
            } else {
                $pesanan->status_booking = StatusBooking::MenungguVerifikasi;
            }

            $pesanan->save();

            Notifikasi::create([
                'user_id' => $pesanan->user_id,
                'pesanan_id' => $pesanan->id,
                'jenis' => 'pembayaran_ditolak',
                'judul' => 'Pembayaran Ditolak',
                'pesan' => 'Pembayaran untuk pesanan #' . $pesanan->id .
                    ' ditolak. Alasan: ' . $validated['alasan_penolakan'],
                'sudah_dibaca' => false,
            ]);
        });

        return redirect()
            ->route('kasir.verifikasi')
            ->with('success', 'Pembayaran berhasil ditolak.');
    }
}
