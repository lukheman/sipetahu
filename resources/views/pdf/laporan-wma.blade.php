<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Prediksi WMA Tahu</title>
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
            padding: 8px;
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
        <h2>LAPORAN PREDIKSI PENJUALAN (WMA) PABRIK TAHU</h2>
        <p>Akurasi Perhitungan Keseluruhan</p>
    </div>

    <table class="summary-box">
        <tr>
            <td>
                <span class="summary-title">Rata-rata MAD</span>
                <span class="summary-value">{{ number_format($avgMAD, 2, ',', '.') }}</span><br>
                <small>Mean Absolute Deviation</small>
            </td>
            <td>
                <span class="summary-title">Rata-rata MSE</span>
                <span class="summary-value">{{ number_format($avgMSE, 2, ',', '.') }}</span><br>
                <small>Mean Squared Error</small>
            </td>
            <td>
                <span class="summary-title">Rata-rata MAPE</span>
                <span class="summary-value">{{ number_format($avgMAPE, 2, ',', '.') }}%</span><br>
                <small>Mean Absolute Percentage Error</small>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th>Tahun</th>
                <th>Bulan</th>
                <th>Aktual (Xt)</th>
                <th>Prediksi (WMA)</th>
                <th>Error</th>
                <th>MAD</th>
                <th>MSE</th>
                <th>MAPE (%)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $bulanOptions = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];
            @endphp

            @forelse ($records->sortByDesc(fn($r) => $r->tahun * 100 + $r->bulan) as $record)
                <tr>
                    <td>{{ $record->tahun }}</td>
                    <td>{{ $bulanOptions[$record->bulan] ?? $record->bulan }}</td>
                    <td style="font-weight: bold;">{{ number_format($record->total_penjualan, 2, ',', '.') }}</td>
                    <td>
                        @if($record->hasilPrediksi)
                            {{ number_format($record->hasilPrediksi->wma, 2, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->error, 2, ',', '.') : '-' }}</td>
                    <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->mad, 2, ',', '.') : '-' }}</td>
                    <td>{{ $record->hasilPrediksi ? number_format($record->hasilPrediksi->mse, 2, ',', '.') : '-' }}</td>
                    <td>
                        @if($record->hasilPrediksi)
                            {{ number_format($record->hasilPrediksi->mape, 2, ',', '.') }}%
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data penjualan Tahu.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

</body>
</html>
