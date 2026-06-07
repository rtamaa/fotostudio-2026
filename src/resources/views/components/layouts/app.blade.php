<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Studio Self-Photo') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body style="background: #FCE4EC;">
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-pink-600">📸 Studio Self-Photo</a>
                <div class="flex space-x-4">
                    @auth
                        <a href="{{ route('booking.calendar') }}">Booking</a>
                        <a href="{{ route('booking.history') }}">Riwayat</a>
                        <a href="{{ route('profile.edit') }}">Profil</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main>
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>