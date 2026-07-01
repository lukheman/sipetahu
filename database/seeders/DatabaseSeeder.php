<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Pemilik;
use App\Models\Produk;

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
        // Seed Pelanggan
        \App\Models\Pelanggan::create([
            'nama_pelanggan' => 'Budi Santoso',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No. 45, Jakarta Selatan',
            'email' => 'pelanggan1@gmail.com',
            'password' => Hash::make('password123'),
            ]);

        \App\Models\Pelanggan::create([
            'nama_pelanggan' => 'Siti Aminah',
            'no_hp' => '089876543210',
            'alamat' => 'Perum. Indah Makmur Blok B/12, Depok',
            'email' => 'pelanggan2@gmail.com',
            'password' => Hash::make('password123'),
            ]);
    }
}
