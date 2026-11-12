<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Menambah ukuran, jenis_lantai, timestamps, dan soft delete pada `lapangan`.
 * Backfill hanya memakai nilai yang sebelumnya di-hardcode di view PHP Native
 * (jenis lantai dan foto untuk lapangan id 1 dan 2). Ukuran TIDAK diisi
 * karena nilainya tidak pernah ada di database maupun kode.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            if (! Schema::hasColumn('lapangan', 'ukuran')) {
                $table->string('ukuran', 50)->nullable();
            }
            if (! Schema::hasColumn('lapangan', 'jenis_lantai')) {
                $table->string('jenis_lantai', 100)->nullable();
            }
            if (! Schema::hasColumn('lapangan', 'created_at')) {
                $table->timestamps();
            }
            if (! Schema::hasColumn('lapangan', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        $lantai = [
            1 => 'Matras Interlock Pro (Sangat Empuk & Anti Slip)',
            2 => 'Rumput Sintetis Monofilament (Mirip Lapangan Asli)',
        ];

        foreach ($lantai as $id => $teks) {
            DB::table('lapangan')->where('id', $id)->whereNull('jenis_lantai')
                ->update(['jenis_lantai' => $teks]);

            // Foto lama: assets/img/lapangan{id}.jpg
            DB::table('lapangan')->where('id', $id)->whereNull('gambar')
                ->update(['gambar' => "lapangan{$id}.jpg"]);
        }
    }

    public function down(): void
    {
        Schema::table('lapangan', function (Blueprint $table) {
            foreach (['ukuran', 'jenis_lantai', 'deleted_at', 'created_at', 'updated_at'] as $kolom) {
                if (Schema::hasColumn('lapangan', $kolom)) {
                    $table->dropColumn($kolom);
                }
            }
        });
    }
};
