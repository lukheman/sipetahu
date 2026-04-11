<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

      User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => Role::ADMIN
            ]
        );

        User::firstOrCreate(
            ['email' => 'pemilik@gmail.com'],
            [
                'name' => 'Pemilik',
                'password' => Hash::make('password123'),
                'role' => Role::PEMILIK
            ]
        );

        $this->call(DataPenjualanSeeder::class);

    }
}
