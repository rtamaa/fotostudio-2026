<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendBookingCreated(Booking $booking): void
    {
        $booking->load(['user', 'package']);

        // ❗ FIX: tidak pakai route() karena tidak ada
        $uploadUrl = 'Silakan login ke aplikasi → Booking → Upload Bukti Pembayaran setelah transfer.';

        $message = $this->buildMessage($booking, $uploadUrl);

        // EMAIL
        if (!empty($booking->user?->email)) {
            $this->sendEmail(
                $booking->user->email,
                'Instruksi Pembayaran Booking',
                nl2br($message)
            );
        }

        // WHATSAPP
        $this->sendWhatsapp(
            $booking->user?->phone,
            $message
        );
    }

    /**
     * Build WhatsApp message
     */
    protected function buildMessage(Booking $booking, string $uploadUrl): string
    {
        return "
Halo {$booking->user->name},

Booking Anda berhasil dibuat.

Paket : {$booking->package->name}
Tanggal : {$booking->booking_date->format('d M Y')}
Jam : {$booking->start_time}

Total Pembayaran :
Rp " . number_format($booking->package->price, 0, ',', '.') . "

Silakan transfer ke:

Bank BCA
No Rekening : 1234567890
Atas Nama : Studio Self Photo

Upload bukti pembayaran:
{$uploadUrl}
";
    }

    /**
     * Send Email
     */
    protected function sendEmail(
        string $email,
        string $subject,
        string $content
    ): void {
        Mail::html($content, function ($mail) use ($email, $subject) {
            $mail->to($email)
                 ->subject($subject);
        });
    }

    /**
     * Send WhatsApp (FINAL DEBUG VERSION)
     */
    protected function sendWhatsapp(
        ?string $phone,
        string $message
    ): void {

        if (empty($phone)) {
            logger()->warning('WA skipped: phone empty');
            return;
        }

        $phone = trim($phone);

        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        try {
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN')
            ])
            ->asForm()
            ->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'message' => $message,
            ]);

            logger()->info('FONNTE RESPONSE', [
                'phone' => $phone,
                'status_code' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->failed()) {
                logger()->error('FONNTE FAILED', [
                    'phone' => $phone,
                    'body' => $response->body()
                ]);
            }

        } catch (\Throwable $e) {
            logger()->error('WhatsApp EXCEPTION', [
                'error' => $e->getMessage(),
                'phone' => $phone
            ]);
        }
    }
}