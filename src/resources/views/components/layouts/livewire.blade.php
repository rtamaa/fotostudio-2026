<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body style="background:#FCE4EC">

    {{-- NAVBAR --}}
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">

            <div class="flex justify-between h-16 items-center">

                {{-- LOGO --}}
                <a href="{{ route('home') }}"
                   class="font-bold text-pink-600">
                    📸 Studio Self-Photo
                </a>

                {{-- MENU --}}
                <div class="flex items-center space-x-5">

                    {{-- 🏠 BERANDA --}}
                    <a href="{{ route('home') }}"
                       class="hover:text-pink-600 font-medium">
                        🏠 Beranda
                    </a>

                    @auth

                        <a href="{{ route('booking.calendar') }}"
                           class="hover:text-pink-600 font-medium">
                            📅 Booking
                        </a>

                        <a href="{{ route('booking.history') }}"
                           class="hover:text-pink-600 font-medium">
                            📖 Riwayat
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="hover:text-pink-600 font-medium">
                            👤 Profil
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="text-red-500 hover:text-red-700 font-medium">
                                🚪 Logout
                            </button>
                        </form>

                    @else

                        <a href="{{ route('login') }}"
                           class="hover:text-pink-600 font-medium">
                            Login
                        </a>

                        <a href="{{ route('register') }}"
                           class="hover:text-pink-600 font-medium">
                            Register
                        </a>

                    @endauth

                </div>

            </div>

        </div>
    </nav>

    {{-- CONTENT --}}
    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>