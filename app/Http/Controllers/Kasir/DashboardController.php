<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Enums\MetodePembayaran;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // Total transaksi yang menunggu tindakan kasir
        $menungguVerifikasi = $menungguNonTunai + $menungguTunai;

        // Pembayaran berhasil
        $pembayaranBerhasil = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::Lunas
        )->count();

        // Pembayaran ditolak
        $pembayaranDitolak = Pesanan::where(
            'status_pembayaran',
            StatusPembayaran::Ditolak
        )->count();

        // Seluruh transaksi
        $totalTransaksi = Pesanan::count();

        // Grafik aktivitas transaksi 7 hari terakhir
        $awalPeriode = Carbon::today()->subDays(6);
        $akhirPeriode = Carbon::now();

        $transaksiHarian = Pesanan::query()
            ->selectRaw('DATE(created_at) as tanggal, COUNT(*) as total')
            ->whereBetween('created_at', [
                $awalPeriode,
                $akhirPeriode,
            ])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->pluck('total', 'tanggal');

        $labelGrafikTransaksi = [];
        $dataGrafikTransaksi = [];

        for ($i = 0; $i < 7; $i++) {
            $tanggal = $awalPeriode->copy()->addDays($i);
            $key = $tanggal->format('Y-m-d');

            $labelGrafikTransaksi[] = $tanggal->translatedFormat('D, d M');
            $dataGrafikTransaksi[] = (int) ($transaksiHarian[$key] ?? 0);
        }

        // Grafik distribusi status pembayaran
        $jumlahStatusPembayaran = Pesanan::query()
            ->select('status_pembayaran', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pembayaran')
            ->pluck('total', 'status_pembayaran');

        $labelGrafikStatus = [
            'Menunggu Verifikasi',
            'Lunas',
            'Belum Bayar',
            'Ditolak',
        ];

        $dataGrafikStatus = [
            (int) ($jumlahStatusPembayaran[StatusPembayaran::MenungguVerifikasi->value] ?? 0),
            (int) ($jumlahStatusPembayaran[StatusPembayaran::Lunas->value] ?? 0),
            (int) ($jumlahStatusPembayaran[StatusPembayaran::BelumBayar->value] ?? 0),
            (int) ($jumlahStatusPembayaran[StatusPembayaran::Ditolak->value] ?? 0),
        ];

        return view('kasir.dashboard', compact(
            'menungguVerifikasi',
            'pembayaranBerhasil',
            'pembayaranDitolak',
            'totalTransaksi',
            'labelGrafikTransaksi',
            'dataGrafikTransaksi',
            'labelGrafikStatus',
            'dataGrafikStatus'
        ));
    }
}
