@extends('components.layouts.app')

@section('content')
<style>
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-8px); }
    }
    @keyframes pulse-glow {
        0%, 100% { box-shadow: 0 0 5px rgba(0,0,0,0.1); }
        50% { box-shadow: 0 0 20px rgba(0,0,0,0.2); }
    }
    .floating-emoji {
        animation: float 3s ease-in-out infinite;
    }
    .bounce-emoji {
        animation: bounce-slow 2s ease-in-out infinite;
    }
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .btn-pulse {
        animation: pulse-glow 2s ease-in-out infinite;
    }
    .btn-pulse:hover {
        animation: none;
        transform: scale(1.05);
    }
</style>

<div class="relative overflow-hidden" style="background: #FCE4EC;">
    {{-- Background Pattern Lucu --}}
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-10 left-10 text-6xl floating-emoji">📸</div>
        <div class="absolute top-20 right-20 text-5xl bounce-emoji">✨</div>
        <div class="absolute bottom-10 left-1/4 text-7xl floating-emoji">🎨</div>
        <div class="absolute bottom-20 right-1/3 text-6xl bounce-emoji">🌟</div>
        <div class="absolute top-1/3 left-1/3 text-4xl floating-emoji">🦄</div>
        <div class="absolute bottom-1/3 right-1/4 text-5xl bounce-emoji">🌈</div>
    </div>
    
    {{-- HERO --}}
    <div class="relative" style="background:#F8BBD0;">
        <div class="max-w-6xl mx-auto px-4 py-20 text-center">

            <div class="text-7xl mb-6">📸</div>

            <h1 class="text-6xl font-black text-black mb-4">
                Studio Self-Photo ✨
            </h1>

            <p class="text-xl mb-2">
                "Abadikan senyum terbaikmu!"
            </p>

            <p class="text-black/70 mb-8">
                Self photo studio anti ribet 🎉
            </p>

            <a href="{{ route('booking.calendar') }}"
               class="bg-black text-white px-8 py-4 rounded-full font-bold">
                Booking Sekarang →
            </a>

            {{-- STATS --}}
            <div class="mt-12 flex justify-center gap-8 text-sm">

                <div>
                    <p class="text-2xl font-bold">
                        {{ \App\Models\Booking::where('booking_status','confirmed')->count() }}
                    </p>
                    <p>Booking</p>
                </div>

                <div>
                    <p class="text-2xl font-bold">
                        {{ \App\Models\Package::count() }}
                    </p>
                    <p>Paket</p>
                </div>

                <div>
                    <p class="text-2xl font-bold">
                        {{ \App\Models\User::count() }}
                    </p>
                    <p>User</p>
                </div>

            </div>

        </div>
    </div>

    {{-- PACKAGE --}}
    <div class="max-w-6xl mx-auto px-4 py-16">

        <h2 class="text-3xl font-bold text-center mb-8">
            📦 Paket Foto
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            @php
                $packages = \App\Models\Package::where('is_active',true)->get();
            @endphp

            @foreach($packages as $p)
                <div class="bg-white p-6 rounded-xl shadow text-center">

                    <h3 class="font-bold text-xl">{{ $p->name }}</h3>

                    <p class="text-gray-500 mt-2">
                        {{ $p->formatted_price }}
                    </p>

                    <a href="{{ route('booking.calendar') }}"
                       class="mt-4 inline-block bg-black text-white px-4 py-2 rounded">
                        Pilih
                    </a>

                </div>
            @endforeach

        </div>
    </div>

    {{-- CARA BOOKING --}}
<div class="max-w-6xl mx-auto px-4 py-16">

    <h2 class="text-3xl font-bold text-center mb-2">
        📋 Cara Mudah Booking
    </h2>

    <p class="text-center text-black/60 mb-10">
        Hanya 4 langkah mudah! 🚀
    </p>

    @php
        $steps = \App\Models\BookingStep::orderBy('step_number')->get();
    @endphp

    <div class="grid md:grid-cols-4 gap-6">

        @forelse($steps as $step)
            <div class="text-center">
                <div class="text-5xl mb-3">
                    {{ $step->icon }}
                </div>

                <div class="bg-pink-100 w-10 h-10 rounded-full flex items-center justify-center mx-auto mb-3 text-sm font-bold text-pink-600">
                    {{ $step->step_number }}
                </div>

                <p class="font-semibold text-black">
                    {{ $step->title }}
                </p>

                <p class="text-sm text-black/50">
                    {{ $step->description }}
                </p>
            </div>
        @empty
            <div class="col-span-4 text-center text-gray-500">
                Belum ada data step booking
            </div>
        @endforelse

    </div>
</div>

    {{-- REVIEW SYSTEM --}}
    <div class="max-w-6xl mx-auto px-4 py-16">

        <h2 class="text-3xl font-bold text-center mb-6">
            ⭐ Review Pengguna
        </h2>

        {{-- FORM REVIEW --}}
        @auth

        @php
            $canReview = \App\Models\Booking::where('user_id', auth()->id())
                ->where('booking_status','confirmed')
                ->exists();

            $alreadyReviewed = \App\Models\Review::where('user_id', auth()->id())->exists();
        @endphp

        @if($canReview && !$alreadyReviewed)

        <form action="{{ route('review.store') }}" method="POST"
              class="bg-white p-6 rounded-xl shadow mb-10">
            @csrf

            <select name="rating" class="border w-full p-2 mb-3">
                <option value="5">⭐⭐⭐⭐⭐</option>
                <option value="4">⭐⭐⭐⭐</option>
                <option value="3">⭐⭐⭐</option>
                <option value="2">⭐⭐</option>
                <option value="1">⭐</option>
            </select>

            <textarea name="comment"
                      class="w-full border p-3"
                      placeholder="Tulis review..."></textarea>

            <button class="mt-3 bg-black text-white px-4 py-2 rounded">
                Kirim Review
            </button>

        </form>

        @endif
        @endauth

        {{-- LIST REVIEW --}}
        @php
        $reviews = \App\Models\Review::where('status', 'approved')
            ->latest()
            ->take(6)
            ->get();
        @endphp

        <div class="grid md:grid-cols-3 gap-6">

            @foreach($reviews as $review)

                <div class="bg-white p-6 rounded-xl shadow">

                    <div class="text-yellow-400 mb-2">
                        {{ str_repeat('⭐', $review->rating) }}
                    </div>

                    <p class="text-gray-600 text-sm">
                        "{{ $review->comment }}"
                    </p>

                    @php
                        $name = $review->user->name ?? 'User';

                        if (strlen($name) <= 2) {
                            $maskedName = substr($name, 0, 1) . '*';
                        } else {
                            $maskedName =
                                substr($name, 0, 1)
                                . str_repeat('*', max(strlen($name) - 2, 3))
                                . substr($name, -1);
                        }
                    @endphp

                    <p class="mt-3 font-bold">
                        {{ $maskedName }}
                    </p>

                </div>

            @endforeach

        </div>

    {{-- FEEDBACK / KRITIK SARAN --}}

    <div class="max-w-4xl mx-auto px-4 py-16">

    <h2 class="text-3xl font-bold text-center mb-6">
        💬 Kritik & Saran
    </h2>

    <form action="{{ route('feedback.store') }}" method="POST"
        class="bg-white p-6 rounded-xl shadow">
        @csrf

        @guest
        <input
            type="text"
            name="name"
            class="w-full border p-3 mb-3"
            placeholder="Nama (opsional)">
        @endguest

        <textarea
            name="message"
            class="w-full border p-3"
            placeholder="Tulis kritik & saran..."
            required></textarea>

        <button
            class="mt-3 bg-pink-600 text-white px-4 py-2 rounded">
            Kirim
        </button>

    </form>

</div>

    {{-- CTA Section --}}
    <div class="max-w-4xl mx-auto px-4 py-16 text-center">
        <div class="bg-black rounded-3xl p-8 md:p-12 shadow-2xl transform hover:scale-105 transition-all duration-500">
            <div class="flex justify-center mb-4">
                <div class="flex gap-2">
                    <span class="text-5xl animate-bounce inline-block" style="animation-delay: 0s">📸</span>
                    <span class="text-5xl animate-bounce inline-block" style="animation-delay: 0.2s">🎉</span>
                    <span class="text-5xl animate-bounce inline-block" style="animation-delay: 0.4s">✨</span>
                    <span class="text-5xl animate-bounce inline-block" style="animation-delay: 0.6s">🌟</span>
                    <span class="text-5xl animate-bounce inline-block" style="animation-delay: 0.8s">🦄</span>
                </div>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Siap Abadikan Momen Spesialmu?
            </h2>
            <p class="text-pink-200 text-lg mb-8">
                Jangan sampai kehabisan slot! Booking sekarang sebelum kepleset! 😜
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('booking.calendar') }}" 
                   class="bg-white text-black px-8 py-4 rounded-full font-bold text-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 inline-flex items-center justify-center gap-2 btn-pulse">
                    <span>🎯 Booking Sekarang</span>
                    <span>→</span>
                </a>
                @guest
                <a href="{{ route('register') }}" 
                   class="bg-pink-600 text-white px-8 py-4 rounded-full font-bold text-lg hover:bg-pink-700 transition-all duration-300 inline-flex items-center justify-center gap-2">
                    <span>📝 Daftar Dulu Yuk!</span>
                    <span>✨</span>
                </a>
                @endguest
            </div>
            <p class="text-pink-200 text-sm mt-6">
                *Garansi puas atau foto ulang gratis! (syarat & ketentuan berlaku)
            </p>
        </div>
    </div>

    {{-- Footer --}}
    <div class="bg-pink-200 py-8 mt-10">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex justify-center gap-4 mb-4 text-2xl">
                <span class="hover:scale-125 transition cursor-pointer">📸</span>
                <span class="hover:scale-125 transition cursor-pointer">🎨</span>
                <span class="hover:scale-125 transition cursor-pointer">✨</span>
                <span class="hover:scale-125 transition cursor-pointer">💖</span>
                <span class="hover:scale-125 transition cursor-pointer">🌟</span>
            </div>
            <p class="text-black/60 text-sm">
                © {{ date('Y') }} Studio Self-Photo - "Jadi Bintang di Fotomu Sendiri!" ⭐
            </p>
            <p class="text-black/40 text-xs mt-2">
                Made with <span class="text-pink-500">❤️</span> for happy moments
            </p>
        </div>
    </div>
</div>
@endsection