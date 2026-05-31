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
            $table->date('tanggal');
            $table->integer('produksi_tahu_kecil')->default(0);
            $table->integer('produksi_tahu_besar')->default(0);
            $table->integer('total_produksi')->default(0);
            $table->integer('penjualan_tahu_kecil')->default(0);
            $table->integer('penjualan_tahu_besar')->default(0);
            $table->integer('total_penjualan')->default(0);
            $table->integer('tahu_kembali_kecil')->default(0);
            $table->integer('tahu_kembali_besar')->default(0);
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
