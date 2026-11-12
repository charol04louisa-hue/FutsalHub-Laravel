<?php

namespace App\Http\Controllers\Penyewa;

use App\Enums\StatusBooking;
use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UlasanLapanganController extends Controller
{
    public function store(Request $request, Pesanan $pesanan): RedirectResponse
    {
        // Pastikan pesanan benar-benar milik penyewa yang sedang login.
        $pesanan = Pesanan::query()
            ->whereKey($pesanan->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Booking harus sudah disetujui.
        if ($pesanan->status_booking !== StatusBooking::Disetujui) {
            return back()->with('error', 'Booking belum disetujui. Kamu belum bisa memberikan ulasan.');
        }

        // Waktu sewa harus sudah selesai.
        if (!$pesanan->waktu_selesai || now()->lt($pesanan->waktu_selesai)) {
            return back()->with('error', 'Kamu hanya bisa memberikan ulasan setelah waktu sewa selesai.');
        }

        // Satu booking hanya boleh memiliki satu ulasan.
        if ($pesanan->ulasan()->exists()) {
            return back()->with('error', 'Kamu sudah memberikan ulasan untuk booking ini.');
        }

        // Validasi data ulasan.
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan' => ['nullable', 'string', 'max:1000'],
        ]);

        // Simpan ulasan melalui relasi pesanan.
        $pesanan->ulasan()->create([
            'user_id' => auth()->id(),
            'lapangan_id' => $pesanan->id_lapangan,
            'rating' => $validated['rating'],
            'ulasan' => $validated['ulasan'] ?? null,
        ]);

        return back()->with('success', 'Terima kasih! Rating dan ulasan kamu berhasil disimpan.');
    }
}
