<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use App\Models\Pesanan;
use Carbon\Carbon;

class PublicController extends Controller
{
    /**
     * Menampilkan galeri lapangan untuk pengunjung publik.
     */
    public function lapangan()
    {
        $lapangan = Lapangan::query()
            ->with('owner')
            ->orderBy('nama_lapangan', 'asc')
            ->get();

        return view('public.lapangan', compact('lapangan'));
    }

    /**
     * Menampilkan slot kosong hari ini untuk setiap lapangan.
     */
    public function jadwal()
    {
        $tanggal = now()->toDateString();

        $lapangan = Lapangan::query()
            ->orderBy('nama_lapangan', 'asc')
            ->get();

        $pesanan = Pesanan::query()
            ->memblokirJadwal()
            ->whereDate('tanggal_sewa', $tanggal)
            ->orderBy('waktu_mulai')
            ->get();

        $jamTersedia = [];

        foreach ($lapangan as $item) {
            $jamTersedia[$item->id] = [];

            for ($jam = 8; $jam < 23; $jam++) {
                $mulai = Carbon::parse(
                    $tanggal . ' ' . sprintf('%02d:00:00', $jam)
                );

                $selesai = $mulai->copy()->addHour();

                // Lewati slot yang sudah lewat.
                if ($mulai->lte(now())) {
                    continue;
                }

                // Periksa bentrok dengan booking aktif.
                $bentrok = $pesanan->contains(function ($booking) use (
                    $item,
                    $mulai,
                    $selesai
                ) {
                    return $booking->id_lapangan == $item->id
                        && $mulai->lt($booking->waktu_selesai)
                        && $selesai->gt($booking->waktu_mulai);
                });

                if (!$bentrok) {
                    $jamTersedia[$item->id][] = $mulai->format('H:i');
                }
            }
        }

        return view('public.jadwal', compact(
            'lapangan',
            'jamTersedia',
            'tanggal'
        ));
    }
}
