<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah role 'kasir' ke ENUM users.role (ditambahkan di AKHIR daftar, sehingga
 * data lama tidak berubah) dan kolom updated_at. Aman dijalankan ulang.
 */
return new class extends Migration
{
    public function up(): void
    {
        $kolom = DB::selectOne(
            "SELECT COLUMN_TYPE AS tipe FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = 'role'"
        );

        if ($kolom && ! str_contains($kolom->tipe, "'kasir'")) {
            DB::statement(
                "ALTER TABLE users MODIFY role ENUM('admin','pemilik','penyewa','kasir')
                 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'penyewa'"
            );
        }

        if (! Schema::hasColumn('users', 'updated_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (DB::table('users')->where('role', 'kasir')->exists()) {
            throw new RuntimeException('Rollback dibatalkan: masih ada akun dengan role kasir.');
        }

        DB::statement(
            "ALTER TABLE users MODIFY role ENUM('admin','pemilik','penyewa')
             CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'penyewa'"
        );

        if (Schema::hasColumn('users', 'updated_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('updated_at');
            });
        }
    }
};
