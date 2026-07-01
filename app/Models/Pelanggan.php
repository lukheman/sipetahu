<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'alamat',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function dataPenjualans(): HasMany
    {
        return $this->hasMany(DataPenjualan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function getNameAttribute()
    {
        return $this->nama_pelanggan;
    }

    public function getRoleAttribute()
    {
        return \App\Enums\Role::PELANGGAN;
    }

    public function hasAvatar()
    {
        return false;
    }

    public function avatarUrl()
    {
        return null;
    }
}
