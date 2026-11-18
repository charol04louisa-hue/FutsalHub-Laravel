<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        // Pastikan akun masih aktif.
        if (!auth()->user()->is_active) {
             auth()->logout();

             $request->session()->invalidate();
             $request->session()->regenerateToken();

    return redirect()->route('login')->withErrors([
        'username' => 'Akun kamu sedang dinonaktifkan. Hubungi Pemilik Lapangan.',
    ]);
}
        $userRole = auth()->user()->role;

        $userRole = $userRole instanceof \BackedEnum
            ? $userRole->value
            : (string) $userRole;

        if (!in_array($userRole, $roles, true)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
