<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\VerifikasiPembayaranController;

use App\Http\Controllers\Penyewa\FavoritController;
use App\Http\Controllers\Penyewa\DashboardController as PenyewaDashboardController;
use App\Http\Controllers\Kasir\RiwayatPembayaranController;
use App\Http\Controllers\Penyewa\LapanganController;
use App\Http\Controllers\Penyewa\DetailLapanganController;
use App\Http\Controllers\Penyewa\JadwalController;
use App\Http\Controllers\Penyewa\BookingController;
use App\Http\Controllers\Penyewa\PaymentController;
use App\Http\Controllers\Penyewa\RiwayatController;
use App\Http\Controllers\Penyewa\ProfilController;
use App\Http\Controllers\Penyewa\NotifikasiController;
use App\Http\Controllers\Penyewa\UlasanLapanganController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/register', function () {
    return redirect()->route('login')->with('status', 'Fitur registrasi belum tersedia.');
})->name('register');

/*
|--------------------------------------------------------------------------
| Dashboard Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Dashboard dan Verifikasi Kasir
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {
        Route::get('/dashboard', [KasirDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/riwayat', [RiwayatPembayaranController::class, 'index'])
            ->name('riwayat');

        Route::get('/verifikasi', [VerifikasiPembayaranController::class, 'index'])
            ->name('verifikasi');

        Route::get('/verifikasi/{pesanan}/bukti', [VerifikasiPembayaranController::class, 'lihatBukti'])
            ->name('verifikasi.bukti');

        Route::post('/verifikasi/{pesanan}/setujui', [VerifikasiPembayaranController::class, 'setujui'])
            ->name('verifikasi.setujui');

        Route::post('/verifikasi/{pesanan}/tolak', [VerifikasiPembayaranController::class, 'tolak'])
            ->name('verifikasi.tolak');

        // Konfirmasi pembayaran tunai oleh kasir.
        Route::post('/tunai/{pesanan}/konfirmasi', [VerifikasiPembayaranController::class, 'konfirmasiTunai'])
            ->name('tunai.konfirmasi');
    });

/*
|--------------------------------------------------------------------------
| Dashboard Pemilik
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pemilik'])
    ->prefix('pemilik')
    ->name('pemilik.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return response('Dashboard Pemilik Lapangan belum tersedia.', 501);
        })->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Fitur Penyewa
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Fitur Penyewa
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:penyewa'])
    ->prefix('penyewa')
    ->name('penyewa.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PenyewaDashboardController::class, 'index'])
            ->name('dashboard');

        // Lapangan
        Route::get('/lapangan', [LapanganController::class, 'index'])
            ->name('lapangan');

        Route::get('/lapangan/{id}', [DetailLapanganController::class, 'show'])
            ->name('lapangan.detail');

        Route::get('/lapangan/{id}/jadwal', [JadwalController::class, 'index'])
            ->name('lapangan.jadwal');

        // Favorit
        Route::get('/favorit', [FavoritController::class, 'index'])
            ->name('favorit');

        Route::post('/favorit/{id}/toggle', [FavoritController::class, 'toggle'])
            ->name('favorit.toggle');

        // Booking
        Route::get('/lapangan/{id}/booking', [BookingController::class, 'create'])
            ->name('booking.confirm');

        Route::post('/lapangan/{id}/booking', [BookingController::class, 'store'])
            ->name('booking.store');

        // Pembayaran
        Route::get('/pembayaran/{id}', [PaymentController::class, 'show'])
            ->name('pembayaran.show');

        Route::post('/pembayaran/{id}', [PaymentController::class, 'store'])
            ->name('pembayaran.store');

        Route::post('/pembayaran/{id}/upload', [PaymentController::class, 'upload'])
            ->name('pembayaran.upload');

        // Riwayat
        Route::get('/riwayat', [RiwayatController::class, 'index'])
            ->name('riwayat');

        Route::get('/riwayat/{id}', [RiwayatController::class, 'show'])
            ->name('riwayat.detail');

        Route::post('/riwayat/{id}/batal', [RiwayatController::class, 'cancel'])
            ->name('riwayat.cancel');

        Route::post('/riwayat/{pesanan}/ulasan', [UlasanLapanganController::class, 'store'])
            ->name('riwayat.ulasan.store');

        // Notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');

        Route::post('/notifikasi/{id}/baca', [NotifikasiController::class, 'tandaiDibaca'])
            ->name('notifikasi.baca');

        Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])
            ->name('notifikasi.baca-semua');

        // Profil
        Route::get('/profil', [ProfilController::class, 'index'])
            ->name('profil');

        Route::put('/profil', [ProfilController::class, 'update'])
            ->name('profil.update');

        Route::put('/profil/password', [ProfilController::class, 'updatePassword'])
            ->name('profil.password');
    });
