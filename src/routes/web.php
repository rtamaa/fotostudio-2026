<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Livewire\Booking\Calendar;
use App\Livewire\Booking\BookingHistory;
use App\Livewire\Booking\UploadPaymentProof;
use App\Livewire\Profile\ProfileForm;

// Guest
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);
});

// Auth
Route::middleware(['auth'])->group(function () {

    Route::get('/booking/calendar', Calendar::class)
        ->name('booking.calendar');

    Route::get('/booking/history', BookingHistory::class)
        ->name('booking.history');

    Route::get('/profile', ProfileForm::class)
        ->name('profile.edit');

    /*
    |--------------------------------------------------------------------------
    | Upload Bukti Pembayaran
    |--------------------------------------------------------------------------
    */
    Route::get(
        '/booking/{booking}/payment-upload',
        UploadPaymentProof::class
    )->name('payment.upload');
});

// Public
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');