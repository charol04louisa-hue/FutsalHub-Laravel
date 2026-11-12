<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Enums\StatusPembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $menungguVerifikasi = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::MenungguVerifikasi
        )->count();

        $pembayaranBerhasil = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::Lunas
        )->count();

        $pembayaranDitolak = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::Ditolak
        )->count();

        $totalTransaksi = Pesanan::count();

        return view('kasir.dashboard', compact(
            'menungguVerifikasi',
            'pembayaranBerhasil',
            'pembayaranDitolak',
            'totalTransaksi'
        ));
    }
}
