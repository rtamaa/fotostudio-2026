<?php

namespace App\Services;

use App\Jobs\SendBookingNotificationJob;
use App\Models\Booking;
use App\Models\Package;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingService
{
    protected SlotService $slotService;

    public function __construct(SlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    public function createBooking(array $data): array
    {
        $package = Package::findOrFail($data['package_id']);

        $endTime = Carbon::parse(
            $data['booking_date'] . ' ' . $data['start_time']
        )->addMinutes($package->duration);

        if (
            !$this->slotService->isSlotAvailable(
                $data['booking_date'],
                $data['start_time']
            )
        ) {
            return [
                'success' => false,
                'message' => 'Slot sudah dibooking orang lain!'
            ];
        }

        DB::beginTransaction();

        try {

            $booking = Booking::create([
                'user_id'         => auth()->id(),
                'package_id'      => $package->id,
                'booking_date'    => $data['booking_date'],
                'start_time'      => $data['start_time'],
                'end_time'        => $endTime->format('H:i:s'),
                'special_request' => $data['special_request'] ?? null,
                'booking_status'  => BookingStatus::PENDING,
            ]);

            SendBookingNotificationJob::dispatch($booking);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'booking' => $booking,
            ];

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Create Booking Error', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat booking',
            ];
        }
    }

    public function confirmBooking(Booking $booking): array
    {
        $booking->update([
            'booking_status' => BookingStatus::CONFIRMED,
            'paid_at' => now(),
        ]);

        return [
            'success' => true,
            'message' => 'Pembayaran berhasil dikonfirmasi',
        ];
    }

    public function cancelBooking(
        Booking $booking,
        string $reason
    ): array {
        $booking->update([
            'booking_status' => BookingStatus::CANCELLED,
            'cancellation_reason' => $reason,
        ]);

        return [
            'success' => true,
            'message' => 'Booking berhasil dibatalkan',
        ];
    }
}