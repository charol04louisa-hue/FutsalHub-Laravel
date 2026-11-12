
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->timestamp('batas_pembayaran')
                ->nullable()
                ->after('created_at');
        });

        DB::statement("
            ALTER TABLE pesanan
            MODIFY status_booking
            ENUM(
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'dibatalkan',
                'kedaluwarsa'
            )
            NOT NULL DEFAULT 'menunggu_verifikasi'
        ");
    }

    public function down(): void
    {
        DB::table('pesanan')
            ->where('status_booking', 'kedaluwarsa')
            ->update(['status_booking' => 'dibatalkan']);

        DB::statement("
            ALTER TABLE pesanan
            MODIFY status_booking
            ENUM(
                'menunggu_verifikasi',
                'disetujui',
                'ditolak',
                'dibatalkan'
            )
            NOT NULL DEFAULT 'menunggu_verifikasi'
        ");

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('batas_pembayaran');
        });
    }
};
