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
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pelanggan')->nullable()->after('tanggal');
            
            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->dropForeign(['id_pelanggan']);
            $table->dropColumn(['id_pelanggan']);
        });
    }
};
