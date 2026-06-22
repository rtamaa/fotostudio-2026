<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // =========================
    // USER: lihat booking sendiri
    // =========================
    public function index(Request $request)
    {
        return Booking::with([
                'package:id,name,price,duration'
            ])
            ->where('user_id', $request->user()->id)
            ->select([
                'id',
                'user_id',
                'package_id',
                'booking_date',
                'start_time',
                'end_time',
                'booking_status'
            ])
            ->latest()
            ->get();
    }

    // =========================
    // ADMIN: lihat semua booking
    // =========================
    public function adminIndex(Request $request)
    {
        // cek role admin (AMAN dari Spatie)
        if (!$request->user()->hasRole('admin')) {
            return response()->json([
                'message' => 'Forbidden'
            ], 403);
        }

        return Booking::with([
                'user:id,name,email',
                'package:id,name,price,duration'
            ])
            ->select([
                'id',
                'user_id',
                'package_id',
                'booking_date',
                'start_time',
                'end_time',
                'booking_status'
            ])
            ->latest()
            ->get();
    }
}