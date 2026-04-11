<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'nama_produk',
        'harga',
        'deskripsi',
    ];

    public function dataPenjualan(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(DataPenjualan::class, 'id_produk', 'id_produk');
    }
}
