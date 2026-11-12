<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotifikasiController extends Controller
{
    /**
     * Menampilkan daftar notifikasi milik penyewa.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $notifikasi = $user->notifikasi()
            ->latest()
            ->paginate(10);

        $jumlahBelumDibaca = $user->notifikasi()
            ->where('sudah_dibaca', false)
            ->count();

        return view('penyewa.notifikasi.index', compact(
            'notifikasi',
            'jumlahBelumDibaca'
        ));
    }

    /**
     * Menandai satu notifikasi sebagai sudah dibaca.
     */
    public function tandaiDibaca(Request $request, int $id): RedirectResponse
    {
        $notifikasi = $request->user()
            ->notifikasi()
            ->findOrFail($id);

        $notifikasi->update([
            'sudah_dibaca' => true,
        ]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca.
     */
    public function tandaiSemuaDibaca(Request $request): RedirectResponse
    {
        $request->user()
            ->notifikasi()
            ->where('sudah_dibaca', false)
            ->update([
                'sudah_dibaca' => true,
            ]);

        return back()->with('success', 'Semua notifikasi sudah dibaca.');
    }
}