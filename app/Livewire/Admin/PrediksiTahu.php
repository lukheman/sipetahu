<?php

namespace App\Livewire\Admin;

use App\Models\DataPenjualan;
use App\Services\WeightedMovingAverage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Prediksi WMA (Tahu)')]
class PrediksiTahu extends Component
{
    use WithPagination;

    public function kalkulasiWMA()
    {
        if (auth()->user()->role !== \App\Enums\Role::ADMIN) {
            abort(403, 'Hanya Admin yang dapat menghitung WMA.');
        }

        $wmaService = new WeightedMovingAverage();
        $wmaService->generatePrediksiTahu();

        session()->flash('success', 'Kalkulasi prediksi WMA berhasil dijalankan!');

        $this->dispatch('wma-calculated');
    }

    public function render()
    {
        $nextPrediction = null;

        $monthlyRecords = DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->take(3)
            ->get();

        if ($monthlyRecords->count() >= 3) {
            $lastRecord = $monthlyRecords->first();

            $nextBulan = $lastRecord->bulan == 12 ? 1 : $lastRecord->bulan + 1;
            $nextTahun = $lastRecord->bulan == 12 ? $lastRecord->tahun + 1 : $lastRecord->tahun;

            $wmaService = new WeightedMovingAverage();

            $ascRecords = DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan')
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun', 'asc')
                ->orderBy('bulan', 'asc')
                ->get()
                ->toArray();

            $wmaNext = $wmaService->calculateWMA($ascRecords, count($ascRecords));

            $count = count($ascRecords);
            $detailStr = null;
            if ($count >= 3) {
                $d3 = number_format($ascRecords[$count-3]['total_penjualan'], 2, ',', '.');
                $d2 = number_format($ascRecords[$count-2]['total_penjualan'], 2, ',', '.');
                $d1 = number_format($ascRecords[$count-1]['total_penjualan'], 2, ',', '.');
                $detailStr = "(( {$d1} × 3 ) + ( {$d2} × 2 ) + ( {$d3} × 1 )) / 6";
            }

            $nextPrediction = [
                'bulan' => $nextBulan,
                'tahun' => $nextTahun,
                'wma' => $wmaNext,
                'detail_wma' => $detailStr
            ];
        }

        $avgMAD = \App\Models\HasilPrediksi::query()->has('dataPenjualan')->avg('mad') ?? 0;
        $avgMSE = \App\Models\HasilPrediksi::query()->has('dataPenjualan')->avg('mse') ?? 0;
        $avgMAPE = \App\Models\HasilPrediksi::query()->has('dataPenjualan')->avg('mape') ?? 0;

        $bulanOptions = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('livewire.admin.prediksi-tahu', [
            'nextPrediction' => $nextPrediction,
            'avgMAD' => $avgMAD,
            'avgMSE' => $avgMSE,
            'avgMAPE' => $avgMAPE,
            'bulanOptions' => $bulanOptions,
        ]);
    }
}
