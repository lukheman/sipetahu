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
    public $start_date;
    public $end_date;

    public function mount()
    {
        if (auth()->user()->role !== Role::PEMILIK) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Pemilik.');
        }

        $firstData = \App\Models\DataPenjualan::orderBy('tanggal', 'asc')->first();
        $lastData = \App\Models\DataPenjualan::orderBy('tanggal', 'desc')->first();

        $this->start_date = $firstData ? $firstData->tanggal : now()->subMonths(3)->startOfMonth()->format('Y-m-d');
        $this->end_date = $lastData ? $lastData->tanggal : now()->endOfMonth()->format('Y-m-d');
    }

    public function exportPdf()
    {
        $dailyRecordsQuery = DataPenjualan::selectRaw('data_penjualan.tanggal, SUM(detail_penjualan.penjualan) as total_penjualan, MAX(data_penjualan.id_data_penjualan) as last_id')
            ->join('detail_penjualan', 'data_penjualan.id_data_penjualan', '=', 'detail_penjualan.id_data_penjualan')
            ->whereBetween('data_penjualan.tanggal', [$this->start_date, $this->end_date])
            ->groupBy('data_penjualan.tanggal')
            ->orderBy('data_penjualan.tanggal', 'asc');
            
        $dailyRecords = $dailyRecordsQuery->get();
            
        $predictions = HasilPrediksi::all()->keyBy('id_data_penjualan');
        
        foreach($dailyRecords as $record) {
            $record->hasilPrediksi = $predictions->get($record->last_id);
            $record->tahu_besar = \App\Models\DetailPenjualan::whereHas('dataPenjualan', fn($q) => $q->where('tanggal', $record->tanggal))->where('id_produk', 1)->sum('penjualan');
            $record->tahu_kecil = \App\Models\DetailPenjualan::whereHas('dataPenjualan', fn($q) => $q->where('tanggal', $record->tanggal))->where('id_produk', 2)->sum('penjualan');
        }

        $avgMAD = HasilPrediksi::has('dataPenjualan')->avg('mad') ?? 0;
        $avgMSE = HasilPrediksi::has('dataPenjualan')->avg('mse') ?? 0;
        $avgMAPE = HasilPrediksi::has('dataPenjualan')->avg('mape') ?? 0;

        $pdf = Pdf::loadView('pdf.laporan-wma', [
            'records' => $dailyRecords,
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
