<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lapangan extends Model
{
    use SoftDeletes;

    protected $table = 'lapangan';

    protected $fillable = [
        'nama_lapangan',
        'ukuran',
        'jenis_lantai',
        'harga_per_jam',
        'deskripsi',
        'gambar',
    ];

    protected function casts(): array
    {
        return [
            'harga_per_jam' => 'decimal:2',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'id_lapangan');
    }

    public function daftarFavorit(): HasMany
    {
        return $this->hasMany(FavoritLapangan::class, 'lapangan_id');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(UlasanLapangan::class, 'lapangan_id');
    }

    public function scopeMilik(Builder $query, User $user): Builder
    {
        return $query->where('owner_id', $user->id);
    }
}
