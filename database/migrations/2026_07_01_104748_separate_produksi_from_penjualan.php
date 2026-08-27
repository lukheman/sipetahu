<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create data_produksi
        Schema::create('data_produksi', function (Blueprint $table) {
            $table->id('id_data_produksi');
            $table->date('tanggal');
            $table->integer('total_produksi')->default(0);
            $table->timestamps();
        });

        // 2. Create detail_produksi
        Schema::create('detail_produksi', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_data_produksi');
            $table->unsignedBigInteger('id_produk');
            $table->integer('produksi')->default(0);
            $table->timestamps();

            $table->foreign('id_data_produksi')->references('id_data_produksi')->on('data_produksi')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('restrict');
        });

        // 3. Migrate data
        $penjualanRecords = DB::table('data_penjualan')->where('total_produksi', '>', 0)->get();
        foreach ($penjualanRecords as $penj) {
            $id_data_produksi = DB::table('data_produksi')->insertGetId([
                'tanggal' => $penj->tanggal,
                'total_produksi' => $penj->total_produksi,
                'created_at' => $penj->created_at,
                'updated_at' => $penj->updated_at,
            ]);

            $details = DB::table('detail_penjualan')
                ->where('id_data_penjualan', $penj->id_data_penjualan)
                ->where('produksi', '>', 0)
                ->get();

            foreach ($details as $det) {
                DB::table('detail_produksi')->insert([
                    'id_data_produksi' => $id_data_produksi,
                    'id_produk' => $det->id_produk,
                    'produksi' => $det->produksi,
                    'created_at' => $det->created_at,
                    'updated_at' => $det->updated_at,
                ]);
            }
        }

        // 4. Clean up old columns
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->dropColumn('total_produksi');
        });

        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->dropColumn('produksi');
        });

        // Remove records that have total_penjualan = 0 (since they were purely produksi)
        DB::table('data_penjualan')->where('total_penjualan', 0)->delete();
    }

    public function down(): void
    {
        // Add back the columns
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->integer('total_produksi')->default(0);
        });

        Schema::table('detail_penjualan', function (Blueprint $table) {
            $table->integer('produksi')->default(0);
        });

        // Move data back
        $produksiRecords = DB::table('data_produksi')->get();
        foreach ($produksiRecords as $prod) {
            $id_data_penjualan = DB::table('data_penjualan')->insertGetId([
                'tanggal' => $prod->tanggal,
                'total_produksi' => $prod->total_produksi,
                'total_penjualan' => 0,
                'created_at' => $prod->created_at,
                'updated_at' => $prod->updated_at,
            ]);

            $details = DB::table('detail_produksi')
                ->where('id_data_produksi', $prod->id_data_produksi)
                ->get();

            foreach ($details as $det) {
                DB::table('detail_penjualan')->insert([
                    'id_data_penjualan' => $id_data_penjualan,
                    'id_produk' => $det->id_produk,
                    'produksi' => $det->produksi,
                    'penjualan' => 0,
                    'created_at' => $det->created_at,
                    'updated_at' => $det->updated_at,
                ]);
            }
        }

        Schema::dropIfExists('detail_produksi');
        Schema::dropIfExists('data_produksi');
    }
};
