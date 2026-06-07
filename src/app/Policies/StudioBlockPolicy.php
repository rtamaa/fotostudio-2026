<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudioBlock;

class StudioBlockPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, StudioBlock $studioBlock): bool
    {
        return $user->hasRole('admin');
    }
}