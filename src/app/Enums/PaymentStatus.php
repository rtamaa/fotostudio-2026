<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Menunggu',
            self::SUCCESS => 'Berhasil',
            self::FAILED => 'Gagal',
            self::EXPIRED => 'Kadaluarsa',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}