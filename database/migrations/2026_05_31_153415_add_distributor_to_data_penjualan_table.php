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
            $table->enum('jenis_pembeli', ['distributor', 'langsung'])->default('langsung')->after('tanggal');
            $table->unsignedBigInteger('id_distributor')->nullable()->after('jenis_pembeli');
            
            $table->foreign('id_distributor')->references('id_distributor')->on('distributor')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->dropForeign(['id_distributor']);
            $table->dropColumn(['jenis_pembeli', 'id_distributor']);
        });
    }
};
