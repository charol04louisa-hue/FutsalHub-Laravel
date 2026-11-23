
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Pemilik - FutsalHub</title>
    <style>
        @page {
            margin: 28px 30px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #26352d;
        }

        .header {
            background-color: #176b45;
            color: white;
            padding: 18px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0 0 5px;
            font-size: 20px;
        }

        .header p {
            margin: 0;
            font-size: 9px;
        }

        .period {
            margin-bottom: 15px;
            font-size: 10px;
        }

        .summary {
            width: 100%;
            margin-bottom: 18px;
        }

        .summary td {
            width: 50%;
            padding: 10px;
            background-color: #eef6f0;
            border: 1px solid #d8e8dc;
        }

        .summary-label {
            color: #607568;
            font-size: 8px;
        }

        .summary-value {
            margin-top: 5px;
            font-size: 15px;
            font-weight: bold;
            color: #176b45;
        }

        table.transactions {
            width: 100%;
            border-collapse: collapse;
        }

        .transactions th {
            background-color: #176b45;
            color: white;
            padding: 7px 5px;
            text-align: left;
            font-size: 8px;
        }

        .transactions td {
            border: 1px solid #dce5df;
            padding: 6px 5px;
            font-size: 7px;
        }

        .transactions tr:nth-child(even) {
            background-color: #f5f8f6;
        }

        .total {
            text-align: right;
            margin-top: 14px;
            font-size: 10px;
            font-weight: bold;
        }

        .footer {
            margin-top: 22px;
            color: #718078;
            font-size: 8px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>FutsalHub</h1>
        <p>Laporan Transaksi dan Pendapatan Pemilik Lapangan</p>
    </div>

    <div class="period">
        <strong>Periode Laporan:</strong>
        {{ \Carbon\Carbon::parse($tanggalAwal)->format('d/m/Y') }}
        -
        {{ \Carbon\Carbon::parse($tanggalAkhir)->format('d/m/Y') }}
        <br>
        <strong>Dicetak pada:</strong>
        {{ now()->format('d/m/Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-label">TOTAL PENDAPATAN LUNAS</div>
                <div class="summary-value">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </div>
            </td>
            <td>
                <div class="summary-label">TOTAL TRANSAKSI</div>
                <div class="summary-value">
                    {{ $totalTransaksi }}
                </div>
            </td>
        </tr>
    </table>

    <table class="transactions">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pemesan</th>
                <th>Lapangan</th>
                <th>Tanggal Transaksi</th>
                <th>Metode</th>
                <th>Total Harga</th>
                <th>Status Booking</th>
                <th>Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pesanan as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->nama_pemesan }}</td>
                    <td>{{ $item->lapangan?->nama_lapangan ?? '-' }}</td>
                    <td>{{ $item->created_at?->format('d/m/Y H:i') ?? '-' }}</td>
                    <td>{{ $item->jenis_pembayaran?->label() ?? '-' }}</td>
                    <td>Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $item->status_booking?->label() ?? '-' }}</td>
                    <td>{{ $item->status_pembayaran?->label() ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">
                        Tidak ada transaksi pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total">
        Total Pendapatan Lunas:
        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
    </div>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem FutsalHub.
    </div>

</body>
</html>
