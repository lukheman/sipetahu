<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case PEMILIK = 'pemilik';
    case PELANGGAN = 'pelanggan';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    /**
     * Get role label in Indonesian
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::PEMILIK => 'Pemilik',
            self::PELANGGAN => 'Pelanggan',
        };
    }

    /**
     * Get role badge color
     */
    public function color(): string
    {
        return match ($this) {
            self::ADMIN => 'danger',
            self::PEMILIK => 'primary',
            self::PELANGGAN => 'success',
        };
    }

    /**
     * Get role icon
     */
    public function icon(): string
    {
        return match ($this) {
            self::ADMIN => 'fas fa-user-shield',
            self::PEMILIK => 'fas fa-user-tie',
            self::PELANGGAN => 'fas fa-user',
        };
    }

}
