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

    {{-- Hero Section --}}
    <div class="relative" style="background: #F8BBD0;">
        <div class="max-w-6xl mx-auto px-4 py-20 text-center relative z-10">
            <div class="flex justify-center mb-6">
                <div class="bg-white/30 rounded-full p-4 backdrop-blur-sm">
                    <span class="text-7xl">📸</span>
                </div>
            </div>
            <h1 class="text-6xl md:text-7xl font-black mb-4 tracking-tight text-black">
                Studio Self-Photo
                <span class="inline-block bounce-emoji ml-2">✨</span>
            </h1>
            <p class="text-xl md:text-2xl mb-2 font-medium text-black">
                "Abadikan senyum terbaikmu dalam sekejap!"
            </p>
            <p class="text-lg text-black/70 mb-8">
                🎉 Self photo studio pertama yang 100% seru dan anti ribet! 🎉
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('booking.calendar') }}" 
                   class="group bg-black text-white px-8 py-4 rounded-full font-bold text-lg shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105 inline-flex items-center gap-2">
                    <span>📷 Booking Sekarang</span>
                    <span class="group-hover:translate-x-1 transition">→</span>
                </a>
                @guest
                <a href="{{ route('register') }}" 
                   class="bg-white/80 backdrop-blur-sm text-black px-8 py-4 rounded-full font-bold text-lg hover:bg-white transition-all duration-300 inline-flex items-center gap-2">
                    <span>🎁 Daftar Akun</span>
                    <span>✨</span>
                </a>
                @else
                <a href="{{ route('booking.history') }}" 
                   class="bg-white/80 backdrop-blur-sm text-black px-8 py-4 rounded-full font-bold text-lg hover:bg-white transition-all duration-300 inline-flex items-center gap-2">
                    <span>📋 Riwayat Booking</span>
                    <span>→</span>
                </a>
                @endguest
            </div>
            
            {{-- Statistik singkat --}}
            <div class="mt-12 flex justify-center gap-8 text-black/60 text-sm">
                <div class="text-center">
                    <p class="text-2xl font-bold text-black">{{ \App\Models\Booking::where('booking_status', 'confirmed')->count() }}</p>
                    <p>Booking Selesai</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-black">{{ \App\Models\Package::count() }}</p>
                    <p>Paket Foto</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-black">{{ \App\Models\User::count() }}</p>
                    <p>Pelanggan Puas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Paket Foto Section --}}
    <div class="max-w-6xl mx-auto px-4 py-20">
        <div class="text-center mb-12">
            <div class="inline-flex items-center gap-2 bg-white/50 text-black px-4 py-2 rounded-full mb-4">
                <span>🎁</span>
                <span class="font-semibold">Pilihan Spesial Untukmu</span>
                <span>🎁</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold text-black mb-4">
                Pilih Paket Favoritmu!
            </h2>
            <p class="text-black/70 text-lg">Dijamin puas, foto keren, harga merakyat! 🚀</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            @php
                $packages = \App\Models\Package::where('is_active', true)->orderBy('sort_order')->get();
                $icons = ['🥈', '🥇', '💎'];
                $bgColors = ['bg-pink-100', 'bg-rose-100', 'bg-fuchsia-100'];
            @endphp

            @forelse($packages as $index => $package)
                <div class="group card-hover transition-all duration-300">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border-2 border-pink-200 hover:border-pink-400 relative">
                        @if($loop->first)
                            <div class="absolute -top-2 -right-2 z-10">
                                <div class="bg-gradient-to-r from-yellow-400 to-orange-400 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg transform rotate-12">
                                    🔥 POPULER!
                                </div>
                            </div>
                        @endif
                        
                        <div class="{{ $bgColors[$index] }} p-6 text-center">
                            <span class="text-7xl floating-emoji inline-block">{{ $icons[$index] }}</span>
                            <h3 class="text-2xl font-bold text-black mt-2">{{ $package->name }}</h3>
                        </div>

                        <div class="p-6">
                            <p class="text-black/60 text-sm mb-4 flex items-center gap-1">
                                <span>⏱️</span> {{ $package->duration }} menit
                                <span class="mx-2">•</span>
                                <span>👥</span> Max {{ $package->max_people }} orang
                            </p>
                            <p class="text-black/70 mb-4 text-sm">{{ $package->short_description }}</p>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center gap-2 text-sm text-black/60">
                                    <span class="text-green-500">✓</span> Free Softcopy
                                </div>
                                <div class="flex items-center gap-2 text-sm text-black/60">
                                    <span class="text-green-500">✓</span> Lighting Profesional
                                </div>
                            </div>

                            <div class="border-t border-pink-100 pt-4 mt-4">
                                <p class="text-3xl font-bold text-black">
                                    {{ $package->formatted_price }}
                                </p>
                                <p class="text-xs text-black/40">/sesi</p>
                            </div>

                            <a href="{{ route('booking.calendar') }}" 
                               class="mt-4 block text-center bg-black text-white py-3 rounded-xl font-semibold hover:bg-gray-800 transition-all duration-300 transform hover:scale-105">
                                Pilih Paket Ini 🎯
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12">
                    <p class="text-gray-500">Belum ada paket tersedia. Silakan hubungi admin.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Keunggulan Section --}}
    <div class="bg-pink-100 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-black mb-2">
                    ✨ Kenapa Pilih Kami? ✨
                </h2>
                <p class="text-black/60">Kami bikin proses foto jadi seru dan gampang!</p>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center group">
                    <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-all duration-300">
                        <span class="text-5xl group-hover:animate-bounce inline-block">💳</span>
                    </div>
                    <h3 class="font-bold text-xl text-black mb-2">Bayar Gampang</h3>
                    <p class="text-black/60">Transfer Bank, QRIS, Virtual Account. Semua metode tersedia!</p>
                </div>
                <div class="text-center group">
                    <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-all duration-300">
                        <span class="text-5xl group-hover:animate-spin inline-block">🔒</span>
                    </div>
                    <h3 class="font-bold text-xl text-black mb-2">Anti Double Booking</h3>
                    <p class="text-black/60">Sistem canggih mencegah jadwal bentrok. Tenang, aman!</p>
                </div>
                <div class="text-center group">
                    <div class="bg-white w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-all duration-300">
                        <span class="text-5xl group-hover:animate-bounce inline-block">🎉</span>
                    </div>
                    <h3 class="font-bold text-xl text-black mb-2">Result Memuaskan</h3>
                    <p class="text-black/60">Peralatan profesional, hasil foto instagramable!</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Cara Booking Section --}}
    <div class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-black mb-2">
                📋 Cara Mudah Booking
            </h2>
            <p class="text-black/60">Hanya 4 langkah mudah! 🚀</p>
        </div>
        <div class="grid md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold text-pink-600">1</div>
                <p class="font-semibold text-black">Pilih Paket</p>
                <p class="text-sm text-black/50">Pilih paket foto favoritmu</p>
            </div>
            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold text-pink-600">2</div>
                <p class="font-semibold text-black">Pilih Jadwal</p>
                <p class="text-sm text-black/50">Pilih tanggal & jam yang tersedia</p>
            </div>
            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold text-pink-600">3</div>
                <p class="font-semibold text-black">Transfer & Upload</p>
                <p class="text-sm text-black/50">Transfer ke rekening kami, upload bukti</p>
            </div>
            <div class="text-center">
                <div class="bg-pink-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl font-bold text-pink-600">4</div>
                <p class="font-semibold text-black">Konfirmasi Admin</p>
                <p class="text-sm text-black/50">Admin konfirmasi, booking selesai!</p>
            </div>
        </div>
    </div>

    {{-- Testimoni Section --}}
    <div class="bg-pink-50 py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-black mb-2">
                    💬 Apa Kata Mereka?
                </h2>
                <p class="text-black/60">Testimoni dari pelanggan kami</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-2 text-yellow-400 mb-3">★★★★★</div>
                    <p class="text-gray-600 text-sm">"Fotonya bagus banget! Proses bookingnya mudah dan cepat. Recommended!"</p>
                    <p class="font-semibold text-black mt-3">— Sarah</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-2 text-yellow-400 mb-3">★★★★★</div>
                    <p class="text-gray-600 text-sm">"Studio lengkap, propertinya banyak. Hasil fotonya keren! Bakal balik lagi."</p>
                    <p class="font-semibold text-black mt-3">— Andi</p>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-md">
                    <div class="flex items-center gap-2 text-yellow-400 mb-3">★★★★★</div>
                    <p class="text-gray-600 text-sm">"Harga terjangkau, pelayanan ramah. Puas banget!"</p>
                    <p class="font-semibold text-black mt-3">— Putri</p>
                </div>
            </div>
        </div>
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