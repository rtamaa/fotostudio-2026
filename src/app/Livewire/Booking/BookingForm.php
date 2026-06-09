<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Services\BookingService;
use App\Models\Package;
use App\Models\BookingLog; // ✅ TAMBAHAN
use Illuminate\Support\Facades\Auth; // ✅ TAMBAHAN

class BookingForm extends Component
{
    public $packageId, $bookingDate, $startTime, $specialRequest = '', $isProcessing = false;

    public function mount($packageId, $bookingDate, $startTime)
    {
        $this->packageId = $packageId;
        $this->bookingDate = $bookingDate;
        $this->startTime = $startTime;
    }

    public function processBooking()
    {
        $this->isProcessing = true;

        $result = app(BookingService::class)->createBooking([
            'package_id' => $this->packageId,
            'booking_date' => $this->bookingDate,
            'start_time' => $this->startTime,
            'special_request' => $this->specialRequest,
        ]);

        // =========================
        // ✅ TAMBAHAN: LOG BOOKING
        // =========================
        if ($result['success'] && isset($result['booking'])) {
            BookingLog::create([
                'booking_id' => $result['booking']->id,
                'created_by' => Auth::id(),
                'action' => 'BOOKED',
                'description' => 'User membuat booking melalui form',
                'new_data' => [
                    'package_id' => $this->packageId,
                    'booking_date' => $this->bookingDate,
                    'start_time' => $this->startTime,
                    'special_request' => $this->specialRequest,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session()->flash('success', 'Booking berhasil!');
            return redirect()->route('booking.history');
        }

        session()->flash('error', $result['message']);
        $this->isProcessing = false;
    }

    public function render()
    {
        return view('livewire.booking.booking-form', [
            'package' => Package::find($this->packageId)
        ]);
    }
}