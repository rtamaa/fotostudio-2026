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
        return $this->roles()
            ->where('name', 'admin')
            ->where('guard_name', 'admin')
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}