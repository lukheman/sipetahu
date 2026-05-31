<?php

namespace App\Livewire\Admin;

use App\Models\DataPenjualan;
use App\Models\HasilPrediksi;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Enums\Role;
use Barryvdh\DomPDF\Facade\Pdf;

#[Title('Laporan Prediksi WMA')]
class LaporanWma extends Component
{
    public function mount()
    {
        if (auth()->user()->role !== Role::PEMILIK) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Pemilik.');
        }
    }

    public function exportPdf()
    {
        $monthlyRecords = DataPenjualan::selectRaw('YEAR(tanggal) as tahun, MONTH(tanggal) as bulan, SUM(total_penjualan) as total_penjualan, MAX(id_data_penjualan) as last_id')
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'asc')
            ->orderBy('bulan', 'asc')
            ->get();
            
        $predictions = HasilPrediksi::all()->keyBy('id_data_penjualan');
        
        foreach($monthlyRecords as $record) {
            $record->hasilPrediksi = $predictions->get($record->last_id);
        }

        $avgMAD = HasilPrediksi::has('dataPenjualan')->avg('mad') ?? 0;
        $avgMSE = HasilPrediksi::has('dataPenjualan')->avg('mse') ?? 0;
        $avgMAPE = HasilPrediksi::has('dataPenjualan')->avg('mape') ?? 0;

        $pdf = Pdf::loadView('pdf.laporan-wma', [
            'records' => $monthlyRecords,
            'avgMAD' => $avgMAD,
            'avgMSE' => $avgMSE,
            'avgMAPE' => $avgMAPE,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Laporan_WMA_' . now()->format('Ymd') . '.pdf');
    }

    public function render()
    {
        $avgMAD = HasilPrediksi::has('dataPenjualan')->avg('mad') ?? 0;
        $avgMSE = HasilPrediksi::has('dataPenjualan')->avg('mse') ?? 0;
        $avgMAPE = HasilPrediksi::has('dataPenjualan')->avg('mape') ?? 0;

        return view('livewire.admin.laporan-wma', [
            'avgMAD' => $avgMAD,
            'avgMSE' => $avgMSE,
            'avgMAPE' => $avgMAPE,
        ]);
    }
}
