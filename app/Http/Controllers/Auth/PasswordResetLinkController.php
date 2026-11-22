<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'status',
                'Jika email terdaftar, tautan reset password akan dikirim ke email tersebut.'
            );
        }

        if ($status === Password::RESET_LINK_THROTTLED) {
            return back()->withErrors([
                'email' => 'Tunggu sebentar sebelum meminta tautan reset password lagi.',
            ]);
        }

        // Pesan umum agar sistem tidak membocorkan apakah email terdaftar.
        return back()->with(
            'status',
            'Jika email terdaftar, tautan reset password akan dikirim ke email tersebut.'
        );
    }
}