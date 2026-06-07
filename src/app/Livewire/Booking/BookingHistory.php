<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Models\Booking;
use App\Services\BookingService;

class BookingHistory extends Component
{
    public $bookings, $statusFilter = 'all';

    public function mount()
    {
        $this->loadBookings();
    }

    public function loadBookings()
    {
        $query = Booking::with('package')->where('user_id', auth()->id())->orderBy('created_at', 'desc');
        if ($this->statusFilter !== 'all') $query->where('booking_status', $this->statusFilter);
        $this->bookings = $query->get();
    }

    public function cancelBooking($bookingId)
    {
        $booking = Booking::where('id', $bookingId)->where('user_id', auth()->id())->first();
        if ($booking) {
            app(BookingService::class)->cancelBooking($booking, 'Dibatalkan oleh pelanggan');
            $this->loadBookings();
        }
    }

    public function render()
    {
        return view('livewire.booking.booking-history')->layout('components.layouts.livewire');
    }
}