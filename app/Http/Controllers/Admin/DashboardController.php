<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\User;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal laporan
        $tanggalAwal = $request->input(
            'tanggal_awal',
            now()->startOfMonth()->toDateString()
        );

        $tanggalAkhir = $request->input(
            'tanggal_akhir',
            now()->toDateString()
        );

        // Validasi tanggal
        $request->validate([
            'tanggal_awal' => ['nullable', 'date'],
            'tanggal_akhir' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_awal',
            ],
        ]);

        $awal = Carbon::parse($tanggalAwal)->startOfDay();
        $akhir = Carbon::parse($tanggalAkhir)->endOfDay();

        // Query dasar berdasarkan periode
        $pesananPeriode = Pesanan::query()
            ->whereBetween('created_at', [$awal, $akhir]);

        // Total pendapatan dari pembayaran lunas
        $totalPendapatan = (clone $pesananPeriode)
            ->where('status_pembayaran', StatusPembayaran::Lunas->value)
            ->sum('total_harga');

        // Total transaksi dalam periode
        $totalTransaksi = (clone $pesananPeriode)->count();

        // Total seluruh pengguna
        $totalPengguna = User::count();

        // Booking yang masih menunggu verifikasi
        $bookingMenunggu = (clone $pesananPeriode)
            ->where('status_booking', StatusBooking::MenungguVerifikasi->value)
            ->count();

        // Data grafik pendapatan harian
        $grafikPendapatan = (clone $pesananPeriode)
            ->where('status_pembayaran', StatusPembayaran::Lunas->value)
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as pendapatan')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('tanggal')
            ->get();

        // Daftar booking yang menunggu verifikasi
        $daftarBookingMenunggu = (clone $pesananPeriode)
            ->with(['lapangan', 'penyewa'])
            ->where('status_booking', StatusBooking::MenungguVerifikasi->value)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.dashboard', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'totalPendapatan',
            'totalTransaksi',
            'totalPengguna',
            'bookingMenunggu',
            'grafikPendapatan',
            'daftarBookingMenunggu'
        ));
    }
}
