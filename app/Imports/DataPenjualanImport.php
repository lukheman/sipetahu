<?php

namespace App\Imports;

use App\Models\DataPenjualan;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class DataPenjualanImport implements ToCollection
{
    public int $importedCount = 0;

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        // First row is the header — build a column map
        $headerRow = $rows->first()->toArray();
        $columnMap = $this->buildColumnMap($headerRow);

        $products = \App\Models\Produk::all();

        // Process data rows (skip header)
        foreach ($rows->skip(1) as $row) {
            $rowArray = $row->toArray();

            $tanggal = $this->parseDate($this->getVal($rowArray, $columnMap, 'tanggal'));
            if (!$tanggal) {
                continue;
            }

            $jenis_pembeli = $this->getVal($rowArray, $columnMap, 'jenis_pembeli');
            $jenis_pembeli = $jenis_pembeli ? strtolower(trim($jenis_pembeli)) : 'langsung';

            $pelanggan_name = $this->getVal($rowArray, $columnMap, 'pelanggan');
            $id_pelanggan = null;
            if ($jenis_pembeli === 'pelanggan' && $pelanggan_name) {
                $dist = \App\Models\Pelanggan::where('nama_pelanggan', 'like', "%{$pelanggan_name}%")->first();
                $id_pelanggan = $dist ? $dist->id_pelanggan : null;
            }

            $record = DataPenjualan::create([
                'tanggal' => $tanggal,
                'jenis_pembeli' => $jenis_pembeli,
                'id_pelanggan' => $id_pelanggan,
                'total_penjualan' => 0,
            ]);

            $nama_produk = $this->getVal($rowArray, $columnMap, 'nama_produk');
            if ($nama_produk) {
                $product = \App\Models\Produk::where('nama_produk', 'like', "%{$nama_produk}%")->first();
                if ($product) {
                    $penjualan = $this->toInt($this->getVal($rowArray, $columnMap, 'penjualan'));

                    $record->detailPenjualans()->create([
                        'id_produk' => $product->id_produk,
                        'penjualan' => $penjualan,
                    ]);
                    $record->update(['total_penjualan' => $record->detailPenjualans()->sum('penjualan')]);
                    $this->importedCount++;
                }
            }

            $this->importedCount++;
        }
    }

    /**
     * Build a map of normalized field name => column index.
     * e.g. "Produksi Tahu Kecil" at index 1 → ['produksi_tahu_kecil' => 1]
     */
    private function buildColumnMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $index => $header) {
            if ($header === null) {
                continue;
            }
            // Normalize: remove extra spaces, lowercase, replace spaces with underscores
            $normalized = Str::snake(Str::lower(trim((string) $header)));
            // Also try slug-based normalization as fallback
            $slugged = Str::slug(trim((string) $header), '_');

            $map[$normalized] = $index;
            if ($normalized !== $slugged) {
                $map[$slugged] = $index;
            }
        }

        return $map;
    }

    private function getVal(array $row, array $columnMap, string $field): mixed
    {
        if (!isset($columnMap[$field])) {
            return null;
        }

        return $row[$columnMap[$field]] ?? null;
    }

    private function parseDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel serial number (dates are stored as numbers > 1000)
        if (is_numeric($value) && (float) $value > 1000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((int) $value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // String date
        try {
            return Carbon::parse((string) $value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function toInt(mixed $value): int
    {
        if ($value === null || $value === '' || $value === '-') {
            return 0;
        }

        return (int) round((float) $value);
    }
}
