@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <!-- Icon Error -->
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        
        <!-- Title -->
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Pembayaran Gagal! 😢</h1>
        
        <!-- Description -->
        <p class="text-gray-600 mb-2">Terjadi kesalahan saat memproses pembayaran Anda.</p>
        <p class="text-gray-500 mb-6">Silakan coba lagi atau hubungi customer service.</p>
        
        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('booking.history') }}" 
               class="bg-pink-500 text-white px-6 py-2 rounded-lg hover:bg-pink-600 transition">
                Coba Lagi
            </a>
            <a href="{{ route('booking.calendar') }}" 
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                Booking Ulang
            </a>
        </div>
        
        <!-- Error Message (if any) -->
        @if(session('error'))
        <div class="mt-6 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-600 text-sm">{{ session('error') }}</p>
        </div>
        @endif
        
        <!-- Additional Info -->
        <p class="text-xs text-gray-400 mt-6">
            Pastikan koneksi internet Anda stabil.<br>
            Jika saldo terpotong, hubungi customer service.
        </p>
    </div>
</div>
@endsection