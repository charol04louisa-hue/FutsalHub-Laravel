<?php

namespace App\Enums;

enum StatusBooking: string
{
    case MenungguVerifikasi = 'menunggu_verifikasi';
    case Disetujui = 'disetujui';
    case Ditolak = 'ditolak';
    case Dibatalkan = 'dibatalkan';
    case Kedaluwarsa = 'kedaluwarsa';

    public function label(): string
    {
        return match ($this) {
            self::MenungguVerifikasi => 'Menunggu Verifikasi',
            self::Disetujui => 'Disetujui',
            self::Ditolak => 'Ditolak',
            self::Dibatalkan => 'Dibatalkan',
            self::Kedaluwarsa => 'Kedaluwarsa',
        };
    }

    /** Status yang menahan jadwal lapangan. */
    public function memblokirJadwal(): bool
    {
        return !in_array($this, [
            self::Ditolak,
            self::Dibatalkan,
            self::Kedaluwarsa,
        ], true);
    }
}
