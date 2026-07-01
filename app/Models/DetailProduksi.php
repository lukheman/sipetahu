<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailProduksi extends Model
{
    protected $table = 'detail_produksi';
    protected $primaryKey = 'id_detail';

    protected $fillable = [
        'id_data_produksi',
        'id_produk',
        'produksi',
    ];

    public function dataProduksi()
    {
        return $this->belongsTo(DataProduksi::class, 'id_data_produksi', 'id_data_produksi');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
