<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();

            // Penerima notifikasi
            $table->unsignedInteger('user_id');

            // ID pesanan mengikuti tipe INT pada tabel pesanan
            $table->integer('pesanan_id')->nullable();

            // Informasi notifikasi
            $table->string('jenis', 50);
            $table->string('judul', 150);
            $table->text('pesan');

            // Status notifikasi
            $table->boolean('sudah_dibaca')->default(false);

            $table->timestamps();

            // Relasi ke users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            // Relasi ke pesanan
            $table->foreign('pesanan_id')
                ->references('id')
                ->on('pesanan')
                ->nullOnDelete();

            $table->index(['user_id', 'sudah_dibaca']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
