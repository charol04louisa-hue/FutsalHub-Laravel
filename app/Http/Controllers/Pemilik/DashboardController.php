<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Pesanan;
use App\Enums\StatusPembayaran;
use Illuminate\Http\Request;

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

        return view('pemilik.dashboard', compact(
            'totalLapangan',
            'totalKaryawan',
            'totalTransaksi',
            'totalPendapatan'
        ));
    }
}
