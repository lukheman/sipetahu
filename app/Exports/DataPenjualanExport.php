<?php

namespace App\Exports;

use App\Models\DataPenjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataPenjualanExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DataPenjualan::orderBy('tanggal', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Produksi Tahu Kecil',
            'Produksi Tahu Besar',
            'Total Produksi',
            'Penjualan Tahu Kecil',
            'Penjualan Tahu Besar',
            'Total Penjualan',
            'Tahu Kembali Kecil',
            'Tahu Kembali Besar'
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal,
            $row->produksi_tahu_kecil,
            $row->produksi_tahu_besar,
            $row->total_produksi,
            $row->penjualan_tahu_kecil,
            $row->penjualan_tahu_besar,
            $row->total_penjualan,
            $row->tahu_kembali_kecil,
            $row->tahu_kembali_besar,
        ];
    }
}
