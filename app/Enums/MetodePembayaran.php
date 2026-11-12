<?php

namespace App\Enums;

/**
 * Nilai string sama persis dengan data lama di kolom pesanan.jenis_pembayaran
 * ('QRIS' dan 'Transfer Bank'), ditambah 'Tunai'.
 */
enum MetodePembayaran: string
{
    case Qris = 'QRIS';
    case TransferBank = 'Transfer Bank';
    case Tunai = 'Tunai';

    public function label(): string
    {
        return match ($this) {
            self::Qris => 'QRIS',
            self::TransferBank => 'Transfer Bank',
            self::Tunai => 'Tunai',
        };
    }

    /** Transfer dan QRIS wajib bukti pembayaran; tunai tidak. */
    public function butuhBukti(): bool
    {
        return $this !== self::Tunai;
    }
}
