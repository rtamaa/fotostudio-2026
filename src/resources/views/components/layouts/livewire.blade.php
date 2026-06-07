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

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}">
                    📸 Studio Self-Photo
                </a>
            </div>
        </div>
    </nav>

    <main>
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>