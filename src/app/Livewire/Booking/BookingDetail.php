<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use App\Models\Booking;
use App\Services\BookingService;

class BookingDetail extends Component
{
    public $booking;

    public function mount($id)
    {
        $this->booking = Booking::with(['package', 'payment'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }

    public function cancelBooking()
    {
        $result = app(BookingService::class)->cancelBooking($this->booking, 'Dibatalkan oleh pelanggan');
        session()->flash('message', $result['message']);
        return redirect()->route('booking.history');
    }

    public function render()
    {
        return view('livewire.booking.booking-detail', [
            'booking' => $this->booking,
        ])->layout('components.layouts.livewire');
    }
}