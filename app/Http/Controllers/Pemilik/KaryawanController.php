<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Pemilik\StoreKaryawanRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
class KaryawanController extends Controller
{
    /**
     * Menampilkan daftar karyawan milik Pemilik yang sedang login.
     */
    public function index()
    {
        $karyawan = User::query()
            ->where('owner_id', auth()->id())
            ->whereIn('role', ['admin', 'kasir'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pemilik.karyawan.index', compact('karyawan'));
    }

    /**
     * Menampilkan form tambah karyawan.
     */
    public function create()
    {
        return view('pemilik.karyawan.create');
    }

    /**
     * Menyimpan akun Admin atau Kasir baru.
     */
    public function store(StoreKaryawanRequest $request)
    {
        $data = $request->validated();

        User::create([
            'nama_lengkap' => $data['nama_lengkap'],
            'email' => $data['email'],
            'username' => $data['username'],
            'nomor_telepon' => $data['nomor_telepon'] ?? null,
            'password_hash' => Hash::make($data['password']),
            'role' => $data['role'],
            'owner_id' => auth()->id(),
        ]);

        return redirect()
            ->route('pemilik.karyawan.index')
            ->with('success', 'Karyawan berhasil ditambahkan.');
    }
    /**
    * Menampilkan form edit data karyawan.
    */
    public function edit(int $id)
    {
    $karyawan = User::query()
        ->where('id', $id)
        ->where('owner_id', auth()->id())
        ->whereIn('role', ['admin', 'kasir'])
        ->firstOrFail();

    return view('pemilik.karyawan.edit', compact('karyawan'));
}

/**
 * Memperbarui data karyawan.
 */
    public function update(Request $request, int $id)
    {
    $karyawan = User::query()
        ->where('id', $id)
        ->where('owner_id', auth()->id())
        ->whereIn('role', ['admin', 'kasir'])
        ->firstOrFail();

    $data = $request->validate([
        'nama_lengkap' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $karyawan->id],
        'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username,' . $karyawan->id],
        'nomor_telepon' => ['nullable', 'string', 'max:20'],
        'role' => ['required', 'in:admin,kasir'],
    ]);

    $karyawan->update($data);

    return redirect()
        ->route('pemilik.karyawan.index')
        ->with('success', 'Data karyawan berhasil diperbarui.');
}
    /**
    * Menghapus akun karyawan milik Pemilik.
    */
    
/**
 * Menghapus akun karyawan milik Pemilik.
 */
    public function destroy(int $id)
    {
    $karyawan = User::query()
        ->where('id', $id)
        ->where('owner_id', auth()->id())
        ->whereIn('role', ['admin', 'kasir'])
        ->firstOrFail();

    $jumlahPesanan = \App\Models\Pesanan::where('user_id', $karyawan->id)->count();

    $jumlahVerifikasi = \App\Models\Pesanan::where('diverifikasi_oleh', $karyawan->id)->count();

    if ($jumlahPesanan > 0 || $jumlahVerifikasi > 0) {
        return redirect()
            ->route('pemilik.karyawan.index')
            ->with('error', 'Akun tidak dapat dihapus karena memiliki riwayat transaksi. Silakan nonaktifkan akun.');
    }

    $karyawan->delete();

    return redirect()
        ->route('pemilik.karyawan.index')
        ->with('success', 'Akun karyawan berhasil dihapus.');
    }

    /**
     * Mengaktifkan atau menonaktifkan akun karyawan.
     */
    public function toggleStatus(int $id)
    {
        $karyawan = User::query()
            ->where('id', $id)
            ->where('owner_id', auth()->id())
            ->whereIn('role', ['admin', 'kasir'])
            ->firstOrFail();

        $karyawan->is_active = !$karyawan->is_active;
        $karyawan->save();

        $status = $karyawan->is_active
            ? 'diaktifkan'
            : 'dinonaktifkan';

        return redirect()
            ->route('pemilik.karyawan.index')
            ->with('success', "Akun karyawan berhasil {$status}.");
    }
}
