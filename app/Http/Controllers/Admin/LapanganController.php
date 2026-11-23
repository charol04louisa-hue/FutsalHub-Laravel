<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LapanganController extends Controller
{
    public function index()
    {
        $lapangan = Lapangan::with('owner')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.lapangan.index', compact('lapangan'));
    }

    public function create()
    {
        $pemilik = User::where('role', Role::Pemilik->value)
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.lapangan.create', compact('pemilik'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lapangan' => ['required', 'string', 'max:100'],
            'ukuran' => ['nullable', 'string', 'max:50'],
            'jenis_lantai' => ['nullable', 'string', 'max:100'],
            'harga_per_jam' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'owner_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', Role::Pemilik->value),
            ],
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $namaFile = Str::uuid() . '.' . $gambar->extension();

            File::ensureDirectoryExists(
                public_path('assets/img/lapangan')
            );

            $gambar->move(
                public_path('assets/img/lapangan'),
                $namaFile
            );

            $validated['gambar'] = 'lapangan/' . $namaFile;
        }

        Lapangan::create($validated);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(int $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $pemilik = User::where('role', Role::Pemilik->value)
            ->orderBy('nama_lengkap')
            ->get();

        return view(
            'admin.lapangan.edit',
            compact('lapangan', 'pemilik')
        );
    }

    public function update(Request $request, int $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $validated = $request->validate([
            'nama_lapangan' => ['required', 'string', 'max:100'],
            'ukuran' => ['nullable', 'string', 'max:50'],
            'jenis_lantai' => ['nullable', 'string', 'max:100'],
            'harga_per_jam' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],
            'deskripsi' => ['nullable', 'string'],
            'gambar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'owner_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', Role::Pemilik->value),
            ],
        ]);

        if ($request->hasFile('gambar')) {
            $gambarLama = $lapangan->gambar;
            $gambar = $request->file('gambar');
            $namaFile = Str::uuid() . '.' . $gambar->extension();

            File::ensureDirectoryExists(
                public_path('assets/img/lapangan')
            );

            $gambar->move(
                public_path('assets/img/lapangan'),
                $namaFile
            );

            $validated['gambar'] = 'lapangan/' . $namaFile;

            if ($gambarLama && str_starts_with($gambarLama, 'lapangan/')) {
                $pathLama = public_path('assets/img/' . $gambarLama);

                if (File::exists($pathLama)) {
                    File::delete($pathLama);
                }
            }
        }

        $lapangan->update($validated);

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $lapangan = Lapangan::findOrFail($id);

        $lapangan->delete();

        return redirect()
            ->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
