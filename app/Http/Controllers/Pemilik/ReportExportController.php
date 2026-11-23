<?php

namespace App\Http\Controllers\Pemilik;

use App\Http\Controllers\Controller;
use App\Exports\PemilikTransactionsExport;
use App\Models\Pesanan;
use App\Enums\StatusPembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ReportExportController extends Controller
{
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

    public function pdf(Request $request)
    {
        [$awal, $akhir] = $this->periode($request);

        $ownerId = $request->user()->id;

        $pesanan = Pesanan::query()
            ->with(['lapangan', 'penyewa', 'verifikator'])
            ->whereHas('lapangan', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->whereBetween('created_at', [$awal, $akhir])
            ->orderBy('created_at')
            ->get();

        $totalPendapatan = Pesanan::query()
            ->whereHas('lapangan', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->whereBetween('created_at', [$awal, $akhir])
            ->where(
                'status_pembayaran',
                StatusPembayaran::Lunas->value
            )
            ->sum('total_harga');

        $pdf = Pdf::loadView('pemilik.reports.pdf', [
            'tanggalAwal' => $awal,
            'tanggalAkhir' => $akhir,
            'pesanan' => $pesanan,
            'totalPendapatan' => $totalPendapatan,
            'totalTransaksi' => $pesanan->count(),
        ])->setPaper('a4', 'landscape');

        return $pdf->download(
            'laporan-transaksi-pemilik-' .
            $awal->format('Y-m-d') .
            '-sampai-' .
            $akhir->format('Y-m-d') .
            '.pdf'
        );
    }

    public function excel(Request $request)
    {
        [$awal, $akhir] = $this->periode($request);

        return Excel::download(
            new PemilikTransactionsExport(
                $awal->toDateString(),
                $akhir->toDateString(),
                $request->user()->id
            ),
            'laporan-transaksi-pemilik-' .
            $awal->format('Y-m-d') .
            '-sampai-' .
            $akhir->format('Y-m-d') .
            '.xlsx',
            ExcelFormat::XLSX
        );
    }
}
