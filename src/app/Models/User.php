<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasRoles;

    protected string $guard_name = 'admin';

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

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }
}