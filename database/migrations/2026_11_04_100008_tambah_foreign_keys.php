<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah foreign key yang belum ada di database lama:
 *   pesanan.user_id, pesanan.diverifikasi_oleh, lapangan.owner_id -> users.id (ON DELETE SET NULL).
 *
 * SEBELUM menambah FK, migration memeriksa data yatim. Jika ada, migration BERHENTI
 * dengan pesan yang menyebut id-nya dan tidak mengubah apa pun. Data tidak dihapus
 * atau diubah otomatis.
 */
return new class extends Migration
{
    public function up(): void
    {
        $yatim = [
            'pesanan.user_id' => DB::table('pesanan as p')
                ->leftJoin('users as u', 'u.id', '=', 'p.user_id')
                ->whereNotNull('p.user_id')->whereNull('u.id')->pluck('p.id')->all(),
            'pesanan.diverifikasi_oleh' => DB::table('pesanan as p')
                ->leftJoin('users as u', 'u.id', '=', 'p.diverifikasi_oleh')
                ->whereNotNull('p.diverifikasi_oleh')->whereNull('u.id')->pluck('p.id')->all(),
            'lapangan.owner_id' => DB::table('lapangan as l')
                ->leftJoin('users as u', 'u.id', '=', 'l.owner_id')
                ->whereNotNull('l.owner_id')->whereNull('u.id')->pluck('l.id')->all(),
        ];

        $pesan = [];
        foreach ($yatim as $kolom => $ids) {
            if ($ids !== []) {
                $pesan[] = "{$kolom} merujuk user yang tidak ada (id baris: " . implode(', ', $ids) . ')';
            }
        }
        if ($pesan !== []) {
            throw new RuntimeException(
                "Data yatim ditemukan, foreign key TIDAK ditambahkan. Perbaiki data secara manual lalu jalankan migrate lagi:\n- "
                . implode("\n- ", $pesan)
            );
        }

        if (! $this->fkAda('pesanan_user_id_foreign')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->foreign('user_id', 'pesanan_user_id_foreign')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }

        if (! $this->fkAda('pesanan_diverifikasi_oleh_foreign')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->foreign('diverifikasi_oleh', 'pesanan_diverifikasi_oleh_foreign')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }

        if (! $this->fkAda('lapangan_owner_id_foreign')) {
            Schema::table('lapangan', function (Blueprint $table) {
                $table->foreign('owner_id', 'lapangan_owner_id_foreign')
                    ->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if ($this->fkAda('pesanan_user_id_foreign')) {
            Schema::table('pesanan', fn (Blueprint $t) => $t->dropForeign('pesanan_user_id_foreign'));
        }
        if ($this->fkAda('pesanan_diverifikasi_oleh_foreign')) {
            Schema::table('pesanan', fn (Blueprint $t) => $t->dropForeign('pesanan_diverifikasi_oleh_foreign'));
        }
        if ($this->fkAda('lapangan_owner_id_foreign')) {
            Schema::table('lapangan', fn (Blueprint $t) => $t->dropForeign('lapangan_owner_id_foreign'));
        }
    }

    private function fkAda(string $nama): bool
    {
        $hasil = DB::selectOne(
            "SELECT COUNT(*) AS n FROM information_schema.TABLE_CONSTRAINTS
             WHERE CONSTRAINT_SCHEMA = DATABASE() AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'",
            [$nama]
        );

        return (int) $hasil->n > 0;
    }
};
