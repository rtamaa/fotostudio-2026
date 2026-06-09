<?php

namespace App\Livewire\Booking;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Booking;
use App\Enums\BookingStatus;
use App\Models\BookingLog;
use App\Services\NotificationService;

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

        /**
         * =========================
         * 📌 BOOKING LOG (ADMIN TRACKING)
         * =========================
         */
        BookingLog::create([
            'booking_id' => $this->booking->id,
            'action' => 'payment_uploaded',
            'description' => 'User upload bukti pembayaran',
        ]);

        /**
         * =========================
         * 📌 PAYMENT STATUS SYNC (AMAN TAMBAHAN)
         * =========================
         * sinkron ke payment table kalau ada relasi
         */
        if ($this->booking->payment) {
            $this->booking->payment->update([
                'status' => \App\Enums\PaymentStatus::PENDING,
            ]);
        }

        /**
         * =========================
         * 📌 NOTIFICATION (WA / EMAIL)
         * =========================
         * aman walaupun service error
         */
        try {
            app(NotificationService::class)
                ->sendPaymentUploaded($this->booking);
        } catch (\Throwable $e) {
            logger()->error('Payment upload notification failed', [
                'error' => $e->getMessage(),
                'booking_id' => $this->booking->id,
            ]);
        }

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