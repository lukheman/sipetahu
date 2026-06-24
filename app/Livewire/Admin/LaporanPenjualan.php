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

        $records = $query->orderBy('tanggal', 'asc')->with('detailPenjualans.produk')->get();
        $products = \App\Models\Produk::orderBy('id_produk')->get();

        $summary = [
            'total_produksi' => $records->sum('total_produksi'),
            'total_penjualan' => $records->sum('total_penjualan'),
            'products' => []
        ];

        foreach ($products as $product) {
            $summary['products'][$product->id_produk] = [
                'produksi' => 0,
                'penjualan' => 0,
            ];
            foreach ($records as $record) {
                $detail = $record->detailPenjualans->firstWhere('id_produk', $product->id_produk);
                if ($detail) {
                    $summary['products'][$product->id_produk]['produksi'] += $detail->produksi;
                    $summary['products'][$product->id_produk]['penjualan'] += $detail->penjualan;
                }
            }
        }

        $pdf = Pdf::loadView('pdf.laporan-penjualan', [
            'records' => $records,
            'summary' => $summary,
            'products' => $products,
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

        $records = $query->orderBy('tanggal', 'desc')->with('detailPenjualans.produk')->get();
        $products = \App\Models\Produk::orderBy('id_produk')->get();

        $summary = [
            'total_produksi' => $records->sum('total_produksi'),
            'total_penjualan' => $records->sum('total_penjualan'),
            'products' => []
        ];

        foreach ($products as $product) {
            $summary['products'][$product->id_produk] = [
                'produksi' => 0,
                'penjualan' => 0,
            ];
            foreach ($records as $record) {
                $detail = $record->detailPenjualans->firstWhere('id_produk', $product->id_produk);
                if ($detail) {
                    $summary['products'][$product->id_produk]['produksi'] += $detail->produksi;
                    $summary['products'][$product->id_produk]['penjualan'] += $detail->penjualan;
                }
            }
        }

        return view('livewire.admin.laporan-penjualan', [
            'records' => $records,
            'products' => $products,
            'summary' => $summary
        ]);
    }
}
