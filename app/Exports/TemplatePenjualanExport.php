<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplatePenjualanExport implements FromArray, WithHeadings
{
    private $products;

    public function __construct()
    {
        $this->products = Produk::orderBy('id_produk')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Jenis Pembeli',
            'Pelanggan',
            'Nama Produk',
            'Produksi',
            'Penjualan'
        ];
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->products as $product) {
            $rows[] = [
                '2025-01-01',
                'Langsung',
                '',
                $product->nama_produk,
                500,
                450
            ];
        }

        return $rows;
    }
}
