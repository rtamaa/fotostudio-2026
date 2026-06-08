<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Livewire\Booking\Calendar;
use App\Livewire\Booking\BookingHistory;
use App\Livewire\Booking\UploadPaymentProof;
use App\Livewire\Profile\ProfileForm;

/*
|--------------------------------------------------------------------------
| Public Home
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| Guest Routes (belum login)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/booking/calendar', Calendar::class)
        ->name('booking.calendar');

    Route::get('/booking/history', BookingHistory::class)
        ->name('booking.history');

    Route::get('/profile', ProfileForm::class)
        ->name('profile.edit');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});


/*
|--------------------------------------------------------------------------
| DEBUG ROUTE (WAJIB DI LUAR middleware auth/guest)
|--------------------------------------------------------------------------
*/
Route::get('/debug-guards', function () {
    return [
        'web' => auth('web')->user()?->email,
        'admin' => auth('admin')->user()?->email,
    ];
});