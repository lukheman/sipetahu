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
        'produksi_tahu_kecil',
        'produksi_tahu_besar',
        'total_produksi',
        'penjualan_tahu_kecil',
        'penjualan_tahu_besar',
        'total_penjualan',
        'tahu_kembali_kecil',
        'tahu_kembali_besar'
    ];

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
        });
    }
}
