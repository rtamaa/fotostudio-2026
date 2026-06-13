<?php

namespace App\Services;

use App\Models\Booking;
use App\Enums\BookingStatus;
use Carbon\Carbon;

class SlotService
{
    public function generateTimeSlots(string $date): array
    {
        $start = Carbon::parse($date . ' 09:00:00');
        $end   = Carbon::parse($date . ' 17:00:00');

        $slots = [];

        while ($start < $end) {

            $slotStart = $start->copy();
            $slotEnd   = $start->copy()->addMinutes(20);

            if ($slotEnd <= $end) {
                $slots[] = [
                    'start'   => $slotStart->format('H:i'),
                    'end'     => $slotEnd->format('H:i'),
                    'display' => $slotStart->format('H:i') . ' - ' . $slotEnd->format('H:i'),

                    // default state
                    'status'  => 'available',
                ];
            }

            $start->addMinutes(20);
        }

        return $slots;
    }

    public function getAvailableSlots(string $date): array
    {
        $allSlots = $this->generateTimeSlots($date);

        $bookings = Booking::where('booking_date', $date)
            ->where(function ($q) {
                $q->where('booking_status', '!=', 'cancelled')
                  ->where('booking_status', '!=', BookingStatus::CANCELLED->value);
            })
            ->get()
            ->keyBy(fn ($b) => Carbon::parse($b->start_time)->format('H:i'));

        return array_map(function ($slot) use ($bookings) {

            $time = $slot['start'];
            $booking = $bookings[$time] ?? null;

            // =========================
            // SLOT STATUS ENGINE
            // =========================
            if (!$booking) {
                $slot['status'] = 'available';
                return $slot;
            }

            // AMAN: support enum + string DB
            $status = $booking->booking_status instanceof BookingStatus
                ? $booking->booking_status
                : BookingStatus::tryFrom($booking->booking_status);

            $slot['status'] = match ($status) {

                BookingStatus::PENDING   => 'pending',
                BookingStatus::CONFIRMED => 'confirmed',
                BookingStatus::CANCELLED => 'blocked',

                default => 'available',
            };

            return $slot;

        }, $allSlots);
    }

    public function isSlotAvailable(string $date, string $startTime): bool
    {
        return !Booking::where('booking_date', $date)
            ->where('start_time', $startTime)
            ->whereNotIn('booking_status', [
                BookingStatus::CANCELLED,
                'cancelled',
            ])
            ->exists();
    }
}