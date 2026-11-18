<?php

namespace App\Http\Controllers\Pemilik;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pemilik\StoreLapanganRequest;
use App\Models\Lapangan;
use Illuminate\Support\Str;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::milik(auth()->user())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pemilik.lapangan.index', compact('lapangan'));
    }

    public function create()
    {
        return view('pemilik.lapangan.create');
    }

    public function store(StoreLapanganRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = Str::uuid() . '.' . $gambar->extension();
            File::ensureDirectoryExists(public_path('assets/img/lapangan'));
            $gambar->move(public_path('assets/img/lapangan'), $namaFile);

            $validated['gambar'] = 'lapangan/' . $namaFile;
        }

        $validated['owner_id'] = auth()->id();

        Lapangan::create($validated);

        return redirect()
            ->route('pemilik.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $lapangan = Lapangan::milik(auth()->user())
            ->findOrFail($id);

        return view('pemilik.lapangan.edit', compact('lapangan'));
    }

    public function update(StoreLapanganRequest $request, int $id)
    {
        $lapangan = Lapangan::milik(auth()->user())
            ->findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('gambar')) {
            $gambarLama = $lapangan->gambar;

            $gambar = $request->file('gambar');
            $namaFile = Str::uuid() . '.' . $gambar->extension();

            $gambar->move(public_path('assets/img/lapangan'), $namaFile);

            $validated['gambar'] = 'lapangan/' . $namaFile;

            if ($gambarLama && str_starts_with($gambarLama, 'lapangan/')) {
                $pathLama = public_path('assets/img/' . $gambarLama);

                if (file_exists($pathLama)) {
                    unlink($pathLama);
                }
            }
        }

        $lapangan->update($validated);

        return redirect()
            ->route('pemilik.lapangan.index')
            ->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $lapangan = Lapangan::milik(auth()->user())
            ->findOrFail($id);

        $lapangan->delete();

        return redirect()
            ->route('pemilik.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
