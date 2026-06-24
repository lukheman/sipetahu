<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataPenjualan extends Model
{
    protected $table = 'data_penjualan';
    protected $primaryKey = 'id_data_penjualan';

    protected $fillable = [
        'tanggal',
        'jenis_pembeli',
        'id_distributor',
        'total_produksi',
        'total_penjualan',
    ];

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_data_penjualan', 'id_data_penjualan');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'id_distributor', 'id_distributor');
    }

    public function hasilPrediksi(): HasOne
    {
        return $this->hasOne(HasilPrediksi::class, 'id_data_penjualan');
    }

    protected static function booted()
    {
        static::deleting(function ($dataPenjualan) {
            $dataPenjualan->hasilPrediksi()->delete();
            $dataPenjualan->detailPenjualans()->delete();
        });
    }

}
