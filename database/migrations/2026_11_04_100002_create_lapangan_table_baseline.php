<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BASELINE: meniru struktur tabel `lapangan` dari database lama.
 * Jika tabel sudah ada, migration ini TIDAK melakukan apa-apa.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('lapangan')) {
            return;
        }

        Schema::create('lapangan', function (Blueprint $table) {
            $table->integer('id', true); // INT (signed) AUTO_INCREMENT, sama dengan tabel lama
            $table->string('nama_lapangan', 100);
            $table->decimal('harga_per_jam', 10, 2)->default(0);
            $table->text('deskripsi')->nullable();
            $table->string('gambar', 255)->nullable();
            $table->unsignedInteger('owner_id')->nullable();

            $table->index('owner_id', 'idx_lapangan_owner_id');
        });
    }

    public function down(): void
    {
        // Sengaja kosong: baseline tidak boleh menghapus tabel yang berisi data lama.
    }
};
