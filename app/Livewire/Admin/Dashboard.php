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
            'total_volume' => DataPenjualan::sum('jumlah'),
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

        if ($produkTahu) {
            $records = DataPenjualan::with('hasilPrediksi')
                ->where('id_produk', $produkTahu->id_produk)
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            foreach ($records as $record) {
                $chartLabels[] = ($bulanOptions[$record->bulan] ?? '') . ' ' . substr($record->tahun, 2);
                $chartActual[] = $record->jumlah;
                $chartWma[] = $record->hasilPrediksi ? $record->hasilPrediksi->wma : null;
            }

            $totalCount = $records->count();
            if ($totalCount >= 3) {
                $lastRecord = $records->last();
                $nextBulan = $lastRecord->bulan == 12 ? 1 : $lastRecord->bulan + 1;
                $nextTahun = $lastRecord->bulan == 12 ? $lastRecord->tahun + 1 : $lastRecord->tahun;
                $wmaNext = (new WeightedMovingAverage())->calculateWMA($totalCount, $produkTahu->id_produk);

                $chartLabels[] = ($bulanOptions[$nextBulan] ?? '') . ' ' . substr($nextTahun, 2);
                $chartActual[] = null;
                $chartWma[] = $wmaNext;
            }
        }

        return view('livewire.admin.dashboard', compact('stats', 'chartLabels', 'chartActual', 'chartWma'));
    }
}
