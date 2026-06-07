<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Package;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'package_id',
        'booking_date',
        'start_time',
        'end_time',
        'special_request',
        'booking_status',
        'paid_at',
        'cancellation_reason',
    ];

    protected $casts = [
        'booking_date'   => 'date',
        'booking_status' => BookingStatus::class,
        'paid_at'        => 'datetime',
    ];

    // =========================
    // RELATIONSHIPS
    // =========================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // =========================
    // STATUS HELPERS
    // =========================

    public function isPending(): bool
    {
        return $this->booking_status === BookingStatus::PENDING;
    }

    public function isConfirmed(): bool
    {
        return $this->booking_status === BookingStatus::CONFIRMED;
    }

    public function isCancelled(): bool
    {
        return $this->booking_status === BookingStatus::CANCELLED;
    }

    // =========================
    // PERMISSION LOGIC (FIXED)
    // =========================
    // ❗ jangan pakai hasRole langsung kalau belum pasti trait aktif
    // pakai Spatie permission safe check

    public function canBeRescheduledBy(?User $user): bool
    {
        if (!$user) return false;

        return $user->hasRole('admin')
            && in_array($this->booking_status, [
                BookingStatus::PENDING,
                BookingStatus::CONFIRMED,
            ]);
    }

    public function canBeCancelledBy(?User $user): bool
    {
        if (!$user) return false;

        return $user->hasRole('admin')
            && $this->booking_status !== BookingStatus::CANCELLED;
    }

    public function canBeConfirmedBy(?User $user): bool
    {
        if (!$user) return false;

        return $user->hasRole('admin')
            && $this->booking_status === BookingStatus::PENDING;
    }
}