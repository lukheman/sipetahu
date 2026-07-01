<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataProduksi extends Model
{
    protected $table = 'data_produksi';
    protected $primaryKey = 'id_data_produksi';

    protected $fillable = [
        'tanggal',
        'total_produksi',
    ];

    public function detailProduksis()
    {
        return $this->hasMany(DetailProduksi::class, 'id_data_produksi', 'id_data_produksi');
    }

    protected static function booted()
    {
        static::deleting(function ($dataProduksi) {
            $dataProduksi->detailProduksis()->delete();
        });
    }
}
