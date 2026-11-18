<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Pesanan;
use App\Enums\StatusPembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $pemilik = auth()->user();

        // Mengambil ID seluruh lapangan milik Pemilik.
        $lapanganIds = Lapangan::milik($pemilik)->pluck('id');

        // Mengambil transaksi dari lapangan milik Pemilik.
        $pesanan = Pesanan::whereIn('id_lapangan', $lapanganIds);

        // Ringkasan dashboard.
        $totalLapangan = $lapanganIds->count();

        $totalKaryawan = $pemilik->karyawan()->count();

        $totalTransaksi = (clone $pesanan)->count();

        $totalPendapatan = (clone $pesanan)
            ->where('status_pembayaran', StatusPembayaran::Lunas->value)
            ->sum('total_harga');

        // Grafik pendapatan 7 hari terakhir.
        $tanggalMulai = now()->subDays(6)->startOfDay();
        $tanggalAkhir = now()->endOfDay();

        $pendapatanHarian = (clone $pesanan)
            ->where('status_pembayaran', StatusPembayaran::Lunas->value)
            ->whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw('DATE(created_at) as tanggal, SUM(total_harga) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        $labelGrafikPendapatan = [];
        $dataGrafikPendapatan = [];

        for (
            $tanggal = $tanggalMulai->copy();
            $tanggal->lte($tanggalAkhir);
            $tanggal->addDay()
        ) {
            $formatTanggal = $tanggal->format('Y-m-d');

            $labelGrafikPendapatan[] = $tanggal->format('d/m');

            $dataGrafikPendapatan[] = (float) (
                $pendapatanHarian[$formatTanggal] ?? 0
            );
        }

        // Lima aktivitas penyewaan terbaru.
        $aktivitasTerbaru = (clone $pesanan)
            ->with('lapangan', 'verifikator')
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(5)
            ->get();

        return view('pemilik.dashboard', compact(
            'totalLapangan',
            'totalKaryawan',
            'totalTransaksi',
            'totalPendapatan',
            'labelGrafikPendapatan',
            'dataGrafikPendapatan',
            'aktivitasTerbaru'
        ));
    }
}
