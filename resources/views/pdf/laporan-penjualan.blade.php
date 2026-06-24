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

    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-title">Total Produksi</div>
            <div class="summary-value">{{ number_format($summary['total_produksi'], 0, ',', '.') }}</div>
            <div class="summary-detail">
                @foreach($products as $product)
                    {{ $product->nama_produk }}: {{ number_format($summary['products'][$product->id_produk]['produksi'] ?? 0, 0, ',', '.') }}<br>
                @endforeach
            </div>
        </div>
        
        <div class="summary-box">
            <div class="summary-title">Total Penjualan</div>
            <div class="summary-value">{{ number_format($summary['total_penjualan'], 0, ',', '.') }}</div>
            <div class="summary-detail">
                @foreach($products as $product)
                    {{ $product->nama_produk }}: {{ number_format($summary['products'][$product->id_produk]['penjualan'] ?? 0, 0, ',', '.') }}<br>
                @endforeach
            </div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">Tanggal</th>
                <th colspan="{{ $products->count() + 1 }}">Produksi Tahu</th>
                <th colspan="{{ $products->count() + 1 }}">Penjualan Tahu</th>
            </tr>
            <tr>
                @foreach($products as $product)
                    <th>{{ $product->nama_produk }}</th>
                @endforeach
                <th>Total</th>

                @foreach($products as $product)
                    <th>{{ $product->nama_produk }}</th>
                @endforeach
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
            <tr>
                <td>{{ \Carbon\Carbon::parse($record->tanggal)->format('d/m/Y') }}</td>
                
                @foreach($products as $product)
                    @php $detail = $record->detailPenjualans->firstWhere('id_produk', $product->id_produk); @endphp
                    <td>{{ number_format($detail?->produksi ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="total-column">{{ number_format($record->total_produksi, 0, ',', '.') }}</td>
                
                @foreach($products as $product)
                    @php $detail = $record->detailPenjualans->firstWhere('id_produk', $product->id_produk); @endphp
                    <td>{{ number_format($detail?->penjualan ?? 0, 0, ',', '.') }}</td>
                @endforeach
                <td class="total-column">{{ number_format($record->total_penjualan, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ 1 + ($products->count() * 2) + 2 }}" style="text-align: center; padding: 20px;">
                    Tidak ada data penjualan pada rentang tanggal ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

</body>
</html>
