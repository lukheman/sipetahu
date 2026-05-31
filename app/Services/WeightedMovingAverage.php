<?php

namespace App\Services;

use App\Models\DataPenjualan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class WeightedMovingAverage
{

    private int $bobot1BulanLalu = 3;
    private int $bobot2BulanLalu = 2;
    private int $bobot3BulanLalu = 1;

    public function __construct()
    {

    }

    public function calculateWMA(array $monthlyData, int $posisi): float
    {
        $start = max(0, $posisi - 3);
        $data = array_slice($monthlyData, $start, 3);

        if (count($data) < 3) {
            return 0;
        }

        $d3 = $data[0]['total_penjualan']; // 3 bulan lalu
        $d2 = $data[1]['total_penjualan']; // 2 bulan lalu
        $d1 = $data[2]['total_penjualan']; // 1 bulan lalu

        $totalBobot = $this->bobot1BulanLalu + $this->bobot2BulanLalu + $this->bobot3BulanLalu;

        $result = round((
            ($d1 * $this->bobot1BulanLalu) +
            ($d2 * $this->bobot2BulanLalu) +
            ($d3 * $this->bobot3BulanLalu)

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
        
        // Group by year and month
        $monthlyRecords = DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan, MAX(id_data_penjualan) as last_id')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();

        $monthlyDataArray = $monthlyRecords->toArray();

        foreach ($monthlyRecords as $index => $monthData) {
            if ($index < $offset) {
                // WMA membutuhkan historis data sebelum index saat ini (misal 3 bulan)
                continue;
            }

            $xt = $monthData->total_penjualan;

            $wma = $this->calculateWMA($monthlyDataArray, $index);
            $mad = $this->calculateMAD($xt, $wma);
            $error = $this->calculateError($xt, $wma);
            $mse = $this->calculateMSE($xt, $wma);
            $mape = $this->calculateMAPE($xt, $wma);

            \App\Models\HasilPrediksi::updateOrCreate(
                ['id_data_penjualan' => $monthData->last_id], // kondisi
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
