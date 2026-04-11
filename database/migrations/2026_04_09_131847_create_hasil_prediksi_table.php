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
        Schema::create('hasil_prediksi', function (Blueprint $table) {
            $table->id('id_hasil_prediksi');
            $table->foreignId('id_data_penjualan')->constrained('data_penjualan', 'id_data_penjualan');
            $table->float('wma')->nullable(); // 3 month wma
            $table->float('error')->nullable();
            $table->float('mad')->nullable();
            $table->float('mse')->nullable();
            $table->float('mape')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_prediksi');
    }
};
