<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Kasir = 'kasir';
    case Pemilik = 'pemilik';
    case Penyewa = 'penyewa';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Kasir => 'Kasir',
            self::Pemilik => 'Pemilik Lapangan',
            self::Penyewa => 'Penyewa',
        };
    }
}
