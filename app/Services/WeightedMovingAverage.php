<?php

namespace App\Services;

use App\Models\DataPenjualan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WeightedMovingAverage
{

    private int $bobot1HariLalu = 3;
    private int $bobot2HariLalu = 2;
    private int $bobot3HariLalu = 1;

    public function __construct()
    {

    }

    public function calculateWMA(array $dailyData, int $posisi): float
    {
        $start = max(0, $posisi - 3);
        $data = array_slice($dailyData, $start, 3);

        if (count($data) < 3) {
            return 0;
        }

        $d3 = $data[0]['total_penjualan']; // 3 hari lalu
        $d2 = $data[1]['total_penjualan']; // 2 hari lalu
        $d1 = $data[2]['total_penjualan']; // 1 hari lalu

        $totalBobot = $this->bobot1HariLalu + $this->bobot2HariLalu + $this->bobot3HariLalu;

        $result = round((
            ($d1 * $this->bobot1HariLalu) +
            ($d2 * $this->bobot2HariLalu) +
            ($d3 * $this->bobot3HariLalu)

        ) / $totalBobot);

        return $result;
    }

    public function calculateMAD(float $xt, float $st): float
    {
        return abs($xt - $st);
    }

    public function calculateError(float $xt, float $st): float
    {
        return $xt - $st;
    }

    public function calculateMSE(float $xt, float $st): float
    {
        return abs($xt - $st) ** 2;
    }

    // MAPE = 100 x sigma | Xt - St | / Xt
    public function calculateMAPE(float $xt, float $st): float
    {
        if ($xt == 0) {
            return 0;
        }

        return round(($this->calculateMAD($xt, $st) / $xt) * 100, 1);
    }

    public function generatePrediksiTahu()
    {
        $offset = 3;
        
        // Ambil data harian dari Desember (12), Januari (1), Februari (2)
        $dailyRecords = DataPenjualan::selectRaw('tanggal, SUM(total_penjualan) as total_penjualan, MAX(id_data_penjualan) as last_id')
            ->whereIn(\Illuminate\Support\Facades\DB::raw('MONTH(tanggal)'), [12, 1, 2])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        $dailyDataArray = $dailyRecords->toArray();

        foreach ($dailyRecords as $index => $dayData) {
            if ($index < $offset) {
                // WMA membutuhkan historis data sebelum index saat ini (misal 3 hari)
                continue;
            }

            $xt = $dayData->total_penjualan;

            $wma = $this->calculateWMA($dailyDataArray, $index);
            $mad = $this->calculateMAD($xt, $wma);
            $error = $this->calculateError($xt, $wma);
            $mse = $this->calculateMSE($xt, $wma);
            $mape = $this->calculateMAPE($xt, $wma);

            \App\Models\HasilPrediksi::updateOrCreate(
                ['id_data_penjualan' => $dayData->last_id], // kondisi
                [
                    'wma' => $wma,
                    'error' => $error,
                    'mad' => $mad,
                    'mse' => $mse,
                    'mape' => $mape,
                ]
            );
        }
    }
}
