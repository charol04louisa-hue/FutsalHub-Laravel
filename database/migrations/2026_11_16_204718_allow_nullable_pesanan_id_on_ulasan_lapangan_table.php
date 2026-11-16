
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ulasan_lapangan', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->integer('pesanan_id')->nullable()->change();

            $table->foreign('pesanan_id')
                ->references('id')
                ->on('pesanan')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ulasan_lapangan', function (Blueprint $table) {
            $table->dropForeign(['pesanan_id']);
            $table->integer('pesanan_id')->nullable(false)->change();

            $table->foreign('pesanan_id')
                ->references('id')
                ->on('pesanan')
                ->cascadeOnDelete();
        });
    }
};
