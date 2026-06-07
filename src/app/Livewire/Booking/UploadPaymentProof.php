<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use App\Enums\BookingStatus;

class UploadPaymentProof extends Component
{
    use WithFileUploads;

    public Booking $booking;

    public $proof;

    public function save()
    {
        $path = $this->proof->store(
            'payment-proofs',
            'public'
        );

        $this->booking->update([
            'payment_proof' => $path,
            'booking_status' => BookingStatus::WAITING_CONFIRMATION,
            'paid_at' => now(),
        ]);

        session()->flash(
            'success',
            'Bukti pembayaran berhasil diupload'
        );
    }

    public function render()
    {
        return view(
            'livewire.booking.upload-payment-proof'
        );
    }
}