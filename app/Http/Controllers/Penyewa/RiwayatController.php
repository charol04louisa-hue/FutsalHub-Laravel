<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // Menampilkan seluruh riwayat transaksi penyewa
    public function index()
    {
        $riwayat = Pesanan::with('lapangan')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('penyewa.riwayat', compact('riwayat'));
    }

    // Menampilkan detail satu transaksi
    public function show($id)
    {
        $pesanan = Pesanan::with('lapangan')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('penyewa.detail-transaksi', compact('pesanan'));
    }

    // Membatalkan pesanan oleh penyewa
    public function cancel($id)
    {
        DB::transaction(function () use ($id) {

            $pesanan = Pesanan::where('user_id', Auth::id())
                ->lockForUpdate()
                ->findOrFail($id);

            // Hanya booking yang menunggu verifikasi
            if ($pesanan->status_booking !== StatusBooking::MenungguVerifikasi) {
                abort(403, 'Pesanan ini tidak dapat dibatalkan.');
            }

            // Pembayaran yang sudah lunas tidak boleh dibatalkan
            if ($pesanan->status_pembayaran === StatusPembayaran::Lunas) {
                abort(403, 'Pesanan yang sudah lunas tidak dapat dibatalkan.');
            }

            // Jadwal bermain harus belum dimulai
            $waktuMulai = Carbon::parse(
                $pesanan->tanggal_sewa->format('Y-m-d') . ' ' . $pesanan->jam_mulai
            );

            if (now()->greaterThanOrEqualTo($waktuMulai)) {
                abort(403, 'Pesanan tidak dapat dibatalkan karena jadwal sudah dimulai.');
            }

            // UBAH STATUS BOOKING MENJADI DIBATALKAN
            $pesanan->status_booking = StatusBooking::Dibatalkan;

            // SIMPAN PERUBAHAN KE DATABASE
            $pesanan->save();
        });

        return redirect()
            ->route('penyewa.riwayat')
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
