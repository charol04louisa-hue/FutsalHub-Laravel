<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class BookingController extends Controller
{
    // Menampilkan halaman konfirmasi booking.
    public function create(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date_format:Y-m-d|after_or_equal:today',
            'jam' => [
                'required',
                'date_format:H:i',
                'in:08:00,09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00,18:00,19:00,20:00,21:00,22:00',
            ],
            'durasi' => 'required|integer|in:1,2,3',
        ]);

        $lapangan = Lapangan::findOrFail($id);
        $tanggal = $validated['tanggal'];
        $jam = $validated['jam'];
        $durasi = (int) $validated['durasi'];

        $mulai = Carbon::parse($tanggal . ' ' . $jam);
        $selesai = $mulai->copy()->addHours($durasi);

        if ($mulai->lte(now())) {
            throw ValidationException::withMessages([
                'jam' => 'Jam bermain tersebut sudah lewat. Silakan pilih jadwal lain.',
            ]);
        }

        $jamTutup = Carbon::parse($tanggal . ' 23:00:00');
        if ($selesai->gt($jamTutup)) {
             throw ValidationException::withMessages([
        'durasi' => 'Durasi melewati batas jam operasional (maksimal pukul 23.00).',
    ]);
}

        $bentrok = DB::table('pesanan')
            ->where('id_lapangan', $lapangan->id)
            ->whereIn('status_booking', [
                'menunggu_verifikasi',
                'disetujui',
            ])
            ->where('waktu_mulai', '<', $selesai->format('Y-m-d H:i:s'))
            ->where('waktu_selesai', '>', $mulai->format('Y-m-d H:i:s'))
            ->exists();

        if ($bentrok) {
            throw ValidationException::withMessages([
                'jam' => 'Jadwal tersebut sudah dipesan. Silakan pilih jam lain.',
            ]);
        }

        $hargaPerJam = $lapangan->harga_per_jam;
        $totalHarga = $hargaPerJam * $durasi;

        return view('penyewa.lapangan.konfirmasi', compact(
            'lapangan',
            'tanggal',
            'jam',
            'durasi',
            'selesai',
            'hargaPerJam',
            'totalHarga'
        ));
    }

    // Menyimpan booking baru.
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date_format:Y-m-d|after_or_equal:today',
            'jam' => [
                'required',
                'date_format:H:i',
                'in:08:00,09:00,10:00,11:00,12:00,13:00,14:00,15:00,16:00,17:00,18:00,19:00,20:00,21:00,22:00',
            ],
            'durasi' => 'required|integer|in:1,2,3',
        ]);

        $bookingId = DB::transaction(function () use ($validated, $id) {
            $lapangan = Lapangan::whereKey($id)
                ->lockForUpdate()
                ->firstOrFail();

            $tanggal = $validated['tanggal'];
            $jam = $validated['jam'];
            $durasi = (int) $validated['durasi'];

            $mulai = Carbon::parse($tanggal . ' ' . $jam);
            $selesai = $mulai->copy()->addHours($durasi);

            // Validasi ulang jadwal.
            if ($mulai->lte(now())) {
                throw ValidationException::withMessages([
                    'jam' => 'Jam bermain tersebut sudah lewat. Silakan pilih jadwal lain.',
                ]);
            }

           $jamTutup = Carbon::parse($tanggal . ' 23:00:00');

            if ($selesai->gt($jamTutup)) {
                 throw ValidationException::withMessages([
              'durasi' => 'Durasi melewati batas jam operasional (maksimal pukul 23.00).',
            ]);
        }

            $bentrok = DB::table('pesanan')
                ->where('id_lapangan', $lapangan->id)
                ->whereIn('status_booking', [
                    'menunggu_verifikasi',
                    'disetujui',
                ])
                ->where('waktu_mulai', '<', $selesai->format('Y-m-d H:i:s'))
                ->where('waktu_selesai', '>', $mulai->format('Y-m-d H:i:s'))
                ->exists();

            if ($bentrok) {
                throw ValidationException::withMessages([
                    'jam' => 'Maaf, jadwal tersebut baru saja dipesan. Silakan pilih jam lain.',
                ]);
            }

            $user = Auth::user();
            $hargaPerJam = $lapangan->harga_per_jam;
            $totalHarga = $hargaPerJam * $durasi;

            // Deadline ditentukan setelah penyewa memilih metode pembayaran.
            $bookingId = DB::table('pesanan')->insertGetId([
                'nama_pemesan' => $user->nama_lengkap,
                'id_lapangan' => $lapangan->id,
                'tanggal_sewa' => $tanggal,
                'jam_mulai' => $jam,
                'durasi_jam' => $durasi,
                'user_id' => $user->id,
                'status_booking' => 'menunggu_verifikasi',
                'status_pembayaran' => 'belum_bayar',
                'harga_per_jam' => $hargaPerJam,
                'total_harga' => $totalHarga,
                'created_at' => now(),
                'batas_pembayaran' => null,
                'updated_at' => now(),
            ]);

            Notifikasi::create([
                'user_id' => $user->id,
                'pesanan_id' => $bookingId,
                'jenis' => 'pembayaran',
                'judul' => 'Booking Berhasil',
                'pesan' => 'Booking berhasil dibuat. Silakan lanjutkan pembayaran.',
                'sudah_dibaca' => false,
            ]);

            return $bookingId;
        });

        return redirect()
            ->route('penyewa.pembayaran.show', $bookingId)
            ->with('success', 'Pemesanan berhasil dibuat! Silakan lanjutkan proses pembayaran.');
    }
}
