<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DataPenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('data_penjualan')->insert([
            ['bulan' => 1, 'tahun' => 2022, 'jumlah' => 18400],
            ['bulan' => 2, 'tahun' => 2022, 'jumlah' => 17500],
            ['bulan' => 3, 'tahun' => 2022, 'jumlah' => 16200],
            ['bulan' => 4, 'tahun' => 2022, 'jumlah' => 18100],
            ['bulan' => 5, 'tahun' => 2022, 'jumlah' => 17426],
            ['bulan' => 6, 'tahun' => 2022, 'jumlah' => 20847],
            ['bulan' => 7, 'tahun' => 2022, 'jumlah' => 18750],
            ['bulan' => 8, 'tahun' => 2022, 'jumlah' => 19755],
            ['bulan' => 9, 'tahun' => 2022, 'jumlah' => 23004],
            ['bulan' => 10, 'tahun' => 2022, 'jumlah' => 19878],
            ['bulan' => 11, 'tahun' => 2022, 'jumlah' => 15798],
            ['bulan' => 12, 'tahun' => 2022, 'jumlah' => 16609],
            ['bulan' => 1, 'tahun' => 2023, 'jumlah' => 16296],
            ['bulan' => 2, 'tahun' => 2023, 'jumlah' => 15045],
            ['bulan' => 3, 'tahun' => 2023, 'jumlah' => 16300],
        ]);
    }
}
