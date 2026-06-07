<div>
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-pink-500 to-rose-500 px-6 py-6">
                <h1 class="text-2xl font-bold text-white">📸 Pilih Jadwal Foto</h1>
            </div>

            <div class="p-6 border-b">
                <h2 class="font-semibold mb-4">Pilih Paket:</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse($packages as $package)
                        <div wire:click="$set('selectedPackage', {{ $package->id }})"
                             class="border rounded-lg p-4 cursor-pointer transition
                                    {{ $selectedPackage == $package->id ? 'bg-pink-50 border-pink-500' : 'hover:border-pink-300' }}">
                            <h3 class="font-bold">{{ $package->name }}</h3>
                            <p class="text-pink-600 font-bold">{{ $package->formatted_price }}</p>
                            <p class="text-gray-500 text-sm">⏱️ {{ $package->duration }} menit</p>
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-3 text-center">Belum ada paket tersedia</p>
                    @endforelse
                </div>
            </div>

            <div class="p-6 border-b">
                <div class="flex justify-between items-center">
                    <button wire:click="previousDay" class="px-4 py-2 bg-gray-200 rounded-lg">← Sebelumnya</button>
                    <h2 class="text-xl font-semibold">{{ \Carbon\Carbon::parse($selectedDate)->format('F Y') }}</h2>
                    <button wire:click="nextDay" class="px-4 py-2 bg-gray-200 rounded-lg">Berikutnya →</button>
                </div>
            </div>

            @if($selectedPackage)
                <div class="p-6">
                    <h3 class="font-semibold mb-4">Slot Tersedia untuk {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}</h3>
                    
                    @if(count($availableSlots) > 0)
                        <div class="grid grid-cols-3 md:grid-cols-5 gap-3">
                            @foreach($availableSlots as $slot)
                                <button wire:click="selectSlot('{{ $slot['start'] }}')"
                                        class="px-4 py-3 rounded-lg text-center transition
                                               {{ $selectedSlot == $slot['start'] ? 'bg-pink-600 text-white' : 'bg-green-50 border-2 border-green-200 hover:bg-green-100' }}">
                                    {{ $slot['display'] }}
                                </button>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">Tidak ada slot tersedia</p>
                    @endif
                </div>
            @endif

            @if($showBookingForm && $selectedPackage && $selectedSlot)
                <div class="border-t p-6 bg-gray-50">
                    @livewire('booking.booking-form', [
                        'packageId' => $selectedPackage,
                        'bookingDate' => $selectedDate,
                        'startTime' => $selectedSlot,
                    ])
                </div>
            @endif
        </div>
    </div>
</div>