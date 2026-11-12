<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan_lapangan', function (Blueprint $table) {
            $table->id();

            $table->integer('pesanan_id');
            $table->unsignedInteger('user_id');
            $table->integer('lapangan_id');

            $table->unsignedTinyInteger('rating');
            $table->text('ulasan')->nullable();

            $table->timestamps();

            $table->unique('pesanan_id');

            $table->foreign('pesanan_id')
                ->references('id')
                ->on('pesanan')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();

            $table->foreign('lapangan_id')
                ->references('id')
                ->on('lapangan')
                ->cascadeOnDelete();

            $table->index('lapangan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan_lapangan');
    }
};
