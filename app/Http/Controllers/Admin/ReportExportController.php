<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\AdminTransactionsExport;
use App\Models\Pesanan;
use App\Enums\StatusPembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ReportExportController extends Controller
{
    /**
     * Validasi dan ambil periode laporan.
     */
    private function periode(Request $request): array
    {
        $data = $request->validate([
            'tanggal_awal' => ['required', 'date'],
            'tanggal_akhir' => [
                'required',
                'date',
                'after_or_equal:tanggal_awal',
            ],
        ]);

        return [
            Carbon::parse($data['tanggal_awal'])->startOfDay(),
            Carbon::parse($data['tanggal_akhir'])->endOfDay(),
        ];
    }

    /**
     * Export laporan transaksi ke PDF.
     */
    public function pdf(Request $request)
    {
        [$awal, $akhir] = $this->periode($request);

        $pesanan = Pesanan::query()
            ->with(['lapangan', 'penyewa', 'verifikator'])
            ->whereBetween('created_at', [$awal, $akhir])
            ->orderBy('created_at')
            ->get();

        $totalPendapatan = Pesanan::query()
            ->whereBetween('created_at', [$awal, $akhir])
            ->where(
                'status_pembayaran',
                StatusPembayaran::Lunas->value
            )
            ->sum('total_harga');

        $pdf = Pdf::loadView('admin.reports.pdf', [
            'tanggalAwal' => $awal,
            'tanggalAkhir' => $akhir,
            'pesanan' => $pesanan,
            'totalPendapatan' => $totalPendapatan,
            'totalTransaksi' => $pesanan->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-transaksi-' . $awal->format('Y-m-d')
            . '-sampai-' . $akhir->format('Y-m-d') . '.pdf'
        );
    }

    /**
     * Export laporan transaksi ke Excel.
     */
    public function excel(Request $request)
    {
        [$awal, $akhir] = $this->periode($request);

        return Excel::download(
            new AdminTransactionsExport(
                $awal->toDateString(),
                $akhir->toDateString()
            ),
            'laporan-transaksi-' . $awal->format('Y-m-d')
            . '-sampai-' . $akhir->format('Y-m-d') . '.xlsx',
            ExcelFormat::XLSX
        );
    }
}
