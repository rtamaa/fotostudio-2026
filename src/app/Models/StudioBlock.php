<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StudioBlock extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'reason',
        'created_by',
        'is_recurring',
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

    // 🔥 FIX FINAL: auto isi created_by
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->created_by = $model->created_by ?? auth()->id();
        });
    }
}