<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPrediksi extends Model
{
    protected $table = 'hasil_prediksi';
    protected $primaryKey = 'id_hasil_prediksi';

    protected $guarded = [];


    public function dataPenjualan(): BelongsTo {
        return $this->belongsTo(DataPenjualan::class, 'id_data_penjualan');
    }

    public static function getAvgMAD() : float {

        return (float) self::query()->avg('mad');

    }

    public static function getAvgMSE(): float {
        return (float) self::query()->avg('mse');
    }

}
