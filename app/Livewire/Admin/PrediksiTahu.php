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

    public $start_date;
    public $end_date;

    public function mount()
    {
        $firstData = \App\Models\DataPenjualan::orderBy('tanggal', 'asc')->first();
        $lastData = \App\Models\DataPenjualan::orderBy('tanggal', 'desc')->first();

        $this->start_date = $firstData ? $firstData->tanggal : now()->subMonths(3)->startOfMonth()->format('Y-m-d');
        $this->end_date = $lastData ? $lastData->tanggal : now()->endOfMonth()->format('Y-m-d');
    }

    public function kalkulasiWMA()
    {
        if (auth()->user()->role !== \App\Enums\Role::ADMIN) {
            abort(403, 'Hanya Admin yang dapat menghitung WMA.');
        }

        \App\Models\HasilPrediksi::truncate();
        $wmaService = new WeightedMovingAverage();
        $wmaService->generatePrediksiTahu($this->start_date, $this->end_date);

        session()->flash('success', 'Kalkulasi prediksi WMA berhasil dijalankan!');

        $this->dispatch('wma-calculated');
    }

    public function render()
    {
        $nextPrediction = null;

        $dailyRecords = DataPenjualan::selectRaw('tanggal, SUM(total_penjualan) as total_penjualan')
            ->whereBetween('tanggal', [$this->start_date, $this->end_date])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->take(3)
            ->get();

        if ($dailyRecords->count() >= 3) {
            $lastRecord = $dailyRecords->first();

            $nextDate = \Carbon\Carbon::parse($lastRecord->tanggal)->addDay();
            $nextHariStr = $nextDate->format('d M Y');

            $wmaService = new WeightedMovingAverage();

            $ascRecords = DataPenjualan::selectRaw('tanggal, SUM(total_penjualan) as total_penjualan')
                ->whereBetween('tanggal', [$this->start_date, $this->end_date])
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
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
                'tanggal' => $nextHariStr,
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
