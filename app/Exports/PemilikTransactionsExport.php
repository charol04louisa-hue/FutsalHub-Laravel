<?php

namespace App\Exports;

use App\Models\Pesanan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Collection;

class PemilikTransactionsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithColumnFormatting
{
    protected string $tanggalAwal;
    protected string $tanggalAkhir;
    protected int $ownerId;

    public function __construct(
        string $tanggalAwal,
        string $tanggalAkhir,
        int $ownerId
    ) {
        $this->tanggalAwal = $tanggalAwal;
        $this->tanggalAkhir = $tanggalAkhir;
        $this->ownerId = $ownerId;
    }

    public function collection(): Collection
    {
        return Pesanan::query()
            ->with(['lapangan', 'penyewa', 'verifikator'])
            ->whereHas('lapangan', function ($query) {
                $query->where('owner_id', $this->ownerId);
            })
            ->whereBetween('created_at', [
                $this->tanggalAwal . ' 00:00:00',
                $this->tanggalAkhir . ' 23:59:59',
            ])
            ->orderBy('created_at')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Transaksi',
            'Nama Pemesan',
            'Lapangan',
            'Tanggal Sewa',
            'Jam Mulai',
            'Durasi (Jam)',
            'Tanggal Transaksi',
            'Metode Pembayaran',
            'Total Harga',
            'Status Booking',
            'Status Pembayaran',
            'Diverifikasi Oleh',
        ];
    }

    public function map($pesanan): array
    {
        return [
            $pesanan->id,
            $pesanan->nama_pemesan,
            $pesanan->lapangan?->nama_lapangan ?? '-',
            $pesanan->tanggal_sewa?->format('d-m-Y') ?? '-',
            $pesanan->jam_mulai,
            $pesanan->durasi_jam,
            $pesanan->created_at?->format('d-m-Y H:i'),
            $pesanan->jenis_pembayaran?->label() ?? '-',
            (float) $pesanan->total_harga,
            $pesanan->status_booking?->label() ?? '-',
            $pesanan->status_pembayaran?->label() ?? '-',
            $pesanan->verifikator?->nama_lengkap ?? '-',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,
        ];
    }
}
