<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Enums\MetodePembayaran;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RiwayatPembayaranController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => ['nullable', 'date'],
            'metode' => [
                'nullable',
                Rule::in(
                    array_map(
                        fn ($metode) => $metode->value,
                        MetodePembayaran::cases()
                    )
                ),
            ],
        ]);

        $riwayat = Pesanan::with([
            'penyewa',
            'lapangan',
            'verifikator',
        ])
            ->whereNotNull('diverifikasi_oleh')
            ->when(
                $validated['tanggal'] ?? null,
                fn ($query, $tanggal) =>
                    $query->whereDate('tanggal_sewa', $tanggal)
            )
            ->when(
                $validated['metode'] ?? null,
                fn ($query, $metode) =>
                    $query->where('jenis_pembayaran', $metode)
            )
            ->orderByDesc('diverifikasi_pada')
            ->paginate(10)
            ->withQueryString();

        $daftarMetode = MetodePembayaran::cases();

        return view('kasir.riwayat.index', compact(
            'riwayat',
            'daftarMetode'
        ));
    }
}

