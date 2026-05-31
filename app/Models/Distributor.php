<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributor';
    protected $primaryKey = 'id_distributor';

    protected $fillable = [
        'nama_distributor',
        'no_hp',
        'alamat'
    ];

    public function dataPenjualan()
    {
        return $this->hasMany(DataPenjualan::class, 'id_distributor', 'id_distributor');
    }
}
