<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Produk;
use App\Enums\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Users
        if (User::count() === 0) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => Role::ADMIN,
            ]);

            User::create([
                'name' => 'Pemilik',
                'email' => 'pemilik@gmail.com',
                'password' => Hash::make('password123'),
                'role' => Role::PEMILIK,
            ]);
        }

        // Seed Produk
        if (Produk::count() === 0) {
            Produk::create([
                'nama_produk' => 'Tahu',
                'harga' => 500,
                'deskripsi' => 'Tahu putih segar',
            ]);
        }
    }
}
