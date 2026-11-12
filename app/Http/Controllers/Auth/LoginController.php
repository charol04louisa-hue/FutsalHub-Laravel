<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'in:admin,kasir,pemilik,penyewa'],
        ]);

        // Coba autentikasi menggunakan username dan password.
        if (!Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
        ])) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        // Ambil user yang berhasil login.
        $user = Auth::user();

        // Ambil role sebagai string, baik jika menggunakan Enum maupun string.
        $userRole = $user->role instanceof \BackedEnum
            ? $user->role->value
            : (string) $user->role;

        // Pastikan role yang dipilih sesuai dengan role akun.
        if ($userRole !== $credentials['role']) {
            Auth::logout();

            throw ValidationException::withMessages([
                'username' => 'Jenis akun tidak sesuai dengan akun yang kamu pilih.',
            ]);
        }

        // Perbarui session setelah login berhasil.
        $request->session()->regenerate();

        // Arahkan user ke dashboard sesuai role.
        return match ($userRole) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('kasir.dashboard'),
            'pemilik' => redirect()->route('pemilik.dashboard'),
            'penyewa' => redirect()->route('penyewa.dashboard'),
            default => redirect('/login'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
