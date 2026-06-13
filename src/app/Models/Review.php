<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;

class Review extends Model
{
    // =========================
    // FILLABLE (WAJIB)
    // =========================
    protected $fillable = [
        'user_id',
        'booking_id',

        'rating',
        'comment',

        // =========================
        // PRO SYSTEM FIELDS
        // =========================
        'name',
        'status',
        'is_verified',
    ];

    // =========================
    // CASTING (BIAR AMAN BOOLEAN)
    // =========================
    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // =========================
    // RELATION: REVIEW -> USER
    // =========================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================
    // RELATION: REVIEW -> BOOKING
    // =========================
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // =========================
    // ACCESSOR: VERIFIED BADGE
    // =========================
    public function getVerifiedBadgeAttribute()
    {
        return $this->is_verified
            ? '✔ Verified Customer'
            : null;
    }

    // =========================
    // ACCESSOR: STAR DISPLAY (UI HELPER)
    // =========================
    public function getStarsAttribute()
    {
        $rating = (int) $this->rating;

        // safety biar tidak error kalau data corrupt
        $rating = max(0, min(5, $rating));

        return str_repeat('⭐', $rating)
             . str_repeat('☆', 5 - $rating);
    }

    // =========================
    // HELPER: CHECK REVIEW VALID
    // =========================
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
}