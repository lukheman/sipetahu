<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_penjualan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_data_penjualan');
            $table->unsignedBigInteger('id_produk');
            $table->integer('produksi')->default(0);
            $table->integer('penjualan')->default(0);
            $table->integer('tahu_kembali')->default(0);
            $table->timestamps();

            $table->foreign('id_data_penjualan')->references('id_data_penjualan')->on('data_penjualan')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('restrict');
        });

        // Ensure products exist before migration
        $produkTahuBesar = Produk::firstOrCreate(
            ['nama_produk' => 'Tahu Besar'],
            ['harga' => 0]
        );
        $produkTahuKecil = Produk::firstOrCreate(
            ['nama_produk' => 'Tahu Kecil'],
            ['harga' => 0]
        );

        // Migrate existing data
        DB::table('data_penjualan')->orderBy('id_data_penjualan')->chunk(100, function ($records) use ($produkTahuBesar, $produkTahuKecil) {
            $details = [];
            foreach ($records as $row) {
                if ($row->produksi_tahu_besar > 0 || $row->penjualan_tahu_besar > 0 || $row->tahu_kembali_besar > 0) {
                    $details[] = [
                        'id_data_penjualan' => $row->id_data_penjualan,
                        'id_produk' => $produkTahuBesar->id_produk,
                        'produksi' => $row->produksi_tahu_besar,
                        'penjualan' => $row->penjualan_tahu_besar,
                        'tahu_kembali' => $row->tahu_kembali_besar,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                if ($row->produksi_tahu_kecil > 0 || $row->penjualan_tahu_kecil > 0 || $row->tahu_kembali_kecil > 0) {
                    $details[] = [
                        'id_data_penjualan' => $row->id_data_penjualan,
                        'id_produk' => $produkTahuKecil->id_produk,
                        'produksi' => $row->produksi_tahu_kecil,
                        'penjualan' => $row->penjualan_tahu_kecil,
                        'tahu_kembali' => $row->tahu_kembali_kecil,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if (!empty($details)) {
                DB::table('detail_penjualan')->insert($details);
            }
        });

        // Drop the hardcoded columns from data_penjualan
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->dropColumn([
                'produksi_tahu_kecil',
                'produksi_tahu_besar',
                'penjualan_tahu_kecil',
                'penjualan_tahu_besar',
                'tahu_kembali_kecil',
                'tahu_kembali_besar'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_penjualan', function (Blueprint $table) {
            $table->integer('produksi_tahu_kecil')->default(0);
            $table->integer('produksi_tahu_besar')->default(0);
            $table->integer('penjualan_tahu_kecil')->default(0);
            $table->integer('penjualan_tahu_besar')->default(0);
            $table->integer('tahu_kembali_kecil')->default(0);
            $table->integer('tahu_kembali_besar')->default(0);
        });

        // Migrate back the data
        DB::table('detail_penjualan')
            ->join('produk', 'detail_penjualan.id_produk', '=', 'produk.id_produk')
            ->orderBy('detail_penjualan.id_detail')
            ->chunk(100, function ($details) {
                foreach ($details as $detail) {
                    if ($detail->nama_produk === 'Tahu Besar') {
                        DB::table('data_penjualan')->where('id_data_penjualan', $detail->id_data_penjualan)->update([
                            'produksi_tahu_besar' => DB::raw("produksi_tahu_besar + {$detail->produksi}"),
                            'penjualan_tahu_besar' => DB::raw("penjualan_tahu_besar + {$detail->penjualan}"),
                            'tahu_kembali_besar' => DB::raw("tahu_kembali_besar + {$detail->tahu_kembali}"),
                        ]);
                    } elseif ($detail->nama_produk === 'Tahu Kecil') {
                        DB::table('data_penjualan')->where('id_data_penjualan', $detail->id_data_penjualan)->update([
                            'produksi_tahu_kecil' => DB::raw("produksi_tahu_kecil + {$detail->produksi}"),
                            'penjualan_tahu_kecil' => DB::raw("penjualan_tahu_kecil + {$detail->penjualan}"),
                            'tahu_kembali_kecil' => DB::raw("tahu_kembali_kecil + {$detail->tahu_kembali}"),
                        ]);
                    }
                }
            });

        Schema::dropIfExists('detail_penjualan');
    }
};
