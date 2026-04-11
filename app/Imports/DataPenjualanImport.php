<?php

namespace App\Imports;

use App\Models\DataPenjualan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DataPenjualanImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        // the index matches the slugified heading string. e.g. "Nama Produk" -> "nama_produk"
        if (!isset($row['nama_produk']) || !isset($row['tahun']) || !isset($row['bulan'])) {
            return null;
        }

        $produk = \App\Models\Produk::whereRaw('LOWER(nama_produk) = ?', [strtolower(trim($row['nama_produk']))])->first();

        if (!$produk) {
            return null; // Ignore if product by that name is not found
        }

        return DataPenjualan::updateOrCreate(
            [
                'id_produk' => $produk->id_produk,
                'tahun' => $row['tahun'],
                'bulan' => $row['bulan'],
            ],
            [
                'jumlah' => isset($row['jumlah']) ? $row['jumlah'] : ($row['jumlah_kg'] ?? 0),
            ]
        );
    }

    public function rules(): array
    {
        return [
            'nama_produk' => 'required',
            'tahun' => 'required|integer',
            'bulan' => 'required|integer|between:1,12',
            'jumlah' => 'nullable|numeric|min:0',
            'jumlah_kg' => 'nullable|numeric|min:0',
        ];
    }
}
