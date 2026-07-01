<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Pemilik extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'pemilik';
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRoleAttribute()
    {
        return \App\Enums\Role::PEMILIK;
    }

    public function hasAvatar(): bool
    {
        return !empty($this->avatar);
    }

    public function avatarUrl(): string
    {
        if ($this->hasAvatar()) {
            return asset('storage/avatars/' . $this->avatar);
        }

        return '';
    }
}
