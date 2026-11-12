<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    /**
     * Menampilkan profil penyewa.
     */
    public function index()
    {
        $user = auth()->user();

        return view('penyewa.profil', compact('user'));
    }

    /**
     * Memperbarui informasi profil penyewa.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'nama_lengkap' => [
                'required',
                'string',
                'max:120',
            ],
            'email' => [
                'required',
                'email',
                'max:190',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'nomor_telepon' => [
                'nullable',
                'string',
                'max:30',
            ],
        ]);

        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->email = $validated['email'];
        $user->nomor_telepon = $validated['nomor_telepon'] ?? null;

        $user->save();

        return redirect()
            ->route('penyewa.profil')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Mengganti password penyewa.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'password_lama' => [
                'required',
                'string',
            ],
            'password_baru' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = auth()->user();

        if (!Hash::check(
            $validated['password_lama'],
            $user->password_hash
        )) {
            throw ValidationException::withMessages([
                'password_lama' => 'Password lama tidak sesuai.',
            ]);
        }

        $user->password_hash = $validated['password_baru'];
        $user->save();

        return redirect()
            ->route('penyewa.profil')
            ->with('success', 'Password berhasil diperbarui.');
    }
}
