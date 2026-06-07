<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Package;

class PackagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Package $package): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Package $package): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->hasRole('admin');
    }
}