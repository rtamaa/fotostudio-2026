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
        $end = Carbon::parse($date . ' 17:00:00');
        $slots = [];

        while ($start < $end) {
            $slotStart = $start->copy();
            $slotEnd = $start->copy()->addMinutes(20);

            if ($slotEnd <= $end) {
                $slots[] = [
                    'start' => $slotStart->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'display' => $slotStart->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                ];
            }

            $start->addMinutes(20);
        }

        return $slots;
    }

    public function getAvailableSlots(string $date): array
    {
        $allSlots = $this->generateTimeSlots($date);

        $booked = Booking::where('booking_date', $date)
            ->whereNotIn('booking_status', [
                BookingStatus::CANCELLED,
            ])
            ->get()
            ->map(fn ($b) => Carbon::parse($b->start_time)->format('H:i'))
            ->toArray();

        return array_values(
            array_filter(
                $allSlots,
                fn ($slot) => !in_array($slot['start'], $booked)
            )
        );
    }

    public function isSlotAvailable(string $date, string $startTime): bool
    {
        return !Booking::where('booking_date', $date)
            ->where('start_time', $startTime)
            ->whereNotIn('booking_status', [
                BookingStatus::CANCELLED,
            ])
            ->exists();
    }
}