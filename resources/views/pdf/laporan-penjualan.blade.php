<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Tahu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #555;
        }
        .summary-box {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .summary-box td {
            width: 33.33%;
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            background-color: #f9f9f9;
        }
        .summary-title {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #aaa;
            padding: 6px;
            text-align: center;
        }
        table.data-table th {
            background-color: #eee;
            font-weight: bold;
        }
        .text-start {
            text-align: left !important;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN PENJUALAN PABRIK TAHU</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</p>
    </div>

    <table class="summary-box">
        <tr>
            <td>
                <span class="summary-title">Total Produksi</span>
                <span class="summary-value">{{ number_format($summary['total_produksi'], 0, ',', '.') }}</span><br>
                <small>Kecil: {{ number_format($summary['produksi_kecil'], 0, ',', '.') }} | Besar: {{ number_format($summary['produksi_besar'], 0, ',', '.') }}</small>
            </td>
            <td>
                <span class="summary-title">Total Penjualan</span>
                <span class="summary-value">{{ number_format($summary['total_penjualan'], 0, ',', '.') }}</span><br>
                <small>Kecil: {{ number_format($summary['penjualan_kecil'], 0, ',', '.') }} | Besar: {{ number_format($summary['penjualan_besar'], 0, ',', '.') }}</small>
            </td>
            <td>
                <span class="summary-title">Total Tahu Kembali</span>
                <span class="summary-value">{{ number_format($summary['kembali_kecil'] + $summary['kembali_besar'], 0, ',', '.') }}</span><br>
                <small>Kecil: {{ number_format($summary['kembali_kecil'], 0, ',', '.') }} | Besar: {{ number_format($summary['kembali_besar'], 0, ',', '.') }}</small>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">Tanggal</th>
                <th colspan="3">Produksi Tahu</th>
                <th colspan="3">Penjualan Tahu</th>
                <th colspan="2">Tahu Kembali</th>
            </tr>
            <tr>
                <th>Kecil</th>
                <th>Besar</th>
                <th>Total</th>
                <th>Kecil</th>
                <th>Besar</th>
                <th>Total</th>
                <th>Kecil</th>
                <th>Besar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
            <tr>
                <td class="text-start">{{ \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') }}</td>
                <td>{{ number_format($record->produksi_tahu_kecil, 0, ',', '.') }}</td>
                <td>{{ number_format($record->produksi_tahu_besar, 0, ',', '.') }}</td>
                <td style="font-weight: bold; background-color: #f4f4f4;">{{ number_format($record->total_produksi, 0, ',', '.') }}</td>
                <td>{{ number_format($record->penjualan_tahu_kecil, 0, ',', '.') }}</td>
                <td>{{ number_format($record->penjualan_tahu_besar, 0, ',', '.') }}</td>
                <td style="font-weight: bold; background-color: #eef9ee;">{{ number_format($record->total_penjualan, 0, ',', '.') }}</td>
                <td>{{ number_format($record->tahu_kembali_kecil, 0, ',', '.') }}</td>
                <td>{{ number_format($record->tahu_kembali_besar, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9">Tidak ada data penjualan pada rentang tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

</body>
</html>
