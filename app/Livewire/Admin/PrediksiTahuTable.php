<?php

namespace App\Livewire\Admin;

use App\Models\DataPenjualan;
use App\Models\HasilPrediksi;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class PrediksiTahuTable extends Component
{
    use WithPagination;

    public $nextPrediction;

    #[On('wma-calculated')]
    public function refreshTable()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get paginated monthly records
        $records = DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan, MAX(id_data_penjualan) as last_id')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->paginate(10);

        // Fetch predictions to map them to the monthly records
        // We only fetch predictions that match the current page's last_ids to be efficient
        $lastIds = $records->pluck('last_id');
        $predictions = HasilPrediksi::whereIn('id_data_penjualan', $lastIds)->get()->keyBy('id_data_penjualan');
        
        $allRecords = DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get()
            ->toArray();

        // Prepare details
        foreach($records as $record) {
            $record->hasilPrediksi = $predictions->get($record->last_id);
            $record->detail_wma = null;

            if ($record->hasilPrediksi) {
                // Find index
                $idx = -1;
                foreach($allRecords as $k => $arr) {
                    if ($arr['tahun'] == $record->tahun && $arr['bulan'] == $record->bulan) {
                        $idx = $k;
                        break;
                    }
                }
                if ($idx >= 3) {
                    $d3 = number_format($allRecords[$idx-3]['total_penjualan'], 2, ',', '.');
                    $d2 = number_format($allRecords[$idx-2]['total_penjualan'], 2, ',', '.');
                    $d1 = number_format($allRecords[$idx-1]['total_penjualan'], 2, ',', '.');
                    
                    $record->detail_wma = "(( {$d1} × 3 ) + ( {$d2} × 2 ) + ( {$d3} × 1 )) / 6";
                }
            }
        }


        $bulanOptions = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return view('livewire.admin.prediksi-tahu-table', [
            'records' => $records,
            'bulanOptions' => $bulanOptions,
        ]);
    }
}
