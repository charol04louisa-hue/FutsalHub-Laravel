
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ReportExportController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\VerifikasiPembayaranController;
use App\Http\Controllers\Kasir\ProfilController as KasirProfilController;
use App\Http\Controllers\Kasir\RiwayatPembayaranController;
use App\Http\Controllers\Pemilik\ReportExportController as PemilikReportExportController;
use App\Http\Controllers\Pemilik\KaryawanController;
use App\Http\Controllers\Pemilik\DashboardController as PemilikDashboardController;
use App\Http\Controllers\Pemilik\LapanganController as PemilikLapanganController;
use App\Http\Controllers\Admin\LapanganController as AdminLapanganController;
use App\Http\Controllers\Penyewa\FavoritController;
use App\Http\Controllers\Penyewa\DashboardController as PenyewaDashboardController;
use App\Http\Controllers\Penyewa\LapanganController as PenyewaLapanganController;
use App\Http\Controllers\Penyewa\DetailLapanganController;
use App\Http\Controllers\Penyewa\JadwalController;
use App\Http\Controllers\Penyewa\BookingController;
use App\Http\Controllers\Penyewa\PaymentController;
use App\Http\Controllers\Penyewa\RiwayatController;
use App\Http\Controllers\Penyewa\ProfilController;
use App\Http\Controllers\Penyewa\NotifikasiController;
use App\Http\Controllers\Penyewa\UlasanLapanganController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');
// Halaman publik
Route::get('/lapangan', [PublicController::class, 'lapangan'])
    ->name('public.lapangan');
Route::get('/jadwal-hari-ini', [PublicController::class, 'jadwal'])
    ->name('public.jadwal');

/*
|--------------------------------------------------------------------------
| Autentikasi
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
// Password Reset
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
    ->middleware('guest')
    ->name('password.request');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware(['guest', 'throttle:5,1'])
    ->name('password.update');
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'register'])
    ->middleware('throttle:5,1')
    ->name('register.store');

/*
|--------------------------------------------------------------------------
| Dashboard Admin dan Export Laporan
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Export Laporan
        Route::get('/export/pdf', [ReportExportController::class, 'pdf'])
            ->name('export.pdf');

        Route::get('/export/excel', [ReportExportController::class, 'excel'])
            ->name('export.excel');

        // Manajemen Lapangan
        Route::get('/lapangan', [AdminLapanganController::class, 'index'])
            ->name('lapangan.index');

        Route::get('/lapangan/create', [AdminLapanganController::class, 'create'])
            ->name('lapangan.create');

        Route::post('/lapangan', [AdminLapanganController::class, 'store'])
            ->name('lapangan.store');

        Route::get('/lapangan/{id}/edit', [AdminLapanganController::class, 'edit'])
            ->name('lapangan.edit');

        Route::put('/lapangan/{id}', [AdminLapanganController::class, 'update'])
            ->name('lapangan.update');

        Route::delete('/lapangan/{id}', [AdminLapanganController::class, 'destroy'])
            ->name('lapangan.destroy');
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

        Route::post('/tunai/{pesanan}/konfirmasi', [VerifikasiPembayaranController::class, 'konfirmasiTunai'])
            ->name('tunai.konfirmasi');
                // Profil Kasir
        Route::get('/profil', [KasirProfilController::class, 'index'])
            ->name('profil');

        Route::put('/profil', [KasirProfilController::class, 'update'])
            ->name('profil.update');

        Route::put('/profil/password', [KasirProfilController::class, 'updatePassword'])
            ->name('profil.password');
    });

/*
|--------------------------------------------------------------------------
| Dashboard dan Manajemen Pemilik
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pemilik'])
    ->prefix('pemilik')
    ->name('pemilik.')
    ->group(function () {
        //PDF dan Excel Export
        Route::get('/export/pdf', [PemilikReportExportController::class, 'pdf'])
             ->name('export.pdf');

        Route::get('/export/excel', [PemilikReportExportController::class, 'excel'])
             ->name('export.excel');

        // Dashboard
        Route::get('/dashboard', [PemilikDashboardController::class, 'index'])
            ->name('dashboard');

        // Manajemen Lapangan
        Route::get('/lapangan', [PemilikLapanganController::class, 'index'])
            ->name('lapangan.index');
        Route::get('/lapangan/{id}', [PemilikLapanganController::class, 'show'])
            ->name('lapangan.show');
            
        // Manajemen Karyawan
        Route::get('/karyawan', [KaryawanController::class, 'index'])
            ->name('karyawan.index');

        Route::post('/karyawan', [KaryawanController::class, 'store'])
            ->name('karyawan.store');

        Route::get('/karyawan/create', [KaryawanController::class, 'create'])
            ->name('karyawan.create');

        Route::patch('/karyawan/{id}/status', [KaryawanController::class, 'toggleStatus'])
            ->name('karyawan.toggle-status');

        Route::get('/karyawan/{id}/edit', [KaryawanController::class, 'edit'])
            ->name('karyawan.edit');

        Route::put('/karyawan/{id}', [KaryawanController::class, 'update'])
            ->name('karyawan.update');

        Route::delete('/karyawan/{id}', [KaryawanController::class, 'destroy'])
            ->name('karyawan.destroy');
    });

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
        Route::get('/lapangan', [PenyewaLapanganController::class, 'index'])
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
