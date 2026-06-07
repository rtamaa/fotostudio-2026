<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'created_by', 'action', 'description',
        'old_data', 'new_data', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}