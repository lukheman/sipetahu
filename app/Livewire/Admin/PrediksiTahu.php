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
    public $filter_produk = '';

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
        $wmaService->generatePrediksiTahu($this->start_date, $this->end_date, $this->filter_produk ?: null);

        session()->flash('success', 'Kalkulasi prediksi WMA berhasil dijalankan!');

        $this->dispatch('wma-calculated');
    }

    public function render()
    {
        $nextPrediction = null;

        $startDateObj = \Carbon\Carbon::parse($this->start_date);
        $endDateObj = \Carbon\Carbon::parse($this->end_date);

        if ($startDateObj->lte($endDateObj)) {
            $nextDate = $endDateObj->copy()->addDay();
            $nextHariStr = $nextDate->format('d M Y');

            $dbRecordsQuery = DataPenjualan::selectRaw('data_penjualan.tanggal, SUM(detail_penjualan.penjualan) as total_penjualan')
                ->join('detail_penjualan', 'data_penjualan.id_data_penjualan', '=', 'detail_penjualan.id_data_penjualan')
                ->whereBetween('data_penjualan.tanggal', [$this->start_date, $this->end_date])
                ->groupBy('data_penjualan.tanggal');
                
            if ($this->filter_produk) {
                $dbRecordsQuery->where('detail_penjualan.id_produk', $this->filter_produk);
            }
            
            $dbRecords = $dbRecordsQuery->pluck('total_penjualan', 'tanggal');

            $ascRecords = [];
            $curr = $startDateObj->copy();
            
            while ($curr->lte($endDateObj)) {
                $dStr = $curr->format('Y-m-d');
                $ascRecords[] = [
                    'tanggal' => $dStr,
                    'total_penjualan' => $dbRecords->get($dStr) ?? 0,
                ];
                $curr->addDay();
            }

            $wmaService = new WeightedMovingAverage();
            $count = count($ascRecords);

            if ($count >= 3) {
                $wmaNext = $wmaService->calculateWMA($ascRecords, $count);

                $d3 = number_format($ascRecords[$count-3]['total_penjualan'], 0, ',', '.');
                $d2 = number_format($ascRecords[$count-2]['total_penjualan'], 0, ',', '.');
                $d1 = number_format($ascRecords[$count-1]['total_penjualan'], 0, ',', '.');
                $detailStr = "(( {$d1} × 3 ) + ( {$d2} × 2 ) + ( {$d3} × 1 )) / 6";

                $nextPrediction = [
                    'tanggal' => $nextHariStr,
                    'wma' => $wmaNext,
                    'detail_wma' => $detailStr
                ];
            }
        }

        $avgMAD = \App\Models\HasilPrediksi::query()->has('dataPenjualan')->avg('mad') ?? 0;
        $avgMSE = \App\Models\HasilPrediksi::query()->has('dataPenjualan')->avg('mse') ?? 0;
        $avgMAPE = \App\Models\HasilPrediksi::query()->has('dataPenjualan')->avg('mape') ?? 0;

        $bulanOptions = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $products = \App\Models\Produk::orderBy('nama_produk')->get();

        return view('livewire.admin.prediksi-tahu', [
            'nextPrediction' => $nextPrediction,
            'avgMAD' => $avgMAD,
            'avgMSE' => $avgMSE,
            'avgMAPE' => $avgMAPE,
            'bulanOptions' => $bulanOptions,
            'products' => $products,
        ]);
    }
}
