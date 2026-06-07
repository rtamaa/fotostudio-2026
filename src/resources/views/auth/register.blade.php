@extends('components.layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-center mb-6">Daftar Akun</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <input type="text" name="name" placeholder="Nama Lengkap" class="w-full border rounded-lg p-2 mb-4" required>
            <input type="email" name="email" placeholder="Email" class="w-full border rounded-lg p-2 mb-4" required>
            <input type="tel" name="phone" placeholder="No WhatsApp" class="w-full border rounded-lg p-2 mb-4">
            <input type="password" name="password" placeholder="Password" class="w-full border rounded-lg p-2 mb-4" required>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" class="w-full border rounded-lg p-2 mb-4" required>
            <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg">Daftar</button>
        </form>
        <p class="text-center mt-4">Sudah punya akun? <a href="{{ route('login') }}" class="text-pink-600">Login</a></p>
    </div>
</div>
@endsection