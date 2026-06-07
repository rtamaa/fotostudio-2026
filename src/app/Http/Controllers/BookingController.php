<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function success()
    {
        return view('booking-success');
    }

    public function error()
    {
        return view('booking-error');
    }
}