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
            $table->enum('jenis_pembeli', ['pelanggan', 'langsung'])->default('langsung')->after('tanggal');
            $table->unsignedBigInteger('id_pelanggan')->nullable()->after('jenis_pembeli');
            
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
            $table->dropColumn(['jenis_pembeli', 'id_pelanggan']);
        });
    }
};
