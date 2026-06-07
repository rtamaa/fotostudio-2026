<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentStatus;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'payment_type',
        'gross_amount',
        'status',
        'payload',
        'paid_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'paid_at' => 'datetime',
        'status' => PaymentStatus::class,
    ];

    // =========================
    // RELATIONSHIP
    // =========================
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // =========================
    // STATUS HELPERS
    // =========================
    public function isSuccess(): bool
    {
        return $this->status === PaymentStatus::SUCCESS;
    }

    public function isPending(): bool
    {
        return $this->status === PaymentStatus::PENDING;
    }

    public function isFailed(): bool
    {
        return $this->status === PaymentStatus::FAILED;
    }

    // =========================
    // ACCESSOR (INI YANG BIKIN FILAMENT LEBIH AMAN)
    // =========================
    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? '-';
    }
}