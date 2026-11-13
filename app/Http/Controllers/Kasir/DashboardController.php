<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Enums\MetodePembayaran;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        // QRIS dan Transfer Bank yang menunggu verifikasi bukti
        $menungguNonTunai = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::MenungguVerifikasi
        )
            ->whereNotNull('bukti_pembayaran')
            ->whereIn('jenis_pembayaran', [
                MetodePembayaran::Qris->value,
                MetodePembayaran::TransferBank->value,
            ])
            ->count();

        // Pembayaran Tunai yang menunggu konfirmasi kasir
        $menungguTunai = Pesanan::where(
            'jenis_pembayaran',
            MetodePembayaran::Tunai->value
        )
            ->where(
                'status_pembayaran',
                StatusPembayaran::BelumBayar
            )
            ->where(
                'status_booking',
                StatusBooking::MenungguVerifikasi->value
            )
            ->whereNotNull('batas_pembayaran')
            ->where('batas_pembayaran', '>', now())
            ->count();

        // Total seluruh transaksi yang menunggu tindakan kasir
        $menungguVerifikasi = $menungguNonTunai + $menungguTunai;

        // Pembayaran yang berhasil
        $pembayaranBerhasil = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::Lunas
        )->count();

        // Pembayaran yang ditolak
        $pembayaranDitolak = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::Ditolak
        )->count();

        // Seluruh transaksi
        $totalTransaksi = Pesanan::count();

        return view('kasir.dashboard', compact(
            'menungguVerifikasi',
            'pembayaranBerhasil',
            'pembayaranDitolak',
            'totalTransaksi'
        ));
    }
}
