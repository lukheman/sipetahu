<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_data_penjualan',
        'id_produk',
        'produksi',
        'penjualan',
    ];

    public function dataPenjualan()
    {
        return $this->belongsTo(DataPenjualan::class, 'id_data_penjualan', 'id_data_penjualan');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
