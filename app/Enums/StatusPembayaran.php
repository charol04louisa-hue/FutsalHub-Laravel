<?php

namespace App\Enums;

enum StatusPembayaran: string
{
    case MenungguVerifikasi = 'menunggu_verifikasi';
    case Lunas = 'lunas';
    case BelumBayar = 'belum_bayar';
    case Ditolak = 'ditolak';

    public function label(): string
    {
        return match ($this) {
            self::MenungguVerifikasi => 'Menunggu Verifikasi',
            self::Lunas => 'Lunas',
            self::BelumBayar => 'Belum Bayar',
            self::Ditolak => 'Ditolak',
        };
    }
}
