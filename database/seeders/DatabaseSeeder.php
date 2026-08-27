<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Pemilik;
use App\Models\Produk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Admin
        Admin::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // Seed Pemilik
        Pemilik::create([
            'name' => 'Pemilik',
            'email' => 'pemilik@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        // Seed Produk
        Produk::create([
            'nama_produk' => 'Tahu',
            'harga' => 500,
            'deskripsi' => 'Tahu putih segar',
        ]);
    }
}
