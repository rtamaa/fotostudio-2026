<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Feedback extends Model
{
    // =========================
    // FILLABLE (WAJIB)
    // =========================
    protected $fillable = [
        'user_id',
        'message',

        // =========================
        // NEW PRO FIELDS
        // =========================
        'name',
        'status',
        'priority',
        'admin_reply',
    ];

    // =========================
    // CASTING (SAFE DEFAULT)
    // =========================
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $attributes = [
    'status' => 'new',
    'priority' => 'low',
    ];

    // =========================
    // RELATION: FEEDBACK -> USER
    // =========================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =========================
    // ACCESSOR (STATUS BADGE UI)
    // =========================
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'new' => '🔵 New',
            'read' => '🟡 Read',
            'archived' => '⚫ Archived',
            default => 'Unknown',
        };
    }

    // =========================
    // ACCESSOR (PRIORITY BADGE UI)
    // =========================
    public function getPriorityBadgeAttribute()
    {
        return match($this->priority) {
            'low' => '🟢 Low',
            'medium' => '🟠 Medium',
            'high' => '🔴 High',
            default => '🟢 Low',
        };
    }

    // =========================
    // HELPER: CHECK URGENCY
    // =========================
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }
}