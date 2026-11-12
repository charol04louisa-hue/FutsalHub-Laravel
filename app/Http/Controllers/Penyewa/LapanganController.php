<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Auth;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::query()
            ->orderBy('nama_lapangan', 'asc')
            ->get();

        $favoritIds = Auth::user()
            ->favoritLapangan()
            ->pluck('lapangan_id')
            ->toArray();

        return view('penyewa.lapangan.index', compact(
            'lapangan',
            'favoritIds'
        ));
    }
}
