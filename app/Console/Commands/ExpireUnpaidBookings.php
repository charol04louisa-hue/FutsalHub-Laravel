<?php

namespace App\Console\Commands;

use App\Enums\MetodePembayaran;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExpireUnpaidBookings extends Command
{
    protected $signature = 'bookings:expire-unpaid';

    protected $description = 'Mengakhiri booking yang melewati batas pembayaran';

    public function handle(): int
    {
        $jumlahKedaluwarsa = 0;

        Pesanan::query()
            ->where('status_booking', StatusBooking::MenungguVerifikasi)
            ->whereNotNull('batas_pembayaran')
            ->where('batas_pembayaran', '<=', now())
            ->select('id')
            ->chunkById(100, function ($pesananList) use (&$jumlahKedaluwarsa) {
                foreach ($pesananList as $item) {
                    $berhasil = DB::transaction(function () use ($item) {
                        $pesanan = Pesanan::whereKey($item->id)
                            ->lockForUpdate()
                            ->first();

                        if (!$pesanan) {
                            return false;
                        }

                        if (
                            $pesanan->status_booking !== StatusBooking::MenungguVerifikasi ||
                            !$pesanan->batas_pembayaran ||
                            $pesanan->batas_pembayaran->isFuture()
                        ) {
                            return false;
                        }

                        // Jangan kedaluwarsakan pembayaran yang sedang diperiksa kasir.
                        if (
                            $pesanan->status_pembayaran === StatusPembayaran::MenungguVerifikasi &&
                            $pesanan->bukti_pembayaran
                        ) {
                            return false;
                        }

                        // Booking hanya boleh kedaluwarsa jika belum lunas.
                        if (
                            $pesanan->status_pembayaran === StatusPembayaran::Lunas
                        ) {
                            return false;
                        }

                        $pesanan->status_booking = StatusBooking::Kedaluwarsa;
                        $pesanan->save();

                        Notifikasi::create([
                            'user_id' => $pesanan->user_id,
                            'pesanan_id' => $pesanan->id,
                            'jenis' => 'pembayaran',
                            'judul' => 'Booking Kedaluwarsa',
                            'pesan' => 'Booking #' . $pesanan->id .
                                ' kedaluwarsa karena batas pembayaran telah habis.',
                            'sudah_dibaca' => false,
                        ]);

                        return true;
                    });

                    if ($berhasil) {
                        $jumlahKedaluwarsa++;
                    }
                }
            });

        $this->info(
            $jumlahKedaluwarsa . ' booking berhasil diperiksa dan dikedaluwarsakan.'
        );

        return self::SUCCESS;
    }
}
