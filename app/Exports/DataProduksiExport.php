<?php

namespace App\Exports;

use App\Models\DetailPenjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataProduksiExport implements FromCollection, WithHeadings, WithMapping
{
    public string $search;

    public string $filter_produk;

    public string $sort_tanggal;

    public function __construct(string $search = '', string $filter_produk = '', string $sort_tanggal = 'desc')
    {
        $this->search = $search;
        $this->filter_produk = $filter_produk;
        $this->sort_tanggal = $sort_tanggal;
    }

    public function collection()
    {
        $query = DetailPenjualan::with(['dataPenjualan', 'produk'])
            ->join('data_penjualan', 'detail_penjualan.id_data_penjualan', '=', 'data_penjualan.id_data_penjualan')
            ->select('detail_penjualan.*');

        if ($this->search) {
            $query->where('data_penjualan.tanggal', 'like', '%'.$this->search.'%');
        }

        if ($this->filter_produk) {
            $query->where('detail_penjualan.id_produk', $this->filter_produk);
        }

        $query->orderBy('data_penjualan.tanggal', $this->sort_tanggal === 'asc' ? 'asc' : 'desc');

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Produk',
            'Produksi',
        ];
    }

    public function map($detail): array
    {
        $dataPenjualan = $detail->dataPenjualan;

        return [
            $dataPenjualan ? $dataPenjualan->tanggal : '',
            $detail->produk ? $detail->produk->nama_produk : '',
            $detail->produksi,
        ];
    }
}
