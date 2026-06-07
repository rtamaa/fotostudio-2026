<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    // 🔥 TAMBAHAN PENTING (FIX ERROR isAdmin)
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}