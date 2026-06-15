<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
use App\Models\BookingLog;
use App\Models\Review;

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // =========================
    // PAYMENT RELATION (AMAN)
    // =========================
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // =========================
    // BOOKING LOGS (AMAN)
    // =========================
    public function logs()
    {
        return $this->hasMany(BookingLog::class);
    }

    // =========================
    // REVIEW RELATION (BARU)
    // =========================
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // =========================
    // OPTIONAL HELPER
    // =========================
    public function hasPayment(): bool
    {
        return $this->payment()->exists();
    }

    public function isPaid(): bool
    {
        return $this->payment?->status === \App\Enums\PaymentStatus::SUCCESS;
    }

    // =========================
    // STATUS HELPERS
    // =========================
    public function isPending(): bool
    {
        return $this->booking_status === BookingStatus::PENDING;
    }

    public function canBeRescheduledBy($user): bool
    {
        return $user?->hasRole('admin')
            && in_array($this->booking_status, [
                BookingStatus::PENDING,
                BookingStatus::CONFIRMED,
            ]);
    }

    public function canBeCancelledBy($user): bool
    {
        return $user?->hasRole('admin')
            && $this->booking_status !== BookingStatus::CANCELLED;
    }
}