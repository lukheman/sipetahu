<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Produk;
use App\Models\DataPenjualan;
use App\Services\WeightedMovingAverage;

#[Title('Dashboard')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_produk' => Produk::count(),
            'total_data' => DataPenjualan::count(),
            'total_volume' => DataPenjualan::sum('total_penjualan'),
            'avg_mape' => \App\Models\HasilPrediksi::avg('mape') ?? 0,
        ];

        $produkTahu = Produk::where('nama_produk', 'Tahu')->first();
        $chartLabels = [];
        $chartActual = [];
        $chartWma = [];

        $bulanOptions = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'Mei',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Agu',
            9 => 'Sep',
            10 => 'Okt',
            11 => 'Nov',
            12 => 'Des',
        ];

        // Group by tanggal for the chart
        $dailyRecords = DataPenjualan::selectRaw('tanggal, SUM(total_penjualan) as total_penjualan, MAX(id_data_penjualan) as last_id')
            ->whereIn(\Illuminate\Support\Facades\DB::raw('MONTH(tanggal)'), [12, 1, 2])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $dailyDataArray = $dailyRecords->toArray();
        $predictions = \App\Models\HasilPrediksi::all()->keyBy('id_data_penjualan');

        foreach ($dailyRecords as $record) {
            $chartLabels[] = \Carbon\Carbon::parse($record->tanggal)->format('d M y');
            $chartActual[] = $record->total_penjualan;
            
            $prediksi = $predictions->get($record->last_id);
            $chartWma[] = $prediksi ? $prediksi->wma : null;
        }

        $totalCount = count($dailyDataArray);
        if ($totalCount >= 3) {
            $lastRecord = end($dailyDataArray);
            
            $nextDate = \Carbon\Carbon::parse($lastRecord['tanggal'])->addDay();
            
            $wmaNext = (new WeightedMovingAverage())->calculateWMA($dailyDataArray, $totalCount);

            $chartLabels[] = $nextDate->format('d M y');
            $chartActual[] = null;
            $chartWma[] = $wmaNext;
        }

        return view('livewire.admin.dashboard', compact('stats', 'chartLabels', 'chartActual', 'chartWma'));
    }
}
