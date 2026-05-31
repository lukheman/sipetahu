<?php

namespace App\Livewire\Admin;

use App\Models\DataPenjualan;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Enums\Role;
use Barryvdh\DomPDF\Facade\Pdf;

#[Title('Laporan Penjualan')]
class LaporanPenjualan extends Component
{
    public $start_date = '';
    public $end_date = '';

    public function mount()
    {
        if (auth()->user()->role !== Role::PEMILIK) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Pemilik.');
        }
        
        $this->start_date = now()->startOfMonth()->format('Y-m-d');
        $this->end_date = now()->endOfMonth()->format('Y-m-d');
    }

    public function exportPdf()
    {
        $query = DataPenjualan::query();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        }

        $records = $query->orderBy('tanggal', 'asc')->get();

        $summary = [
            'produksi_kecil' => $records->sum('produksi_tahu_kecil'),
            'produksi_besar' => $records->sum('produksi_tahu_besar'),
            'total_produksi' => $records->sum('total_produksi'),
            'penjualan_kecil' => $records->sum('penjualan_tahu_kecil'),
            'penjualan_besar' => $records->sum('penjualan_tahu_besar'),
            'total_penjualan' => $records->sum('total_penjualan'),
            'kembali_kecil' => $records->sum('tahu_kembali_kecil'),
            'kembali_besar' => $records->sum('tahu_kembali_besar'),
        ];

        $pdf = Pdf::loadView('pdf.laporan-penjualan', [
            'records' => $records,
            'summary' => $summary,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Laporan_Penjualan_' . $this->start_date . '_sd_' . $this->end_date . '.pdf');
    }

    public function render()
    {
        $query = DataPenjualan::query();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('tanggal', [$this->start_date, $this->end_date]);
        }

        $records = $query->orderBy('tanggal', 'desc')->get();

        $total_produksi_kecil = $records->sum('produksi_tahu_kecil');
        $total_produksi_besar = $records->sum('produksi_tahu_besar');
        $total_produksi = $records->sum('total_produksi');
        
        $total_penjualan_kecil = $records->sum('penjualan_tahu_kecil');
        $total_penjualan_besar = $records->sum('penjualan_tahu_besar');
        $total_penjualan = $records->sum('total_penjualan');

        $total_kembali_kecil = $records->sum('tahu_kembali_kecil');
        $total_kembali_besar = $records->sum('tahu_kembali_besar');

        return view('livewire.admin.laporan-penjualan', [
            'records' => $records,
            'summary' => [
                'produksi_kecil' => $total_produksi_kecil,
                'produksi_besar' => $total_produksi_besar,
                'total_produksi' => $total_produksi,
                'penjualan_kecil' => $total_penjualan_kecil,
                'penjualan_besar' => $total_penjualan_besar,
                'total_penjualan' => $total_penjualan,
                'kembali_kecil' => $total_kembali_kecil,
                'kembali_besar' => $total_kembali_besar,
            ]
        ]);
    }
}
