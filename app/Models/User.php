<?php

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $fillable = [
        'nama_lengkap',
        'email',
        'username',
        'nomor_telepon',
        'password_hash',
    ];

    protected $hidden = ['password_hash'];

    protected function casts(): array
    {
        return [
            'role' => Role::class,
            'password_hash' => 'hashed',
        ];
    }

    /**
     * Mengambil nama pengguna dari kolom nama_lengkap.
     * Agar relasi verifikator->name dapat menampilkan nama kasir.
     */
    public function getNameAttribute(): ?string
    {
        return $this->nama_lengkap;
    }

    public function getAuthPassword(): string
    {
        return (string) $this->password_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function punyaRole(Role ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    public function lapanganDimiliki(): HasMany
    {
        return $this->hasMany(Lapangan::class, 'owner_id');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'user_id');
    }

    public function favoritLapangan(): HasMany
    {
        return $this->hasMany(FavoritLapangan::class, 'user_id');
    }

    public function pesananDiverifikasi(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'diverifikasi_oleh');
    }
}
