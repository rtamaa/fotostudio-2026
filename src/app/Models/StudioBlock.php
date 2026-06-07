<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudioBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'date', 'start_time', 'end_time', 'reason', 'created_by', 'is_recurring'
    ];

    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_recurring' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}