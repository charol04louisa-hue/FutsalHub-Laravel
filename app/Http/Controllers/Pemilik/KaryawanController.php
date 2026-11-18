<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Models\User;
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
}
