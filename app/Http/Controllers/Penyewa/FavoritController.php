<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\FavoritLapangan;
use App\Models\Lapangan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoritController extends Controller
{
    /**
     * Menampilkan daftar lapangan favorit milik penyewa.
     */
    public function index(): View
    {
        $favorit = FavoritLapangan::with('lapangan')
            ->where('user_id', Auth::id())
            ->latest()
            ->get()
            ->filter(fn ($item) => $item->lapangan !== null);

        return view('penyewa.favorit.index', compact('favorit'));
    }

    /**
     * Menambahkan atau menghapus lapangan dari favorit.
     */
    public function toggle(int $id): RedirectResponse
    {
        $lapangan = Lapangan::findOrFail($id);

        $favorit = FavoritLapangan::where('user_id', Auth::id())
            ->where('lapangan_id', $lapangan->id)
            ->first();

        if ($favorit) {
            $favorit->delete();

            return back()->with(
                'success',
                'Lapangan berhasil dihapus dari favorit.'
            );
        }

        FavoritLapangan::create([
            'user_id' => Auth::id(),
            'lapangan_id' => $lapangan->id,
        ]);

        return back()->with(
            'success',
            'Lapangan berhasil ditambahkan ke favorit.'
        );
    }
}
