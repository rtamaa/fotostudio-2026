@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold mb-4">Pembayaran Berhasil! 🎉</h1>
        <p class="text-gray-600 mb-6">Booking Anda telah dikonfirmasi.</p>
        <a href="{{ route('booking.history') }}" class="bg-pink-500 text-white px-6 py-2 rounded-lg">Lihat Riwayat</a>
    </div>
</div>
@endsection