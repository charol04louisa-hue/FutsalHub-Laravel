<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah status booking, status pembayaran, data verifikasi kasir, snapshot harga,
 * dan timestamps pada `pesanan`.
 *
 * Backfill data lama (disetujui pemilik proyek):
 *  - Pesanan lama diberi status menunggu_verifikasi (booking dan pembayaran), BUKAN
 *    otomatis lunas, karena belum pernah diverifikasi kasir.
 *  - harga_per_jam dan total_harga dihitung dari harga lapangan SAAT INI x durasi.
 *    Ini PERKIRAAN, karena snapshot harga asli tidak pernah disimpan.
 * Backfill hanya berjalan saat kolom baru benar-benar ditambahkan (tidak menimpa status
 * jika migration dijalankan ulang).
 */
return new class extends Migration
{
    public function up(): void
    {
        $tambahStatus = ! Schema::hasColumn('pesanan', 'status_booking');
        $tambahHarga = ! Schema::hasColumn('pesanan', 'total_harga');

        Schema::table('pesanan', function (Blueprint $table) use ($tambahStatus, $tambahHarga) {
            if ($tambahStatus) {
                $table->enum('status_booking', ['menunggu_verifikasi', 'disetujui', 'ditolak'])
                    ->default('menunggu_verifikasi');
                $table->enum('status_pembayaran', ['menunggu_verifikasi', 'lunas', 'belum_bayar', 'ditolak'])
                    ->default('belum_bayar');
                $table->text('alasan_penolakan')->nullable();
                $table->unsignedInteger('diverifikasi_oleh')->nullable();
                $table->timestamp('diverifikasi_pada')->nullable();
                $table->index('status_booking', 'idx_pesanan_status_booking');
            }

            if ($tambahHarga) {
                $table->decimal('harga_per_jam', 10, 2)->nullable();
                $table->decimal('total_harga', 12, 2)->nullable();
            }

            if (! Schema::hasColumn('pesanan', 'created_at')) {
                $table->timestamps();
            }
        });

        if ($tambahStatus) {
            DB::table('pesanan')->update([
                'status_booking' => 'menunggu_verifikasi',
                'status_pembayaran' => 'menunggu_verifikasi',
            ]);
        }

        if ($tambahHarga) {
            DB::statement(
                'UPDATE pesanan p JOIN lapangan l ON l.id = p.id_lapangan
                 SET p.harga_per_jam = l.harga_per_jam,
                     p.total_harga = l.harga_per_jam * p.durasi_jam
                 WHERE p.total_harga IS NULL'
            );

            DB::statement(
                'ALTER TABLE pesanan
                 MODIFY harga_per_jam DECIMAL(10,2) NOT NULL,
                 MODIFY total_harga DECIMAL(12,2) NOT NULL'
            );
        }
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            if (Schema::hasColumn('pesanan', 'status_booking')) {
                $table->dropIndex('idx_pesanan_status_booking');
            }
            foreach ([
                'status_booking', 'status_pembayaran', 'alasan_penolakan', 'diverifikasi_oleh',
                'diverifikasi_pada', 'harga_per_jam', 'total_harga', 'created_at', 'updated_at',
            ] as $kolom) {
                if (Schema::hasColumn('pesanan', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};
