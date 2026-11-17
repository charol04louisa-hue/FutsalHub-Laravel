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

        // Menghitung booking aktif milik penyewa
        $bookingAktif = $user->pesanan()
            ->whereIn('status_booking', [
                'menunggu_verifikasi',
                'disetujui',
            ])
            ->whereDate('tanggal_sewa', '>=', today())
            ->count();

        // Mengambil maksimal 5 jadwal mendatang
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

        // Menghitung total transaksi yang sudah lunas
        $totalTransaksi = $user->pesanan()
            ->where('status_pembayaran', 'lunas')
            ->sum('total_harga');

        // Mengambil daftar lapangan
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
