<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingStep extends Model
{
    // =========================
    // FILLABLE (WAJIB UNTUK CRUD)
    // =========================
    protected $fillable = [
        'step_number',
        'title',
        'description',
        'icon',
    ];

    // =========================
    // OPTIONAL: SORTING HELPER
    // =========================
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_number', 'asc');
    }
}