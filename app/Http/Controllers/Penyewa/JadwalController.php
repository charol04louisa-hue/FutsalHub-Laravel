<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'nullable|date_format:Y-m-d|after_or_equal:today',
            'jam' => [
                'nullable',
                'in:08:00,09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00,18:00,19:00,20:00,21:00,22:00',
            ],
            'durasi' => 'nullable|integer|in:1,2,3',
        ]);

        $lapangan = Lapangan::findOrFail($id);

        $tanggal = $request->query('tanggal', now()->toDateString());
        $jamDipilih = $request->query('jam');
        $durasiDipilih = $request->query('durasi');

        $pesanan = Pesanan::query()
            ->where('id_lapangan', $lapangan->id)
            ->whereDate('tanggal_sewa', $tanggal)
            ->whereIn('status_booking', [
                'menunggu_verifikasi',
                'disetujui',
            ])
            ->orderBy('jam_mulai')
            ->get();

        return view('penyewa.lapangan.jadwal', compact(
            'lapangan',
            'tanggal',
            'pesanan',
            'jamDipilih',
            'durasiDipilih'
        ));
    }
}
