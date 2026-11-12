<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BASELINE: meniru struktur tabel `users` dari database lama (db_futsal).
 * Jika tabel sudah ada (database lama), migration ini TIDAK melakukan apa-apa.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id'); // INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->string('nama_lengkap', 120);
            $table->string('email', 190);
            $table->string('username', 50);
            $table->string('nomor_telepon', 30)->nullable();
            $table->string('password_hash', 255);
            $table->enum('role', ['admin', 'pemilik', 'penyewa'])->default('penyewa');
            $table->timestamp('created_at')->useCurrent();

            $table->unique('email', 'email');
            $table->unique('username', 'username');
        });
    }

    public function down(): void
    {
        // Sengaja kosong: baseline tidak boleh menghapus tabel yang berisi data lama.
    }
};
