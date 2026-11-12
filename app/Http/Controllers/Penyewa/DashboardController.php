<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        // Jumlah booking aktif milik penyewa
        $bookingAktif = $user->pesanan()
            ->whereIn('status_booking', [
                'menunggu_verifikasi',
                'disetujui',
            ])
            ->whereDate('tanggal_sewa', '>=', today())
            ->count();

        // Jadwal mendatang
        $jadwalMendatang = $user->pesanan()
            ->with('lapangan')
            ->whereIn('status_booking', [
                'menunggu_verifikasi',
                'disetujui',
            ])
            ->whereDate('tanggal_sewa', '>=', today())
            ->orderBy('tanggal_sewa')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        // Total transaksi yang sudah lunas
        $totalTransaksi = $user->pesanan()
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        // Daftar lapangan yang belum dihapus
        $daftarLapangan = Lapangan::query()
            ->orderBy('nama_lapangan')
            ->limit(4)
            ->get();

        return view('penyewa.dashboard', compact(
            'user',
            'bookingAktif',
            'jadwalMendatang',
            'totalTransaksi',
            'daftarLapangan'
        ));
    }
}
