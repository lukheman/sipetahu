<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplatePenjualanExport implements FromArray, WithHeadings
{
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

    public function array(): array
    {
        return [
            [
                '2025-01-01',
                7056,
                6912,
                13968,
                6624,
                5616,
                12240,
                432,
                1296
            ]
        ];
    }
}
