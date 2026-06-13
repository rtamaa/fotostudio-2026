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

            {{-- LOGO --}}
            <a href="{{ route('home') }}"
               class="text-xl font-bold text-pink-600">
                📸 Studio Self-Photo
            </a>

            {{-- NAV MENU --}}
            <div class="flex items-center gap-6 text-sm font-medium">

                <a href="{{ route('home') }}"
                   class="hover:text-pink-600">
                    🏠 Beranda
                </a>

                @auth

                    <a href="{{ route('booking.calendar') }}"
                       class="hover:text-pink-600">
                        📅 Booking
                    </a>

                    <a href="{{ route('booking.history') }}"
                       class="hover:text-pink-600">
                        📖 Riwayat
                    </a>

                    <a href="{{ route('profile.edit') }}"
                       class="hover:text-pink-600">
                        👤 Profil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="text-red-500 hover:text-red-700">
                            🚪 Logout
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}"
                       class="px-3 py-1 rounded hover:text-pink-600">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-3 py-1 bg-pink-500 text-white rounded hover:bg-pink-600">
                        Register
                    </a>

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