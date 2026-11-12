<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah waktu_mulai dan waktu_selesai sebagai GENERATED COLUMN (dihitung otomatis oleh MySQL
 * dari tanggal_sewa + jam_mulai + durasi_jam, sehingga selalu konsisten dan tidak bisa
 * diisi manual), plus index untuk query bentrok jadwal.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('pesanan', 'waktu_mulai')) {
            return;
        }

        DB::statement(
            'ALTER TABLE pesanan
             ADD COLUMN waktu_mulai DATETIME
                 GENERATED ALWAYS AS (TIMESTAMP(tanggal_sewa, jam_mulai)) STORED,
             ADD COLUMN waktu_selesai DATETIME
                 GENERATED ALWAYS AS (DATE_ADD(TIMESTAMP(tanggal_sewa, jam_mulai), INTERVAL durasi_jam HOUR)) STORED,
             ADD INDEX idx_pesanan_slot (id_lapangan, waktu_mulai, waktu_selesai)'
        );
    }

    public function down(): void
    {
        if (Schema::hasColumn('pesanan', 'waktu_mulai')) {
            DB::statement(
                'ALTER TABLE pesanan
                 DROP INDEX idx_pesanan_slot,
                 DROP COLUMN waktu_mulai,
                 DROP COLUMN waktu_selesai'
            );
        }
    }
};
