<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;

class DetailLapanganController extends Controller
{
    public function show($id)
    {
        $lapangan = Lapangan::with([
            'ulasan.penyewa',
        ])->findOrFail($id);

        $rataRataRating = $lapangan->ulasan()->avg('rating') ?? 0;

        $jumlahUlasan = $lapangan->ulasan()->count();

        $ulasan = $lapangan->ulasan()
            ->with('penyewa')
            ->latest()
            ->get();

        return view('penyewa.lapangan.detail', compact(
            'lapangan',
            'rataRataRating',
            'jumlahUlasan',
            'ulasan'
        ));
    }
}
