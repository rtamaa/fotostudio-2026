<?php

namespace App\Enums;

enum BookingStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case WAITING_CONFIRMATION = 'waiting_confirmation';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING => 'Menunggu Pembayaran',
            self::WAITING_CONFIRMATION => 'Menunggu Konfirmasi Admin',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Dibatalkan',
        };
    }

    public function canBeCancelled(): bool
    {
        return in_array($this, [self::DRAFT, self::PENDING, self::WAITING_CONFIRMATION]);
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}