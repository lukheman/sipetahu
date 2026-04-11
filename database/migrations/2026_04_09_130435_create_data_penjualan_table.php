<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_penjualan', function (Blueprint $table) {
            $table->id('id_data_penjualan');
            $table->integer('bulan');
            $table->integer('tahun');
            $table->decimal('jumlah'); // dalam kilogram (kg)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_penjualan');
    }
};
