<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Booking;

class BookingPolicy
{
    public function view(User $user, Booking $booking): bool
    {
        return $user->isAdmin() || $user->id === $booking->user_id;
    }

    public function cancel(User $user, Booking $booking): bool
    {
        return $booking->canBeCancelledBy($user);
    }

    public function reschedule(User $user, Booking $booking): bool
    {
        return $user->isAdmin() && $booking->canBeRescheduledBy($user);
    }

    public function confirmPayment(User $user, Booking $booking): bool
    {
        return $user->isAdmin() && $booking->isPending();
    }
}