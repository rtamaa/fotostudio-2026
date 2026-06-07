<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Models\Booking;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRole::class,
    ];

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // bisa pakai salah satu:
        return $this->hasRole('admin'); // 🔥 ini sekarang valid
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}