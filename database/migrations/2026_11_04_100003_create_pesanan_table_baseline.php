<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BASELINE: meniru struktur tabel `pesanan` dari database lama.
 * Jika tabel sudah ada, migration ini TIDAK melakukan apa-apa.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pesanan')) {
            return;
        }

        Schema::create('pesanan', function (Blueprint $table) {
            $table->integer('id', true); // INT (signed) AUTO_INCREMENT
            $table->string('nama_pemesan', 100);
            $table->integer('id_lapangan'); // INT signed, sama dengan lapangan.id
            $table->date('tanggal_sewa');
            $table->time('jam_mulai');
            $table->integer('durasi_jam');
            $table->string('jenis_pembayaran', 50)->nullable();
            $table->string('bukti_pembayaran', 255)->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->index('id_lapangan', 'id_lapangan');
            $table->index('user_id', 'idx_pesanan_user_id');
            $table->foreign('id_lapangan', 'pesanan_ibfk_1')->references('id')->on('lapangan');
        });
    }

    public function down(): void
    {
        // Sengaja kosong: baseline tidak boleh menghapus tabel yang berisi data lama.
    }
};
