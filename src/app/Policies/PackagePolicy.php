<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Package;

class PackagePolicy
{
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Package $package): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Package $package): bool
    {
        return $user->isAdmin();
    }
}