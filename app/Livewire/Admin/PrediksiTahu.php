<?php

namespace App\Livewire\Admin;

use App\Models\DataPenjualan;
use App\Models\Produk;
use App\Models\HasilPrediksi;
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya Admin yang dapat menghitung WMA.');
        }

        $wmaService = new WeightedMovingAverage();
        $wmaService->generatePrediksiTahu();

        session()->flash('success', 'Kalkulasi prediksi WMA berhasil dijalankan untuk produk Tahu!');
    }

    public function render()
    {
        $produkTahu = Produk::where('nama_produk', 'Tahu')->first();

        $nextPrediction = null;

        if ($produkTahu) {
            $records = DataPenjualan::with('hasilPrediksi')
                ->where('id_produk', $produkTahu->id_produk)
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->paginate(15);

            $totalCount = DataPenjualan::where('id_produk', $produkTahu->id_produk)->count();

            if ($totalCount >= 3) {
                // Determine next month/year
                $lastRecord = DataPenjualan::where('id_produk', $produkTahu->id_produk)
                    ->orderBy('tahun', 'desc')
                    ->orderBy('bulan', 'desc')
                    ->first();

                $nextBulan = $lastRecord->bulan == 12 ? 1 : $lastRecord->bulan + 1;
                $nextTahun = $lastRecord->bulan == 12 ? $lastRecord->tahun + 1 : $lastRecord->tahun;

                $wmaService = new WeightedMovingAverage();
                // We use totalCount as the position to predict the upcoming unrecorded index
                $wmaNext = $wmaService->calculateWMA($totalCount, $produkTahu->id_produk);

                $nextPrediction = [
                    'bulan' => $nextBulan,
                    'tahun' => $nextTahun,
                    'wma' => $wmaNext
                ];
            }
        } else {
            $records = collect();
        }

        $avgMAD = HasilPrediksi::whereHas('dataPenjualan', function ($q) use ($produkTahu) {
            if ($produkTahu)
                $q->where('id_produk', $produkTahu->id_produk);
        })->avg('mad') ?? 0;

        $avgMSE = HasilPrediksi::whereHas('dataPenjualan', function ($q) use ($produkTahu) {
            if ($produkTahu)
                $q->where('id_produk', $produkTahu->id_produk);
        })->avg('mse') ?? 0;

        $avgMAPE = HasilPrediksi::whereHas('dataPenjualan', function ($q) use ($produkTahu) {
            if ($produkTahu)
                $q->where('id_produk', $produkTahu->id_produk);
        })->avg('mape') ?? 0;

        $bulanOptions = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return view('livewire.admin.prediksi-tahu', [
            'records' => $records,
            'nextPrediction' => $nextPrediction,
            'avgMAD' => $avgMAD,
            'avgMSE' => $avgMSE,
            'avgMAPE' => $avgMAPE,
            'bulanOptions' => $bulanOptions,
        ]);
    }
}
