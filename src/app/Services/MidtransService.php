<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use App\Models\Booking;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\BookingLog;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createTransaction(Booking $booking): array
    {
        $customer = $booking->user;
        $package = $booking->package;

        $payload = [
            'transaction_details' => [
                'order_id' => $booking->id,
                'gross_amount' => $package->price,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => $package->id,
                    'price' => $package->price,
                    'quantity' => 1,
                    'name' => $package->name,
                ]
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);

            $booking->update([
                'snap_token' => $snapToken,
                'midtrans_order_id' => $booking->id,
            ]);

            // =========================
            // FIX: PREVENT DUPLICATE PAYMENT
            // =========================
            $booking->payment()->updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'order_id' => $booking->id,
                    'gross_amount' => $package->price,
                    'status' => PaymentStatus::PENDING,
                ]
            );

            // =========================
            // BOOKING LOG
            // =========================
            BookingLog::create([
                'booking_id' => $booking->id,
                'created_by' => auth()->id() ?? null,
                'action' => 'payment_created_midtrans',
                'description' => 'Payment dibuat melalui Midtrans Snap',
                'new_data' => [
                    'order_id' => $booking->id,
                    'amount' => $package->price,
                ],
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken
            ];

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function handleWebhook(): void
    {
        try {
            $notif = new Notification();

            $orderId = $notif->order_id;
            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status;
            $paymentType = $notif->payment_type ?? null;
            $transactionId = $notif->transaction_id ?? null;

            $booking = Booking::findOrFail($orderId);
            $payment = Payment::where('order_id', $orderId)->first();

            DB::transaction(function () use (
                $booking,
                $payment,
                $transactionStatus,
                $fraudStatus,
                $paymentType,
                $transactionId,
                $notif
            ) {

                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {

                    if ($fraudStatus == 'accept') {

                        $booking->update([
                            'booking_status' => BookingStatus::CONFIRMED,
                            'paid_at' => now(),
                        ]);

                        if ($payment) {
                            $payment->update([
                                'status' => PaymentStatus::SUCCESS,
                                'transaction_id' => $transactionId,
                                'payment_type' => $paymentType,
                                'payload' => (array) $notif,
                                'paid_at' => now(),
                            ]);
                        }

                        BookingLog::create([
                            'booking_id' => $booking->id,
                            'action' => 'payment_success',
                            'description' => 'Payment berhasil & booking dikonfirmasi',
                        ]);
                    }
                }

                elseif ($transactionStatus == 'cancel' || $transactionStatus == 'deny') {

                    $booking->update([
                        'booking_status' => BookingStatus::CANCELLED
                    ]);

                    if ($payment) {
                        $payment->update([
                            'status' => PaymentStatus::FAILED,
                            'payload' => (array) $notif,
                        ]);
                    }

                    BookingLog::create([
                        'booking_id' => $booking->id,
                        'action' => 'payment_failed',
                        'description' => 'Payment gagal / ditolak',
                    ]);
                }

                elseif ($transactionStatus == 'expire') {

                    $booking->update([
                        'booking_status' => BookingStatus::EXPIRED
                    ]);

                    if ($payment) {
                        $payment->update([
                            'status' => PaymentStatus::EXPIRED,
                            'payload' => (array) $notif,
                        ]);
                    }

                    BookingLog::create([
                        'booking_id' => $booking->id,
                        'action' => 'payment_expired',
                        'description' => 'Payment expired',
                    ]);
                }
            });

        } catch (\Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            throw $e;
        }
    }
}