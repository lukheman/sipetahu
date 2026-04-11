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
        return DataPenjualan::with('produk')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Tahun',
            'Bulan',
            'Jumlah (kg)'
        ];
    }

    public function map($row): array
    {
        return [
            optional($row->produk)->nama_produk,
            $row->tahun,
            $row->bulan,
            $row->jumlah,
        ];
    }
}
