<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'alamat'
    ];

    public function dataPenjualans(): HasMany
    {
        return $this->hasMany(DataPenjualan::class, 'id_pelanggan', 'id_pelanggan');
    }
}
