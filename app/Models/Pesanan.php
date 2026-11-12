<?php

namespace App\Models;

use App\Enums\MetodePembayaran;
use App\Enums\StatusBooking;
use App\Enums\StatusPembayaran;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'nama_pemesan',
        'id_lapangan',
        'tanggal_sewa',
        'jam_mulai',
        'durasi_jam',
        'jenis_pembayaran',
        'bukti_pembayaran',
        'user_id',
        'harga_per_jam',
        'total_harga',
	'batas_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sewa' => 'date:Y-m-d',
            'durasi_jam' => 'integer',
            'jenis_pembayaran' => MetodePembayaran::class,
            'status_booking' => StatusBooking::class,
            'status_pembayaran' => StatusPembayaran::class,
            'harga_per_jam' => 'decimal:2',
            'total_harga' => 'decimal:2',
            'diverifikasi_pada' => 'datetime',
	    'batas_pembayaran' => 'datetime',
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
        ];
    }

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'id_lapangan')->withTrashed();
    }

    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function verifikator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh');
    }

    public function ulasan(): HasOne
    {
        return $this->hasOne(UlasanLapangan::class, 'pesanan_id');
    }

    /** Booking yang menahan jadwal: menunggu verifikasi dan disetujui. */
    public function scopeMemblokirJadwal(Builder $query): Builder
    {
        return $query->whereIn('status_booking', [
            StatusBooking::MenungguVerifikasi->value,
            StatusBooking::Disetujui->value,
        ]);
    }

    /** Memeriksa apakah waktu booking bertabrakan. */
    public function scopeBentrokDengan(
        Builder $query,
        int $idLapangan,
        CarbonInterface $mulai,
        CarbonInterface $selesai
    ): Builder {
        return $query->where('id_lapangan', $idLapangan)
            ->where('waktu_mulai', '<', $selesai)
            ->where('waktu_selesai', '>', $mulai);
    }
}
