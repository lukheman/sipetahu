<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateProduksiExport implements FromArray, WithHeadings
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
            'Nama Produk',
            'Produksi'
        ];
    }

    public function array(): array
    {
        $rows = [];
        
        foreach ($this->products as $product) {
            $rows[] = [
                '2025-01-01',
                $product->nama_produk,
                500
            ];
        }

        return $rows;
    }
}
