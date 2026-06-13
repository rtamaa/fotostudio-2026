<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Livewire\Booking\Calendar;
use App\Livewire\Booking\BookingHistory;
use App\Livewire\Profile\ProfileForm;

use App\Models\Feedback;
use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| PUBLIC HOME
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| GUEST ROUTES
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

/*
|--------------------------------------------------------------------------
| AUTH USER ROUTES
|--------------------------------------------------------------------------
*/
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
| FEEDBACK SYSTEM (PUBLIC)
|--------------------------------------------------------------------------
*/
Route::post('/feedback', function (Request $request) {

    $request->validate([
        'message' => 'required|string|max:1000',
        'name'    => 'nullable|string|max:100',
    ]);

    Feedback::create([

        // kalau login simpan user id
        'user_id' => auth()->id(),

        // kalau guest pakai nama dari form
        'name' => auth()->check()
            ? auth()->user()->name
            : ($request->name ?: 'Anonymous'),

        'message'  => $request->message,
        'status'   => 'new',
        'priority' => 'medium',
    ]);

    return back()->with(
        'success',
        'Kritik & saran berhasil dikirim!'
    );

})->name('feedback.store');

/*
|--------------------------------------------------------------------------
| REVIEW SYSTEM (BOOKING-BASED PRO ⭐)
|--------------------------------------------------------------------------
*/
Route::post('/review', function (Request $request) {

    $request->validate([
        'rating'     => 'required|integer|min:1|max:5',
        'comment'    => 'required|string|max:1000',
        'booking_id' => 'required|exists:bookings,id',
    ]);

    $userId = auth()->id();

    /*
    🔒 VALIDASI BOOKING:
    - harus milik user
    - harus confirmed
    */
    $booking = Booking::where('id', $request->booking_id)
        ->where('user_id', $userId)
        ->where('booking_status', 'confirmed')
        ->first();

    if (!$booking) {
        return back()->withErrors([
            'review' => 'Booking tidak valid atau belum selesai.'
        ]);
    }

    /*
    🔒 ANTI SPAM:
    - 1 booking hanya boleh 1 review
    */
    $exists = Review::where('booking_id', $booking->id)->exists();

    if ($exists) {
        return back()->withErrors([
            'review' => 'Booking ini sudah pernah direview.'
        ]);
    }

    /*
    ⭐ CREATE REVIEW (PRO SYSTEM)
    */
    Review::create([
        'user_id'     => $userId,
        'booking_id'  => $booking->id,
        'rating'      => $request->rating,
        'comment'     => $request->comment,

        // moderation system
        'status'      => 'pending',

        // verified otomatis karena valid booking
        'is_verified' => true,
    ]);

    return back()->with('success_review', 'Review berhasil dikirim!');

})->middleware('auth')->name('review.store');

/*
|--------------------------------------------------------------------------
| DEBUG (SAFE)
|--------------------------------------------------------------------------
*/
Route::get('/debug-guards', function () {
    return [
        'web'   => auth('web')->user()?->email,
        'admin' => auth('admin')->user()?->email,
    ];
});