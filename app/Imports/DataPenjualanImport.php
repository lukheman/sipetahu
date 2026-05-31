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
        if (!isset($row['tanggal'])) {
            return null;
        }

        // Convert excel date format if necessary
        $tanggal = $row['tanggal'];
        if (is_numeric($tanggal)) {
            $tanggal = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tanggal)->format('Y-m-d');
        }

        return DataPenjualan::updateOrCreate(
            [
                'tanggal' => $tanggal,
            ],
            [
                'produksi_tahu_kecil' => $row['produksi_tahu_kecil'] ?? 0,
                'produksi_tahu_besar' => $row['produksi_tahu_besar'] ?? 0,
                'total_produksi' => $row['total_produksi'] ?? 0,
                'penjualan_tahu_kecil' => $row['penjualan_tahu_kecil'] ?? 0,
                'penjualan_tahu_besar' => $row['penjualan_tahu_besar'] ?? 0,
                'total_penjualan' => $row['total_penjualan'] ?? 0,
                'tahu_kembali_kecil' => $row['tahu_kembali_kecil'] ?? 0,
                'tahu_kembali_besar' => $row['tahu_kembali_besar'] ?? 0,
            ]
        );
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required',
            'produksi_tahu_kecil' => 'nullable|integer|min:0',
            'produksi_tahu_besar' => 'nullable|integer|min:0',
            'total_produksi' => 'nullable|integer|min:0',
            'penjualan_tahu_kecil' => 'nullable|integer|min:0',
            'penjualan_tahu_besar' => 'nullable|integer|min:0',
            'total_penjualan' => 'nullable|integer|min:0',
            'tahu_kembali_kecil' => 'nullable|integer|min:0',
            'tahu_kembali_besar' => 'nullable|integer|min:0',
        ];
    }
}
