<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DataPenjualan extends Model
{
    protected $table = 'data_penjualan';
    protected $primaryKey = 'id_data_penjualan';

    protected $fillable = [
        'id_produk',
        'bulan',
        'tahun',
        'jumlah'
    ];

    public static function getWindowPenjualan($posisi, $id_produk, $jumlahSebelumnya = 3)
    {
        $start = max(0, $posisi - $jumlahSebelumnya);

        return self::where('id_produk', $id_produk)
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->skip($start)
            ->take($jumlahSebelumnya + 1)
            ->pluck('jumlah'); // hanya ambil jumlah
    }

    public function hasilPrediksi(): HasOne
    {
        return $this->hasOne(HasilPrediksi::class, 'id_data_penjualan');
    }

    public function produk(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
